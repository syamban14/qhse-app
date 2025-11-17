<?php

namespace App\Livewire;

use App\Models\Incident;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IncidentCreate extends Component
{
    public $incident_type;
    public $location;
    public $description;
    public $incident_time;
    public $category;
    public $location_details;
    public $latitude;
    public $longitude;

    public $categories = [
        'Kecelakaan Lalu Lintas',
        'Cedera di Gudang',
        'Kerusakan Kargo',
        'Tumpahan Bahan Berbahaya',
        'Insiden Peralatan',
        'Hampir Celaka (Near Miss)',
        'Lainnya',
    ];

    protected $rules = [
        'incident_type' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'required|string',
        'incident_time' => 'required|date',
        'category' => 'required|string|max:255',
        'location_details' => 'nullable|string',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
    ];

    public function mount()
    {
        $this->incident_time = now()->format('Y-m-d\TH:i');
    }

    public function save()
    {
        $this->validate();

        Incident::create([
            'reporter_id' => Auth::id(),
            'incident_type' => $this->incident_type,
            'location' => $this->location,
            'description' => $this->description,
            'incident_time' => $this->incident_time,
            'category' => $this->category,
            'location_details' => $this->location_details,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => 'reported',
        ]);

        return redirect()->route('incidents.index');
    }

    public function render()
    {
        return view('livewire.incident-create');
    }
}