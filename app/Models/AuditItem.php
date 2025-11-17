<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'item_question',
        'result',
        'remarks',
        'evidence_path',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }
}