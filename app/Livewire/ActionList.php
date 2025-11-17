<?php

namespace App\Livewire;

use App\Models\Action;
use Livewire\Component;
use Livewire\WithPagination;

class ActionList extends Component
{
    use WithPagination;

    public function render()
    {
        $actions = Action::with('incident', 'pic')
            ->latest()
            ->paginate(10);

        return view('livewire.action-list', [
            'actions' => $actions,
        ]);
    }

    public function delete(Action $action)
    {
        abort_if(!auth()->user()->can('manage actions'), 403);
        $action->delete();
    }
}