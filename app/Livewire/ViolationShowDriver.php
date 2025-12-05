<?php

namespace App\Livewire;

use App\Models\Accident;
use App\Models\Master\Driver;
use App\Models\Violation;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ViolationShowDriver extends Component
{
    use WithPagination;

    public Driver $driver;

    public function mount(Driver $driver)
    {
        $this->driver = $driver->load('karyawan');
    }

    public function render()
    {
        $violations = Violation::where('violator_type', Driver::class)
            ->where('violator_id', $this->driver->id)
            ->latest()
            ->paginate(10, ['*'], 'violationsPage');

        $accidents = collect();
        if ($this->driver->karyawan) {
            $accidents = Accident::where('employee_payroll_id', $this->driver->karyawan->payroll_id)
                ->latest('accident_date')
                ->paginate(5, ['*'], 'accidentsPage');
        }

        return view('livewire.violation-show-driver', [
            'violations' => $violations,
            'accidents' => $accidents
        ]);
    }
}
