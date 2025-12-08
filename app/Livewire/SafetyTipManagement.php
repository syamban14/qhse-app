<?php

namespace App\Livewire;

use App\Models\SafetyTip;
use Livewire\Component;
use Livewire\WithPagination;

class SafetyTipManagement extends Component
{
    use WithPagination;

    public $title, $content, $is_active = true;
    public $tip_id;
    public $isOpen = false;

    protected $rules = [
        'content' => 'required|string|min:10',
        'title' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.safety-tip-management', [
            'tips' => SafetyTip::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->content = '';
        $this->is_active = true;
        $this->tip_id = null;
    }

    public function store()
    {
        $this->validate();

        SafetyTip::updateOrCreate(['id' => $this->tip_id], [
            'title' => $this->title,
            'content' => $this->content,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message',
            $this->tip_id ? 'Safety Tip Updated Successfully.' : 'Safety Tip Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $tip = SafetyTip::findOrFail($id);
        $this->tip_id = $id;
        $this->title = $tip->title;
        $this->content = $tip->content;
        $this->is_active = $tip->is_active;

        $this->openModal();
    }

    public function delete($id)
    {
        SafetyTip::find($id)->delete();
        session()->flash('message', 'Safety Tip Deleted Successfully.');
    }
}