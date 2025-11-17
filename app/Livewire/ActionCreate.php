<?php

namespace App\Livewire;

use App\Models\Action;
use App\Models\Incident;
use App\Models\User;
use Livewire\Component;

class ActionCreate extends Component
{
    public $incident_id;
    public $asset_or_location_id;
    public $description;
    public $pic_user_id;
    public $due_date;

    protected $rules = [
        'incident_id' => 'nullable|exists:incidents,id',
        'asset_or_location_id' => 'nullable|string|max:255',
        'description' => 'required|string',
        'pic_user_id' => 'required|exists:users,id',
        'due_date' => 'required|date',
    ];

    public function mount()
    {
        abort_if(!auth()->user()->can('manage actions'), 403);
        $this->due_date = now()->format('Y-m-d');
    }

    public function save()
    {
        abort_if(!auth()->user()->can('manage actions'), 403);
        $this->validate();

        if ($this->incident_id) {
            $existingAction = Action::where('incident_id', $this->incident_id)->first();

            if ($existingAction) {
                $picName = $existingAction->pic->name;
                session()->flash('error', "Insiden ini sudah ditugaskan sebagai tindakan kepada {$picName}.");
                return redirect()->route('actions.edit', $existingAction);
            }
        }

        Action::create([
            'incident_id' => $this->incident_id,
            'asset_or_location_id' => $this->asset_or_location_id,
            'description' => $this->description,
            'pic_user_id' => $this->pic_user_id,
            'due_date' => $this->due_date,
            'status' => 'open',
        ]);

        return redirect()->route('actions.index');
    }

    public function render()
    {
        return view('livewire.action-create', [
            'incidents' => Incident::all(),
            'users' => User::all(),
        ]);
    }
}