<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyTip extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
    ];
}
