<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalIncidents = Incident::count();
        $openActions = Action::where('status', 'open')->count();
        $completedActions = Action::where('status', 'completed')->count();

        // Incidents by Type
        $incidentTypes = Incident::select('incident_type', DB::raw('count(*) as total'))
            ->groupBy('incident_type')
            ->get();
        $incidentByTypeLabels = $incidentTypes->pluck('incident_type')->map(fn($label) => ucfirst($label))->toArray();
        $incidentByTypeData = $incidentTypes->pluck('total')->toArray();

        // Actions by Status
        $actionStatuses = Action::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $actionByStatusLabels = $actionStatuses->pluck('status')->map(fn($label) => ucfirst($label))->toArray();
        $actionByStatusData = $actionStatuses->pluck('total')->toArray();

        // Incidents by Status
        $incidentStatuses = Incident::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $statusColorMap = [
            'reported' => 'rgb(239, 68, 68)', // Red
            'investigating' => 'rgb(251, 188, 5)', // Yellow
            'closed' => 'rgb(34, 197, 94)', // Green
            // Add more statuses and colors if needed
        ];

        $statusLabelMap = [
            'reported' => 'open',
            'investigating' => 'investigating',
            'closed' => 'close',
        ];

        $incidentByStatusLabels = $incidentStatuses->pluck('status')->map(function ($label) use ($statusLabelMap) {
            $lowerLabel = strtolower($label);
            return $statusLabelMap[$lowerLabel] ?? ucfirst($lowerLabel);
        })->toArray();

        $incidentByStatusData = $incidentStatuses->pluck('total')->toArray();

        $incidentByStatusColors = $incidentStatuses->pluck('status')->map(function ($status) use ($statusColorMap) {
            $lowerStatus = strtolower($status);
            return $statusColorMap[$lowerStatus] ?? 'rgb(156, 163, 175)'; // Default gray
        })->toArray();

        return view('dashboard', compact(
            'totalIncidents',
            'openActions',
            'completedActions',
            'incidentByTypeLabels',
            'incidentByTypeData',
            'actionByStatusLabels',
            'actionByStatusData',
            'incidentByStatusLabels',
            'incidentByStatusData',
            'incidentByStatusColors' // Pass colors to the view
        ));
    }
}