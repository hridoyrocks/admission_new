<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name', 'account_number', 'instructions', 
        'is_active', 'method_details'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'method_details' => 'array',
        ];
    }
}