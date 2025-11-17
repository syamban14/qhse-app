<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    protected $fillable = [
        'reporter_id',
        'incident_type',
        'location',
        'description',
        'category',
        'location_details',
        'latitude',
        'longitude',
        'incident_time',
        'status',
    ];

    protected $casts = [
        'incident_time' => 'datetime',
    ];

    /**
     * Get the user that reported the incident.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class);
    }
}