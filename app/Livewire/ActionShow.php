<?php

namespace App\Livewire;

use App\Models\Action;
use Livewire\Component;

class ActionShow extends Component
{
    public Action $action;

    public function mount(Action $action)
    {
        $this->action = $action->load('pic', 'incident');
    }

    public function render()
    {
        return view('livewire.action-show');
    }
}