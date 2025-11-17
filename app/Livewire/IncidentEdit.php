<?php

namespace App\Livewire;

use App\Models\Incident;
use Livewire\Component;

class IncidentEdit extends Component
{
    public Incident $incident;
    public $incident_type;
    public $location;
    public $description;
    public $incident_time;
    public $status;
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

    protected function rules()
    {
        return [
            'incident_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_time' => 'required|date',
            'status' => 'required|in:reported,investigating,resolved',
            'category' => 'required|string|max:255',
            'location_details' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }

    public function mount(Incident $incident)
    {
        $this->incident = $incident;
        $this->incident_type = $incident->incident_type;
        $this->location = $incident->location;
        $this->description = $incident->description;
        $this->incident_time = $incident->incident_time->format('Y-m-d\TH:i');
        $this->status = $incident->status;
        $this->category = $incident->category;
        $this->location_details = $incident->location_details;
        $this->latitude = $incident->latitude;
        $this->longitude = $incident->longitude;
    }

    public function save()
    {
        $this->validate();

        $this->incident->update([
            'incident_type' => $this->incident_type,
            'location' => $this->location,
            'description' => $this->description,
            'incident_time' => $this->incident_time,
            'status' => $this->status,
            'category' => $this->category,
            'location_details' => $this->location_details,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        return redirect()->route('incidents.show', $this->incident);
    }

    public function render()
    {
        return view('livewire.incident-edit');
    }
}