<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class DocumentShow extends Component
{
    public Document $document;

    public function mount(Document $document)
    {
        $this->document = $document;
    }

    public function render()
    {
        return view('livewire.document-show');
    }

    public function download()
    {
        return Storage::download($this->document->file_path);
    }
}