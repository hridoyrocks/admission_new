<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'course_type', 
        'profession', 'score', 'class_session_id'
    ];

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function application(): HasOne
    {
        return $this->hasOne(Application::class);
    }
}