<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Accident;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $actionStatuses = Action::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $actionByStatusLabels = $actionStatuses->pluck('status')->map(fn($label) => ucfirst($label));
        $actionByStatusData = $actionStatuses->pluck('total');

        // Chart 2: Incidents per Month (Last 12 Months)
        $incidentsByMonth = Accident::select(
                \DB::raw("to_char(accident_date, 'YYYY-MM') as month"),
                \DB::raw('count(*) as total')
            )
            ->where('accident_date', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        $incidentByMonthLabels = $incidentsByMonth->pluck('month');
        $incidentByMonthData = $incidentsByMonth->pluck('total');

        // New KPI: Division with Most Accidents (Two-Query Approach)
        $topDivision = null;
        try {
            // 1. Get accident counts per payroll_id from the app DB
            $accidentCounts = \DB::connection('pgsql')->table('accidents')
                ->select('employee_payroll_id', \DB::raw('COUNT(id) as accident_count'))
                ->whereNotNull('employee_payroll_id')
                ->groupBy('employee_payroll_id')
                ->get();

            if ($accidentCounts->isNotEmpty()) {
                $payrollIds = $accidentCounts->pluck('employee_payroll_id');

                // 2. Get the division for each of those payroll_ids from the master DB
                $karyawanDivisions = \DB::connection('pgsql_master')->table('m_karyawan')
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
                    // 4. Find the top division ID and its count
                    arsort($accidentsPerDivision);
                    $topDivisionId = key($accidentsPerDivision);
                    $topDivisionCount = reset($accidentsPerDivision);

                    // 5. Get the name of the top division
                    $topDivisionName = \DB::connection('pgsql_master')->table('m_division')
                        ->where('div_code', $topDivisionId)
                        ->value('div_name');
                    
                    // 6. Assemble the final object
                    if ($topDivisionName) {
                        $topDivision = (object)[
                            'div_name' => $topDivisionName,
                            'accident_count' => $topDivisionCount,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Log the error or handle it gracefully
            \Log::error('Failed to calculate top division KPI: ' . $e->getMessage());
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
            'topDivision'
        ));
    }
}
