<?php

namespace App\Livewire;

use App\Models\Audit;
use Livewire\Component;
use Livewire\WithPagination;

class AuditList extends Component
{
    use WithPagination;

    public function render()
    {
        $audits = Audit::with('auditor')->latest()->paginate(10);

        return view('livewire.audit-list', [
            'audits' => $audits,
        ]);
    }

    public function delete(Audit $audit)
    {
        abort_if(!auth()->user()->can('delete audit'), 403);
        $audit->delete();
    }
}