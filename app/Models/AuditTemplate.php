<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuditTemplate extends Model
{
    protected $fillable = ['title', 'description'];

    public function questions(): HasMany
    {
        return $this->hasMany(AuditTemplateQuestion::class);
    }
}
