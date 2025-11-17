<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'auditor_id',
        'schedule_date',
        'status',
        'audit_template_id',
    ];

    protected $casts = [
        'schedule_date' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(AuditTemplate::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AuditItem::class);
    }
}