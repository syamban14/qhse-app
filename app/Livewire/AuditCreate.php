<?php

namespace App\Livewire;

use App\Models\Audit;
use App\Models\AuditTemplate;
use App\Models\User;
use Livewire\Component;

class AuditCreate extends Component
{
    public $audit_template_id;
    public $auditor_id;
    public $schedule_date;

    protected function rules()
    {
        return [
            'audit_template_id' => 'required|exists:audit_templates,id',
            'auditor_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
        ];
    }

    public function mount()
    {
        $this->schedule_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $template = AuditTemplate::with('questions')->findOrFail($this->audit_template_id);

        $audit = Audit::create([
            'title' => $template->title . ' - ' . $this->schedule_date,
            'audit_template_id' => $this->audit_template_id,
            'auditor_id' => $this->auditor_id,
            'schedule_date' => $this->schedule_date,
            'status' => 'scheduled',
        ]);

        foreach ($template->questions as $question) {
            $audit->items()->create([
                'item_question' => $question->question_text,
            ]);
        }

        return redirect()->route('audits.show', $audit);
    }

    public function render()
    {
        return view('livewire.audit-create', [
            'users' => User::all(),
            'templates' => AuditTemplate::all(),
        ]);
    }
}