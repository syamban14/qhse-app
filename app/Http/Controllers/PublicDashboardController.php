<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Accident;
use App\Models\SafetyTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PublicDashboardController extends Controller
{
    public function index()
    {
        // KPI Cards
        $totalIncidents = Accident::count();
        $openActions = Action::where('status', 'open')->count();
        $closedActions = Action::where('status', 'closed')->count();
        $safetyObservations = 0; // Placeholder for Leading Indicator

        // Safety Tip from Database
        $safetyTip = SafetyTip::where('is_active', true)->inRandomOrder()->first();
        $randomSafetyTip = $safetyTip ? $safetyTip->content : 'Selamat datang di dasbor QHSE. Pastikan untuk selalu memprioritaskan keselamatan.';

        // Data for Incidents per Month Chart (Last 12 Months)
        $incidentsByMonth = Accident::select(
                DB::raw('EXTRACT(YEAR FROM accident_date) as year'),
                DB::raw('EXTRACT(MONTH FROM accident_date) as month'),
                DB::raw('count(*) as count')
            )
            ->where('accident_date', '>=', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [$date->format('M Y') => $item->count];
            });

        // Fill in missing months with 0
        $incidentLabels = [];
        $incidentData = [];
        $currentMonth = Carbon::now()->subYear()->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $monthKey = $currentMonth->format('M Y');
            $incidentLabels[] = $monthKey;
            $incidentData[] = $incidentsByMonth[$monthKey] ?? 0;
            $currentMonth->addMonth();
        }

        // Data for Actions by Status Chart
        $actionsByStatus = Action::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $actionStatusLabels = $actionsByStatus->keys();
        $actionStatusData = $actionsByStatus->values();

        // New KPI: Division with Most Accidents (Two-Query Approach)
        $topDivision = null;
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
                    // 4. Find the top division ID and its count
                    arsort($accidentsPerDivision);
                    $topDivisionId = key($accidentsPerDivision);
                    $topDivisionCount = reset($accidentsPerDivision);

                    // 5. Get the name of the top division
                    $topDivisionName = DB::connection('pgsql_master')->table('m_division')
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


        return view('welcome', compact(
            'totalIncidents',
            'openActions',
            'closedActions',
            'safetyObservations',
            'randomSafetyTip',
            'incidentLabels',
            'incidentData',
            'actionStatusLabels',
            'actionStatusData',
            'topDivision'
        ));
    }
}