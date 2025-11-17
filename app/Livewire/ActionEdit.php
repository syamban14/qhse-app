<?php

namespace App\Livewire;

use App\Models\Action;
use App\Models\Incident;
use App\Models\User;
use Livewire\Component;

class ActionEdit extends Component
{
    public Action $action;
    public $incident_id;
    public $asset_or_location_id;
    public $description;
    public $pic_user_id;
    public $due_date;
    public $status;
    public $completion_notes;
    public $effectiveness_verification_notes;
    public $effectiveness_verification_date;

    protected function rules()
    {
        return [
            'incident_id' => 'nullable|exists:incidents,id',
            'asset_or_location_id' => 'nullable|string|max:255',
            'description' => 'required|string',
            'pic_user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'required|in:open,in_progress,completed,pending_verification,closed_effective,closed_ineffective,cancelled',
            'completion_notes' => 'nullable|string',
            'effectiveness_verification_notes' => 'nullable|string',
            'effectiveness_verification_date' => 'nullable|date',
        ];
    }

    public function mount(Action $action)
    {
        abort_if(!auth()->user()->can('manage actions'), 403);
        $this->action = $action;
        $this->incident_id = $action->incident_id;
        $this->asset_or_location_id = $action->asset_or_location_id;
        $this->description = $action->description;
        $this->pic_user_id = $action->pic_user_id;
        $this->due_date = $action->due_date->format('Y-m-d');
        $this->status = $action->status;
        $this->completion_notes = $action->completion_notes;
        $this->effectiveness_verification_notes = $action->effectiveness_verification_notes;
        $this->effectiveness_verification_date = $action->effectiveness_verification_date ? $action->effectiveness_verification_date->format('Y-m-d') : null;
    }

    public function save()
    {
        abort_if(!auth()->user()->can('manage actions'), 403);
        $this->validate();

        $data = [
            'incident_id' => $this->incident_id,
            'asset_or_location_id' => $this->asset_or_location_id,
            'description' => $this->description,
            'pic_user_id' => $this->pic_user_id,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'completion_notes' => $this->completion_notes,
            'effectiveness_verification_notes' => $this->effectiveness_verification_notes,
        ];

        // Automatically set verification date if status is moved to a closed state
        if (in_array($this->status, ['closed_effective', 'closed_ineffective']) && is_null($this->action->effectiveness_verification_date)) {
            $data['effectiveness_verification_date'] = now();
        }

        $this->action->update($data);

        return redirect()->route('actions.show', $this->action);
    }

    public function render()
    {
        return view('livewire.action-edit', [
            'incidents' => Incident::all(),
            'users' => User::all(),
        ]);
    }
}