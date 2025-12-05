<?php

namespace App\Livewire;

use App\Models\Accident;
use App\Models\Master\Unit;
use App\Models\StoringEvent;
use App\Models\UnitMonthlyReport;
use Livewire\Component;

class ViolationShowUnit extends Component
{
    public Unit $unit;
    public $totalKilometer = 0;
    public $storingEventCount = 0;
    public $accidents;
    public $storingEvents; // To hold the collection of storing events

    public function mount(Unit $unit)
    {
        $this->unit = $unit;
        
        // Manually query across database connections
        
        // Calculate total kilometers
        $this->totalKilometer = UnitMonthlyReport::where('unit_id', $this->unit->id)->sum('kilometer');
        
        // Get related report IDs to fetch storing events
        $reportIds = UnitMonthlyReport::where('unit_id', $this->unit->id)->pluck('id');
        $this->storingEvents = StoringEvent::whereIn('unit_monthly_report_id', $reportIds)->with('driver.karyawan')->latest('event_date')->get();
        $this->storingEventCount = $this->storingEvents->count();

        // Get related accidents
        $this->accidents = Accident::where('m_unit_id', $this->unit->id)->latest()->get();
    }

    public function openStoringModal()
    {
        $this->dispatch('open-modal', 'storing-details');
    }

    public function render()
    {
        return view('livewire.violation-show-unit');
    }
}
