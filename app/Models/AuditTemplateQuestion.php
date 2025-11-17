<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTemplateQuestion extends Model
{
    protected $fillable = ['audit_template_id', 'question_text'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(AuditTemplate::class);
    }
}
