<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\CourseSetting;
use App\Models\TimeCondition;
use App\Models\PaymentMethod;
use App\Models\Batch;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@ielts.com',
            'password' => Hash::make('password')
        ]);

        // Create default course settings
        CourseSetting::create([
            'duration' => '2 মাস',
            'classes' => 'সপ্তাহে 3 দিন',
            'fee' => 8000,
            'materials' => 'Free PDF + Videos',
            'mock_tests' => '5টি',
            'additional_info' => [
                'Expert instructors',
                '24/7 support',
                'Certificate provided',
                'Job placement assistance'
            ],
            'youtube_link' => 'https://youtube.com/@ielts',
            'contact_number' => '01712345678'
        ]);

        // Create time conditions
        TimeCondition::create([
            'profession' => 'student',
            'is_fixed' => true,
            'fixed_time' => 'Morning 8:00 AM'
        ]);

        TimeCondition::create([
            'profession' => 'job_holder',
            'is_fixed' => false,
            'score_rules' => [
                ['min_score' => 0, 'max_score' => 20, 'time' => 'Evening 6:00 PM'],
                ['min_score' => 21, 'max_score' => 35, 'time' => 'Evening 7:00 PM'],
                ['min_score' => 36, 'max_score' => 40, 'time' => 'Evening 8:00 PM']
            ]
        ]);

        TimeCondition::create([
            'profession' => 'housewife',
            'is_fixed' => true,
            'fixed_time' => 'Morning 10:00 AM'
        ]);

        // Create payment methods
        PaymentMethod::create([
            'name' => 'bKash',
            'account_number' => '01712345678',
            'instructions' => 'Send Money করুন এবং আপনার নাম Reference এ লিখুন'
        ]);

        PaymentMethod::create([
            'name' => 'Nagad',
            'account_number' => '01712345679',
            'instructions' => 'Send Money করুন এবং আপনার ফোন নম্বর Reference এ লিখুন'
        ]);

        PaymentMethod::create([
            'name' => 'Bank Transfer',
            'account_number' => 'Dutch Bangla Bank - 123456789',
            'instructions' => 'Deposit করুন এবং SMS করুন'
        ]);

        // Create a default batch (optional)
        $batch = Batch::create([
            'name' => 'January 2025',
            'status' => 'open',
            'is_active' => true,
            'start_date' => now()->addDays(7)
        ]);

        // Create default sessions for the batch
        $defaultSessions = [
            ['session_name' => 'Morning Session', 'time' => '8:00 AM', 'days' => 'Sunday, Tuesday, Thursday'],
            ['session_name' => 'Afternoon Session', 'time' => '2:00 PM', 'days' => 'Sunday, Tuesday, Thursday'],
            ['session_name' => 'Evening Session', 'time' => '7:00 PM', 'days' => 'Sunday, Tuesday, Thursday'],
            ['session_name' => 'Weekend Session', 'time' => '10:00 AM', 'days' => 'Friday, Saturday']
        ];

        foreach ($defaultSessions as $sessionData) {
            $batch->classSessions()->create($sessionData);
        }

        $this->command->info('Database seeding completed successfully!');
    }
}