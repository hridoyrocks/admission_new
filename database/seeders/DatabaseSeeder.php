<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin account only
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@ielts.com',
            'password' => Hash::make('password')
        ]);

        $this->command->info('Database seeding completed successfully!');
        $this->command->info('Default admin account created:');
        $this->command->info('Email: admin@ielts.com');
        $this->command->info('Password: password');
        $this->command->info('');
        $this->command->info('Please login to admin panel and configure:');
        $this->command->info('- Course Settings');
        $this->command->info('- Payment Methods');
        $this->command->info('- Time Conditions');
        $this->command->info('- Create a Batch with Sessions');
    }
}