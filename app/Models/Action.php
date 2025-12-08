<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\UserService; // Import UserService
use App\Models\Accident;

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
        return $this->belongsTo(Accident::class, 'incident_id');
    }

    public function getPicAttribute()
    {
        // Caching sederhana agar tidak query berulang kali
        if (!array_key_exists('pic_data', $this->attributes)) {
            $this->attributes['pic_data'] = app(UserService::class)->findById($this->pic_user_id);
        }
        return $this->attributes['pic_data'];
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(CorrectiveActionReport::class, 'corrective_action_report_id');
    }
}