<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Accident;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(): View
    {
        // KPI Cards Data
        $openIncidentsCount = Accident::doesntHave('rca')->count();
        $openActionsCount = Action::where('status', 'open')->count();
        $overdueActionsCount = Action::where('status', 'open')->where('due_date', '<', now())->count();

        // My Pending Actions Table Data
        $myPendingActions = Action::where('pic_user_id', Auth::id())
            ->where('status', 'open')
            ->with(['car.rootCauseAnalysis.accident', 'incident'])
            ->latest('due_date')
            ->get();

        // Chart 1: Action Status Breakdown
        $actionStatuses = Action::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $actionByStatusLabels = $actionStatuses->pluck('status')->map(fn($label) => ucfirst($label));
        $actionByStatusData = $actionStatuses->pluck('total');

        // Chart 2: Incidents per Month (Last 12 Months)
        $incidentsByMonth = Accident::select(
                DB::raw("to_char(accident_date, 'YYYY-MM') as month"),
                DB::raw('count(*) as total')
            )
            ->where('accident_date', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        $incidentByMonthLabels = $incidentsByMonth->pluck('month');
        $incidentByMonthData = $incidentsByMonth->pluck('total');

        // KPI & Chart: Division with Most Accidents
        $topDivision = null;
        $divisionAccidentLabels = [];
        $divisionAccidentData = [];
        try {
            // 1. Get accident counts per payroll_id from the app DB
            $accidentCounts = DB::connection('pgsql')->table('accidents')
                ->select('employee_payroll_id', DB::raw('COUNT(id) as accident_count'))
                ->whereNotNull('employee_payroll_id')
                ->groupBy('employee_payroll_id')
                ->get();

            if ($accidentCounts->isNotEmpty()) {
                $payrollIds = $accidentCounts->pluck('employee_payroll_id');

                // 2. Get the division for each of those payroll_ids from the master DB
                $karyawanDivisions = DB::connection('pgsql_master')->table('m_karyawan')
                    ->whereIn('payroll_id', $payrollIds)
                    ->pluck('div_id', 'payroll_id');

                // 3. Aggregate counts per division in PHP
                $accidentsPerDivision = [];
                foreach ($accidentCounts as $acc) {
                    $payrollId = $acc->employee_payroll_id;
                    $divId = $karyawanDivisions[$payrollId] ?? null;

                    if ($divId) {
                        if (!isset($accidentsPerDivision[$divId])) {
                            $accidentsPerDivision[$divId] = 0;
                        }
                        $accidentsPerDivision[$divId] += $acc->accident_count;
                    }
                }

                if (!empty($accidentsPerDivision)) {
                    arsort($accidentsPerDivision);

                    // 4. Get Top Division for KPI Card
                    $topDivisionId = key($accidentsPerDivision);
                    $topDivisionName = DB::connection('pgsql_master')->table('m_division')->where('div_code', $topDivisionId)->value('div_name');
                    if ($topDivisionName) {
                        $topDivision = (object)[
                            'div_name' => $topDivisionName,
                            'accident_count' => $accidentsPerDivision[$topDivisionId],
                        ];
                    }
                    
                    // 5. Prepare data for the Top 5 Divisions Chart
                    $top5Divisions = array_slice($accidentsPerDivision, 0, 5, true);
                    $top5DivisionIds = array_keys($top5Divisions);
                    
                    $divisionNames = DB::connection('pgsql_master')->table('m_division')
                        ->whereIn('div_code', $top5DivisionIds)
                        ->pluck('div_name', 'div_code');

                    foreach ($top5Divisions as $divId => $count) {
                        $divisionAccidentLabels[] = $divisionNames[$divId] ?? 'Unknown';
                        $divisionAccidentData[] = $count;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to calculate division accident data for internal dashboard: ' . $e->getMessage());
        }


        return view('dashboard', compact(
            'openIncidentsCount',
            'openActionsCount',
            'overdueActionsCount',
            'myPendingActions',
            'actionByStatusLabels',
            'actionByStatusData',
            'incidentByMonthLabels',
            'incidentByMonthData',
            'topDivision',
            'divisionAccidentLabels',
            'divisionAccidentData'
        ));
    }
}
