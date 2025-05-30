<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeCondition;

class TimeConditionSeeder extends Seeder
{
    public function run(): void
    {
        // Check if time conditions already exist
        if (TimeCondition::count() > 0) {
            $this->command->info('Time conditions already exist. Skipping...');
            return;
        }

        // Create time conditions for student
        TimeCondition::create([
            'profession' => 'student',
            'is_fixed' => true,
            'fixed_time' => 'Morning 8:00 AM'
        ]);

        // Create time conditions for job holder (score-based)
        TimeCondition::create([
            'profession' => 'job_holder',
            'is_fixed' => false,
            'score_rules' => [
                ['min_score' => 0, 'max_score' => 20, 'time' => 'Evening 6:00 PM'],
                ['min_score' => 21, 'max_score' => 35, 'time' => 'Evening 7:00 PM'],
                ['min_score' => 36, 'max_score' => 40, 'time' => 'Evening 8:00 PM']
            ]
        ]);

        // Create time conditions for housewife
        TimeCondition::create([
            'profession' => 'housewife',
            'is_fixed' => true,
            'fixed_time' => 'Morning 10:00 AM'
        ]);

        $this->command->info('Time conditions created successfully!');
        $this->command->info('- Student: Fixed at Morning 8:00 AM');
        $this->command->info('- Job Holder: Score-based (6PM/7PM/8PM)');
        $this->command->info('- Housewife: Fixed at Morning 10:00 AM');
    }
}