<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSetting extends Model
{
    protected $fillable = [
        'duration', 'classes', 'fee', 'materials', 
        'mock_tests', 'additional_info', 'youtube_link', 'contact_number'
    ];

    protected function casts(): array
    {
        return [
            'additional_info' => 'array',
        ];
    }
}
