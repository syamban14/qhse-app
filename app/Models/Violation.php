<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Violation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'violator_id',
        'violator_type',
        'location',
        'violation_date',
        'description',
        'rule_broken',
    ];

    /**
     * Get the parent violator model (can be a User or a Driver).
     */
    public function violator(): MorphTo
    {
        return $this->morphTo();
    }
}
