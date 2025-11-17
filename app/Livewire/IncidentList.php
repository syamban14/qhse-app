<?php

namespace App\Livewire;

use App\Models\Incident;
use Livewire\Component;
use Livewire\WithPagination;

class IncidentList extends Component
{
    use WithPagination;

    public function render()
    {
        $incidents = Incident::with('reporter')
            ->latest()
            ->paginate(10);

        return view('livewire.incident-list', [
            'incidents' => $incidents,
        ]);
    }

    public function delete(Incident $incident)
    {
        abort_if(!auth()->user()->can('delete incident'), 403);
        $incident->delete();
    }
}