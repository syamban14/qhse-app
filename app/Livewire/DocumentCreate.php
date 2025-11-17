<?php

namespace App\Livewire;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentCreate extends Component
{
    use WithFileUploads;

    public Document $document;
    public $file;

    protected $rules = [
        'document.title' => 'required|string|max:255',
        'document.document_type' => 'required|string|max:255',
        'document.version' => 'required|string|max:50',
        'document.expiry_date' => 'nullable|date|after_or_equal:today',
        'file' => 'required|file|max:10240', // 10MB Max
    ];

    public function mount()
    {
        $this->document = new Document();
        $this->document->expiry_date = null;
    }

    public function save()
    {
        $this->validate();

        $this->document->uploaded_by = Auth::id();
        $this->document->file_path = $this->file->store('documents', 'private');
        $this->document->save();

        return redirect()->route('documents.index');
    }

    public function render()
    {
        return view('livewire.document-create');
    }
}