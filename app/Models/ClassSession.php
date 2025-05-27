<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSession extends Model
{
    protected $table = 'class_sessions';
    
    protected $fillable = [
        'batch_id', 'session_name', 'time', 'days', 'current_count'
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_session_id');
    }
}
