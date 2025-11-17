<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Action extends Model
{
    protected $fillable = [
        'incident_id',
        'asset_or_location_id',
        'description',
        'due_date',
        'pic_user_id',
        'status',
        'completion_notes',
        'effectiveness_verification_notes',
        'effectiveness_verification_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }
}