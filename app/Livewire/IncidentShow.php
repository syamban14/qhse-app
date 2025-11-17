<?php

namespace App\Livewire;

use App\Models\Incident;
use Livewire\Component;

class IncidentShow extends Component
{
    public Incident $incident;

    public function mount(Incident $incident)
    {
        $this->incident = $incident;
    }

    public function render()
    {
        return view('livewire.incident-show');
    }
}