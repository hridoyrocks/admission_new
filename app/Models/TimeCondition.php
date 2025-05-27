<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeCondition extends Model
{
    protected $fillable = [
        'profession', 'is_fixed', 'fixed_time', 'score_rules'
    ];

    protected function casts(): array
    {
        return [
            'is_fixed' => 'boolean',
            'score_rules' => 'array',
        ];
    }
}
