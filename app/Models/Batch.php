<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $fillable = [
        'name', 'status', 'is_active', 'start_date'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'date',
        ];
    }

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
