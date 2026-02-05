<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@shopsmart.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@shopsmart.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+255 123 456 789',
                'address' => 'Dar es Salaam, Tanzania',
                'language' => 'en',
                'timezone' => 'Africa/Dar_es_Salaam',
                'date_format' => 'Y-m-d',
                'notifications_email' => true,
                'notifications_sms' => false,
                'theme' => 'light',
            ]
        );

        // Create Manager User
        User::updateOrCreate(
            ['email' => 'manager@shopsmart.com'],
            [
                'name' => 'Manager User',
                'email' => 'manager@shopsmart.com',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'phone' => '+255 123 456 790',
                'address' => 'Dar es Salaam, Tanzania',
                'language' => 'en',
                'timezone' => 'Africa/Dar_es_Salaam',
                'date_format' => 'Y-m-d',
                'notifications_email' => true,
                'notifications_sms' => false,
                'theme' => 'light',
            ]
        );

        // Create Sales User (using cashier role)
        User::updateOrCreate(
            ['email' => 'sales@shopsmart.com'],
            [
                'name' => 'Sales User',
                'email' => 'sales@shopsmart.com',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
                'phone' => '+255 123 456 791',
                'address' => 'Dar es Salaam, Tanzania',
                'language' => 'en',
                'timezone' => 'Africa/Dar_es_Salaam',
                'date_format' => 'Y-m-d',
                'notifications_email' => true,
                'notifications_sms' => false,
                'theme' => 'light',
            ]
        );

        $this->command->info('Users seeded successfully!');
        $this->command->info('Default login credentials:');
        $this->command->info('Admin: admin@shopsmart.com / password123');
        $this->command->info('Manager: manager@shopsmart.com / password123');
        $this->command->info('Sales: sales@shopsmart.com / password123');
    }
}
