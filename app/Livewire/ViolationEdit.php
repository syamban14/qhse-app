<?php

namespace App\Livewire;

use App\Models\Master\Driver;
use App\Models\User;
use App\Models\Violation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViolationEdit extends Component
{
    public Violation $violation;

    // Form properties
    public $location;
    public $violation_date;
    public $description;
    public $rule_broken;

    // Properties for display and linking
    public $violatorName;
    public $violatorPayrollId;
    public $driverId;


    // Hardcoded violation categories for the dropdown
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

    public function mount(Violation $violation)
    {
        $this->violation = $violation->load('violator.karyawan');
        
        // Populate form fields
        $this->location = $violation->location;
        $this->violation_date = $violation->violation_date ? date('Y-m-d', strtotime($violation->violation_date)) : null;
        $this->description = $violation->description;
        $this->rule_broken = $violation->rule_broken;

        // Populate display and linking properties based on violator type
        $violator = $this->violation->violator;
        if ($violator instanceof Driver) {
            $this->violatorName = $violator->karyawan?->nama_karyawan;
            $this->violatorPayrollId = $violator->karyawan?->payroll_id;
            $this->driverId = $violator->id;
        } elseif ($violator instanceof User) {
            $this->violatorName = $violator->karyawan?->nama_karyawan;
            $this->violatorPayrollId = $violator->karyawan?->payroll_id;
            $this->driverId = $violator->karyawan?->driver?->id;
        }
    }

    protected function rules()
    {
        return [
            'location' => 'required|string',
            'violation_date' => 'required|date',
            'description' => 'required|string|min:10',
            'rule_broken' => 'required|string',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->violation->update([
            'location' => $this->location,
            'violation_date' => $this->violation_date,
            'description' => $this->description,
            'rule_broken' => $this->rule_broken,
        ]);

        session()->flash('success', 'Data pelanggaran berhasil diperbarui.');
        
        if ($this->driverId) {
            return $this->redirectRoute('violations.show.driver', ['driver' => $this->driverId]);
        }
        
        // Fallback redirect
        return $this->redirectRoute('violations.index');
    }

    public function render()
    {
        return view('livewire.violation-edit');
    }
}
