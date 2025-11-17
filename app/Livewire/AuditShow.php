<?php

namespace App\Livewire;

use App\Models\Audit;
use Livewire\Component;

class AuditShow extends Component
{
    public Audit $audit;
    public $auditItems = [];
    public $auditStatus;

    protected $rules = [
        'auditItems.*.result' => 'nullable|string|in:compliant,non_compliant,n_a',
        'auditItems.*.remarks' => 'nullable|string|max:500',
        'auditStatus' => 'required|string|in:scheduled,in_progress,completed,cancelled',
    ];

    public function mount(Audit $audit)
    {
        $this->audit = $audit->load('items');
        $this->auditStatus = $audit->status;

        foreach ($this->audit->items as $item) {
            $this->auditItems[$item->id] = [
                'item_question' => $item->item_question,
                'result' => $item->result,
                'remarks' => $item->remarks,
                'evidence_path' => $item->evidence_path,
            ];
        }
    }

    public function saveAuditItems()
    {
        $this->validate(['auditItems.*.result' => $this->rules['auditItems.*.result'], 'auditItems.*.remarks' => $this->rules['auditItems.*.remarks']]);

        foreach ($this->auditItems as $itemId => $data) {
            $item = $this->audit->items->find($itemId);
            if ($item) {
                $item->update([
                    'result' => $data['result'],
                    'remarks' => $data['remarks'],
                ]);
            }
        }

        session()->flash('message', 'Audit items berhasil diperbarui.');
    }

    public function updateAuditStatus()
    {
        $this->validate(['auditStatus' => $this->rules['auditStatus']]);

        $this->audit->update(['status' => $this->auditStatus]);

        session()->flash('message', 'Status audit berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.audit-show');
    }
}