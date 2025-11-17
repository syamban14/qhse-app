<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentList extends Component
{
    use WithPagination;

    public function render()
    {
        $documents = Document::with('uploadedBy')->latest()->paginate(10);

        return view('livewire.document-list', [
            'documents' => $documents,
        ]);
    }

    public function delete(Document $document)
    {
        abort_if(!auth()->user()->can('delete document'), 403);
        $document->delete();
    }
}