<?php

namespace App\Livewire;

use App\Models\Audit;
use App\Models\User;
use Livewire\Component;

class AuditEdit extends Component
{
    public Audit $audit;
    public $title;
    public $auditor_id;
    public $schedule_date;
    public $status;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'auditor_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
        ];
    }

    public function mount(Audit $audit)
    {
        $this->audit = $audit;
        $this->title = $audit->title;
        $this->auditor_id = $audit->auditor_id;
        $this->schedule_date = $audit->schedule_date->format('Y-m-d');
        $this->status = $audit->status;
    }

    public function save()
    {
        $this->validate();

        $this->audit->update([
            'title' => $this->title,
            'auditor_id' => $this->auditor_id,
            'schedule_date' => $this->schedule_date,
            'status' => $this->status,
        ]);

        return redirect()->route('audits.show', $this->audit);
    }

    public function render()
    {
        return view('livewire.audit-edit', [
            'users' => User::all(),
        ]);
    }
}