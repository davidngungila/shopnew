<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsProvider;

class SmsProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create primary SMS provider with given configuration
        SmsProvider::create([
            'name' => 'Primary SMS Gateway',
            'description' => 'Primary SMS gateway provider configured from system settings',
            'username' => 'f9a89f439206e27169ead766463ca92c', // Bearer token stored in username field
            'password' => '', // Hidden password
            'from' => 'ShopSmart', // Changed from FEEDTAN to ShopSmart
            'api_url' => 'https://messaging-service.co.tz/link/sms/v1/text/single',
            'active' => true,
            'is_primary' => true,
            'config' => [
                'bearer_token_length' => 32,
                'stored_in' => 'username_field',
                'api_version' => 'v1',
                'service_type' => 'link/sms'
            ]
        ]);

        $this->command->info('Primary SMS Gateway provider seeded successfully.');
    }
}
