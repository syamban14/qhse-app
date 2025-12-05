<?php

namespace App\Livewire;

use App\Models\Master\Driver;
use App\Models\Master\Karyawan;
use App\Models\Master\Unit as MasterUnit;
use App\Models\UnitMonthlyReport;
use App\Models\StoringEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout; // Import the Layout attribute

#[Layout('layouts.app')] // Define the layout for this component
class UnitMonthlyReportPage extends Component
{
    // Query string properties
    public $kategori;

    // Collections for dropdowns
    public $units = [];
    public $categories = [];

    // Selected items
    public $selectedUnitId;

    // The monthly report instance
    public ?UnitMonthlyReport $report = null;

    // Form fields
    public $kilometer = 0;
    public $storingEvents = [];

    // New storing event form
    public $newStoring = [
        'event_date' => '',
        'event_time' => '',
        'week_of_month' => 1,
        'location' => '',
        'description' => '',
        'driver_id' => null,
    ];
    public $driverSearch = '';
    public $driverSearchResults = [];

    // UI state
    public $activeTab = 'minggu1';

    public function mount()
    {
        // Accept 'kategori' or 'type' from the query string
        $this->kategori = request()->query('kategori', request()->query('type', 'all'));
        
        $this->categories = MasterUnit::select('kategori')->distinct()->pluck('kategori');
        $this->loadUnits();
        $this->newStoring['event_date'] = today()->format('Y-m-d');
    }

    public function loadUnits()
    {
        $query = MasterUnit::orderBy('no_unit');
        if ($this->kategori && $this->kategori !== 'all') {
            $query->where('kategori', $this->kategori);
        }
        $this->units = $query->get();
    }

    public function updatedKategori()
    {
        $this->loadUnits();
        $this->selectedUnitId = null; // Reset unit selection
        $this->report = null; // Reset report
    }

    public function updatedSelectedUnitId($unitId)
    {
        if (!$unitId) {
            $this->report = null;
            return;
        }

        $this->report = UnitMonthlyReport::firstOrCreate(
            [
                'unit_id' => $unitId,
                'year' => Carbon::now()->year,
                'month' => Carbon::now()->month,
            ],
            [
                'kilometer' => 0,
                'user_id' => Auth::id(),
            ]
        );

        $this->loadReportDetails();
    }

    public function updatedDriverSearch($value)
    {
        if (strlen($value) < 2) {
            $this->driverSearchResults = [];
            return;
        }

        $this->driverSearchResults = Driver::with('karyawan')
            ->whereHas('karyawan', function($query) use ($value) {
                $query->where(DB::raw('LOWER(nama_karyawan)'), 'like', '%' . strtolower($value) . '%')
                      ->orWhere('payroll_id', 'like', '%' . $value . '%');
            })
            ->limit(5)
            ->get();
    }

    public function selectDriver($driverId, $driverName)
    {
        $this->newStoring['driver_id'] = $driverId;
        $this->driverSearch = $driverName;
        $this->driverSearchResults = [];
    }

    private function resetDriverSelection()
    {
        $this->newStoring['driver_id'] = null;
        $this->driverSearch = '';
        $this->driverSearchResults = [];
    }
    
    public function loadReportDetails()
    {
        if ($this->report) {
            $this->kilometer = $this->report->kilometer;
            $this->storingEvents = $this->report->storingEvents()->with('driver.karyawan')->orderBy('event_date')->get();
        }
    }

    public function saveKilometer()
    {
        $this->validate(['kilometer' => 'required|numeric|min:0']);

        if ($this->report) {
            $this->report->update(['kilometer' => $this->kilometer]);
            session()->flash('message', 'Kilometer berhasil diperbarui.');
        }
    }

    public function addStoringEvent()
    {
        $this->validate([
            'newStoring.event_date' => 'required|date',
            'newStoring.event_time' => 'required|date_format:H:i',
            'newStoring.location' => 'required|string|max:255',
            'newStoring.description' => 'required|string',
            'newStoring.driver_id' => 'nullable|exists:pgsql_master.m_drivers,id'
        ]);

        if (!$this->report) {
            session()->flash('error', 'Silakan pilih unit terlebih dahulu.');
            return;
        }

        // Determine week of month
        $date = Carbon::parse($this->newStoring['event_date']);
        
        $this->report->storingEvents()->create([
            'event_date' => $this->newStoring['event_date'],
            'event_time' => $this->newStoring['event_time'],
            'week_of_month' => $date->weekOfMonth,
            'location' => $this->newStoring['location'],
            'description' => $this->newStoring['description'],
            'driver_id' => $this->newStoring['driver_id'],
            'user_id' => Auth::id(),
        ]);

        // Reset form and reload events
        $this->reset('newStoring');
        $this->resetDriverSelection();
        $this->newStoring['event_date'] = today()->format('Y-m-d');
        $this->loadReportDetails();

        session()->flash('message', 'Temuan storing berhasil ditambahkan.');
    }
    
    public function render()
    {
        return view('livewire.unit-monthly-report-page');
    }
}