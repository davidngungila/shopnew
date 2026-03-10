<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunicationConfig;

class CommunicationConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Email Configuration
        CommunicationConfig::create([
            'name' => 'Primary Email Gateway',
            'type' => 'email',
            'description' => 'Primary email gateway provider configured from system settings',
            'is_active' => true,
            'is_primary' => true,
            'config' => [
                'mail_mailer' => 'smtp',
                'mail_host' => 'smtp.gmail.com',
                'mail_port' => '587',
                'mail_username' => 'noreply@shopsmart.com',
                'mail_password' => 'encrypted_password_here',
                'mail_encryption' => 'tls',
                'mail_from_address' => 'noreply@shopsmart.com',
                'mail_from_name' => 'ShopSmart'
            ]
        ]);

        // Create SMS Configuration
        CommunicationConfig::create([
            'name' => 'Primary SMS Gateway',
            'type' => 'sms',
            'description' => 'Primary SMS gateway provider configured from system settings',
            'is_active' => true,
            'is_primary' => true,
            'config' => [
                'provider' => 'twilio',
                'from_number' => 'ShopSmart',
                'api_url' => 'https://messaging-service.co.tz/link/sms/v1/text/single',
                'username' => 'f9a89f439206e27169ead766463ca92c',
                'password' => 'encrypted_password_here',
                'api_version' => 'v1'
            ]
        ]);

        $this->command->info('Communication configurations seeded successfully.');
    }
}
