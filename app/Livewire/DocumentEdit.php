<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Component;

class DocumentEdit extends Component
{
    public Document $document;

    protected $rules = [
        'document.title' => 'required|string|max:255',
        'document.document_type' => 'required|string|max:255',
        'document.version' => 'required|string|max:50',
        'document.expiry_date' => 'nullable|date|after_or_equal:today',
    ];

    public function mount(Document $document)
    {
        $this->document = $document;
    }

    public function save()
    {
        $this->validate();

        $this->document->save();

        return redirect()->route('documents.show', $this->document);
    }

    public function render()
    {
        return view('livewire.document-edit');
    }
}