<?php

namespace App\Livewire;

use App\Models\Master\Driver;
use App\Models\Master\Karyawan;
use App\Models\User;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViolationCreateDriver extends Component
{
    // Property for filtering driver based on URL param
    public string $driver_type = ''; // 'dumptruck', 'trailer', 'project'

    // Properti untuk filter dan pencarian driver
    public $driver_search = '';
    public $searchResults = [];
    public $selectedKaryawanName;

    // Properti untuk form
    public $violator_id;
    public $violator_type;
    public $violation_date;
    public $violation_category;
    public $description;
    public $sanction;
    public $location;
    public $nearmiss_report_alindo;

    // Hardcoded violation categories from user's CSV
    public $violationCategories = [
        'Fatigue',
        'Distraction',
        'Field of View (FOV)',
        'Rest Area Policy',
        'Pelanggaran Jam Larangan',
        'Accident',
        'Overspeed',
        'Continuous Driving',
        'Cell Phone Use',
        'Keikutsertaan DDT',
        'Penggunaan BCS Fit',
        'Lainnya',
    ];

    protected function rules()
    {
        return [
            'driver_type' => 'required|in:dumptruck,trailer,project',
            'violator_id' => 'required',
            'violator_type' => 'required|string',
            'violation_date' => 'required|date',
            'description' => 'required|string|min:10',
            'sanction' => 'nullable|string',
            'location' => 'required|string',
            'violation_category' => 'required|string',
            'nearmiss_report_alindo' => 'nullable|numeric|min:0',
        ];
    }

    public function mount()
    {
        // Initialize driver_type from URL query parameter
        $this->driver_type = request()->query('driver_type', '');
        
        // Redirect if driver_type is not provided or invalid
        if (!in_array($this->driver_type, ['dumptruck', 'trailer', 'project'])) {
            session()->flash('error', 'Tipe driver tidak valid. Silakan pilih kategori driver terlebih dahulu.');
            return $this->redirectRoute('violations.index'); // Redirect to a safe page
        }

        $this->violation_date = Carbon::now()->format('Y-m-d');
        $this->location = 'Area Proyek'; // Default value
    }

    public function updatedDriverSearch()
    {
        if (strlen($this->driver_search) < 2) {
            $this->searchResults = [];
            return;
        }

        $driverCategory = strtoupper($this->driver_type);

        $this->searchResults = Driver::with('karyawan')
            ->where('driver_category', $driverCategory)
            ->whereHas('karyawan', function ($query) {
                $query->where('nama_karyawan', 'ilike', '%' . $this->driver_search . '%')
                      ->orWhere('payroll_id', 'ilike', '%' . $this->driver_search . '%');
            })
            ->take(5)
            ->get();
    }

    public function selectDriver($driverId)
    {
        $driver = Driver::with('karyawan')->find($driverId);
        if ($driver && $driver->karyawan) {
            $this->violator_id = $driver->id;
            $this->violator_type = Driver::class;
            $this->selectedKaryawanName = $driver->karyawan->nama_karyawan . ' (' . $driver->karyawan->payroll_id . ')';
            $this->searchResults = [];
            $this->driver_search = '';
        }
    }

    public function changeKaryawan()
    {
        $this->violator_id = null;
        $this->violator_type = null;
        $this->selectedKaryawanName = null;
        $this->driver_search = '';
        $this->searchResults = [];
    }
    
    public function save()
    {
        $this->validate();

        // Gabungkan sanksi ke deskripsi jika ada
        $fullDescription = $this->description;
        if (!empty($this->sanction)) {
            $fullDescription .= "\n\nSanksi yang diberikan: " . $this->sanction;
        }

        Violation::create([
            'violator_id' => $this->violator_id,
            'violator_type' => $this->violator_type,
            'location' => $this->location,
            'violation_date' => $this->violation_date,
            'description' => $fullDescription,
            'rule_broken' => $this->violation_category,
            'nearmiss_report_alindo' => $this->nearmiss_report_alindo,
        ]);

        session()->flash('success', 'Data pelanggaran berhasil disimpan.');

        return $this->redirectRoute('violations.index');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // Map driver_type to a more readable format for display
        $displayDriverType = match ($this->driver_type) {
            'dumptruck' => 'Dumptruck',
            'trailer' => 'Trailer',
            'project' => 'Project',
            default => 'Unknown',
        };

        return view('livewire.violation-create-driver', [
            'displayDriverType' => $displayDriverType,
        ]);
    }
}