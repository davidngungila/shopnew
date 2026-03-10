<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\CommunicationConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function emailCreate()
    {
        // Load email provider configuration for creation
        $emailProvider = [
            'name' => 'Primary Email Gateway',
            'description' => 'Primary email gateway provider configured from system settings',
            'status' => 'active',
            'is_primary' => true,
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '587',
            'smtp_username' => 'noreply@shopsmart.com',
            'smtp_password' => '',
            'smtp_encryption' => 'tls',
            'from_email' => 'noreply@shopsmart.com',
            'from_name' => 'ShopSmart'
        ];
        
        return view('settings.communication.email-create', compact('emailProvider'));
    }

    public function testMessage()
    {
        return view('settings.communication.test-message');
    }

    public function communicationIndex()
    {
        // Get all communication configurations
        $emailConfigs = CommunicationConfig::where('type', 'email')->get();
        $smsConfigs = CommunicationConfig::where('type', 'sms')->get();
        
        return view('settings.communication.index', compact('emailConfigs', 'smsConfigs'));
    }

    public function general()
    {
        $settings = Setting::getGroup('general');
        return view('settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string',
            'company_logo' => 'nullable|image|max:2048',
            'tax_id' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'company_logo' && $request->hasFile('company_logo')) {
                $path = $request->file('company_logo')->store('settings', 'public');
                Setting::set($key, $path, 'general', 'file');
            } elseif ($key !== 'company_logo') {
                Setting::set($key, $value, 'general');
            }
        }

        return back()->with('success', 'General settings updated successfully.');
    }

    public function users()
    {
        $users = User::with('employee')->latest()->paginate(20);
        return view('settings.users', compact('users'));
    }

    public function roles()
    {
        return view('settings.roles');
    }

    public function system()
    {
        $settings = Setting::getGroup('system');
        return view('settings.system', compact('settings'));
    }

    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'enable_pos' => 'boolean',
            'enable_quotations' => 'boolean',
            'enable_purchases' => 'boolean',
            'enable_notifications' => 'boolean',
            'enable_sms' => 'boolean',
            'enable_email' => 'boolean',
            'auto_backup' => 'boolean',
            'backup_frequency' => 'nullable|in:daily,weekly,monthly',
            'theme' => 'nullable|in:light,dark',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ? '1' : '0', 'system', 'boolean');
        }

        return back()->with('success', 'System settings updated successfully.');
    }

    public function financial()
    {
        $settings = Setting::getGroup('financial');
        return view('settings.financial', compact('settings'));
    }

    public function updateFinancial(Request $request)
    {
        $validated = $request->validate([
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
            'default_discount_type' => 'nullable|in:percentage,fixed',
            'enable_payment_cash' => 'boolean',
            'enable_payment_card' => 'boolean',
            'enable_payment_mobile_money' => 'boolean',
            'enable_payment_bank' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value, 'financial', is_bool($value) ? 'boolean' : 'text');
        }

        return back()->with('success', 'Financial settings updated successfully.');
    }

    public function inventory()
    {
        $settings = Setting::getGroup('inventory');
        return view('settings.inventory', compact('settings'));
    }

    public function updateInventory(Request $request)
    {
        $validated = $request->validate([
            'default_low_stock_alert' => 'nullable|integer|min:0',
            'default_unit' => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'inventory');
        }

        return back()->with('success', 'Inventory settings updated successfully.');
    }

    public function quotations()
    {
        $settings = Setting::getGroup('quotations');
        return view('settings.quotations', compact('settings'));
    }

    public function updateQuotations(Request $request)
    {
        $validated = $request->validate([
            'default_quotation_expiry_days' => 'nullable|integer|min:1',
            'default_terms_conditions' => 'nullable|string',
            'quotation_number_prefix' => 'nullable|string|max:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'quotations');
        }

        return back()->with('success', 'Quotation settings updated successfully.');
    }

    public function notifications()
    {
        $settings = Setting::getGroup('notifications');
        return view('settings.notifications', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'low_stock_alert' => 'boolean',
            'quotation_reminder' => 'boolean',
            'invoice_overdue_alert' => 'boolean',
            'payment_received_alert' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ? '1' : '0', 'notifications', 'boolean');
        }

        return back()->with('success', 'Notification settings updated successfully.');
    }

    public function backup()
    {
        return view('settings.backup');
    }

    public function createBackup(Request $request)
    {
        try {
            $backupType = $request->input('backup_type', 'database');
            $compression = $request->input('compression', 'gzip');
            
            $filename = 'backup_' . date('Y-m-d_His') . '_' . $backupType;
            
            if ($backupType === 'database' || $backupType === 'full') {
                // Database backup using mysqldump
                $database = config('database.connections.mysql.database');
                $username = config('database.connections.mysql.username');
                $password = config('database.connections.mysql.password');
                $host = config('database.connections.mysql.host');
                
                $backupPath = storage_path('app/backups');
                if (!File::exists($backupPath)) {
                    File::makeDirectory($backupPath, 0755, true);
                }
                
                $sqlFile = $backupPath . '/' . $filename . '.sql';
                $command = sprintf(
                    'mysqldump -h %s -u %s -p%s %s > %s',
                    escapeshellarg($host),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database),
                    escapeshellarg($sqlFile)
                );
                
                exec($command, $output, $returnVar);
                
                if ($returnVar !== 0) {
                    throw new \Exception('Database backup failed');
                }
                
                if ($compression === 'gzip') {
                    $compressedFile = $sqlFile . '.gz';
                    exec("gzip $sqlFile");
                    $filename .= '.sql.gz';
                } else {
                    $filename .= '.sql';
                }
            }
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'filename' => $filename
                ]);
            }
            
            return back()->with('success', 'Backup created successfully: ' . $filename);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup failed: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function smsProvider()
    {
        // Load SMS provider configuration from database or use default
        $smsProvider = [
            'name' => 'Primary SMS Gateway',
            'description' => 'Primary SMS gateway provider configured from system settings',
            'status' => 'active',
            'is_primary' => true,
            'bearer_token' => 'f9a89f439206e27169ead766463ca92c',
            'password' => '',
            'from' => 'FEEDTAN',
            'api_url' => 'https://messaging-service.co.tz/link/sms/v1/text/single'
        ];
        
        return view('settings.communication.sms-provider', compact('smsProvider'));
    }

    // Email Configuration - Edit
    public function emailEdit($id)
    {
        $config = CommunicationConfig::findOrFail($id);
        if ($config->type !== 'email') {
            return redirect()->route('settings.communication.index')->with('error', 'Invalid configuration type.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'mail_mailer' => 'nullable|in:smtp,sendmail,mailgun,ses,postmark,resend,log,array',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_encryption' => 'nullable|in:tls,ssl,',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
        ]);

        $configData = $config->config;
        $configData['mail_mailer'] = $validated['mail_mailer'] ?? $configData['mail_mailer'] ?? 'smtp';
        $configData['mail_from_address'] = $validated['mail_from_address'];
        $configData['mail_from_name'] = $validated['mail_from_name'] ?? $configData['mail_from_name'] ?? '';
        $configData['mail_host'] = $validated['mail_host'] ?? $configData['mail_host'] ?? '';
        $configData['mail_port'] = $validated['mail_port'] ?? $configData['mail_port'] ?? '';
        $configData['mail_encryption'] = $validated['mail_encryption'] ?? $configData['mail_encryption'] ?? 'tls';
        $configData['mail_username'] = $validated['mail_username'] ?? $configData['mail_username'] ?? '';
        
        // Only update password if provided
        if (!empty($validated['mail_password'])) {
            $configData['mail_password'] = $validated['mail_password'];
        }

        $config->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'config' => $configData,
        ]);

        return redirect()->route('settings.communication.index')->with('success', 'Email configuration updated successfully.');
    }

    // SMS Configuration - Create
    public function smsCreate()
    {
        return view('settings.communication.sms');
    }

    // SMS Configuration - Store
    public function smsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sms_provider' => 'nullable|in:twilio,nexmo,aws_sns,messagebird,plivo,custom',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_api_secret' => 'nullable|string|max:255',
            'sms_from' => 'nullable|string|max:50',
            'sms_api_url' => 'nullable|url|max:255',
            'sms_region' => 'nullable|string|max:50',
            'sms_country_code' => 'nullable|string|max:10',
        ]);

        $config = [
            'sms_provider' => $validated['sms_provider'] ?? 'twilio',
            'sms_api_key' => $validated['sms_api_key'] ?? '',
            'sms_api_secret' => $validated['sms_api_secret'] ?? '',
            'sms_from' => $validated['sms_from'] ?? '',
            'sms_api_url' => $validated['sms_api_url'] ?? '',
            'sms_region' => $validated['sms_region'] ?? '',
            'sms_country_code' => $validated['sms_country_code'] ?? '+1',
        ];

        $communicationConfig = CommunicationConfig::create([
            'name' => $validated['name'],
            'type' => 'sms',
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'is_primary' => false,
            'config' => $config,
        ]);

        return redirect()->route('settings.communication.index')->with('success', 'SMS configuration created successfully.');
    }

    // SMS Configuration - Edit
    public function smsEdit($id)
    {
        $config = CommunicationConfig::findOrFail($id);
        if ($config->type !== 'sms') {
            return redirect()->route('settings.communication.index')->with('error', 'Invalid configuration type.');
        }
        return view('settings.communication.sms', compact('config'));
    }

    // SMS Configuration - Update
    public function smsUpdate(Request $request, $id)
    {
        $config = CommunicationConfig::findOrFail($id);
        if ($config->type !== 'sms') {
            return redirect()->route('settings.communication.index')->with('error', 'Invalid configuration type.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sms_provider' => 'nullable|in:twilio,nexmo,aws_sns,messagebird,plivo,custom',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_api_secret' => 'nullable|string|max:255',
            'sms_from' => 'nullable|string|max:50',
            'sms_api_url' => 'nullable|url|max:255',
            'sms_region' => 'nullable|string|max:50',
            'sms_country_code' => 'nullable|string|max:10',
        ]);

        $configData = $config->config;
        $configData['sms_provider'] = $validated['sms_provider'] ?? $configData['sms_provider'] ?? 'twilio';
        $configData['sms_api_key'] = $validated['sms_api_key'] ?? $configData['sms_api_key'] ?? '';
        $configData['sms_from'] = $validated['sms_from'] ?? $configData['sms_from'] ?? '';
        $configData['sms_api_url'] = $validated['sms_api_url'] ?? $configData['sms_api_url'] ?? '';
        $configData['sms_region'] = $validated['sms_region'] ?? $configData['sms_region'] ?? '';
        $configData['sms_country_code'] = $validated['sms_country_code'] ?? $configData['sms_country_code'] ?? '+1';
        
        // Only update secret if provided
        if (!empty($validated['sms_api_secret'])) {
            $configData['sms_api_secret'] = $validated['sms_api_secret'];
        }
        
        $config->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'config' => $configData,
        ]);

        return redirect()->route('settings.communication.index')->with('success', 'SMS configuration updated successfully.');
    }

    // Set Primary Configuration
    public function setPrimary($id)
    {
        $config = CommunicationConfig::findOrFail($id);
        $config->setAsPrimary();
        return back()->with('success', 'Configuration set as primary successfully.');
    }

    // Delete Configuration
    public function destroy($id)
    {
        $config = CommunicationConfig::findOrFail($id);
        $config->delete();
        return back()->with('success', 'Configuration deleted successfully.');
    }

    public function testEmail(Request $request)
    {
        try {
            $testEmail = $request->input('test_email');
            $testMessage = $request->input('test_message');
            $testSubject = $request->input('test_subject');
            
            // Get configuration from form
            $config = [
                'smtp_host' => $request->input('smtp_host'),
                'smtp_port' => $request->input('smtp_port'),
                'smtp_username' => $request->input('smtp_username'),
                'smtp_password' => $request->input('smtp_password'),
                'smtp_encryption' => $request->input('smtp_encryption'),
                'from_email' => $request->input('from_email'),
                'from_name' => $request->input('from_name')
            ];
            
            // Validate required fields
            if (!$testEmail || !$testMessage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test email and message are required'
                ], 400);
            }
            
            // Configure SMTP settings dynamically for this test
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $config['smtp_host'],
                'mail.mailers.smtp.port' => $config['smtp_port'],
                'mail.mailers.smtp.encryption' => $config['smtp_encryption'],
                'mail.mailers.smtp.username' => $config['smtp_username'],
                'mail.mailers.smtp.password' => $config['smtp_password'],
                'mail.from.address' => $config['from_email'],
                'mail.from.name' => $config['from_name'],
            ]);
            
            // Send test email
            Mail::send([], [], function ($message) use ($testEmail, $testSubject, $config) {
                $message->to($testEmail)
                    ->subject($testSubject)
                    ->from($config['from_email'], $config['from_name'])
                    ->html($testMessage);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $testEmail
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testSMS(Request $request)
    {
        try {
            $phone = $request->input('phone');
            $configId = $request->input('config_id');
            
            if (!$phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number is required'
                ], 400);
            }

            // Get configuration
            if ($configId) {
                $config = CommunicationConfig::find($configId);
                if ($config && $config->type === 'sms' && $config->is_active) {
                    $configData = $config->config;
                    $provider = $configData['sms_provider'] ?? 'twilio';
                    $apiKey = $configData['sms_api_key'] ?? '';
                    $apiSecret = $configData['sms_api_secret'] ?? '';
                    $from = $configData['sms_from'] ?? '';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'SMS configuration not found or inactive'
                    ], 400);
                }
            } else {
                // Use primary configuration
                $primaryConfig = CommunicationConfig::getPrimary('sms');
                if ($primaryConfig && $primaryConfig->is_active) {
                    $configData = $primaryConfig->config;
                    $provider = $configData['sms_provider'] ?? 'twilio';
                    $apiKey = $configData['sms_api_key'] ?? '';
                    $apiSecret = $configData['sms_api_secret'] ?? '';
                    $from = $configData['sms_from'] ?? '';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No active SMS configuration found'
                    ], 400);
                }
            }

            if (empty($apiKey) || empty($apiSecret)) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS API credentials are not configured'
                ], 400);
            }

            // Here you would implement actual SMS sending logic based on provider
            // For now, we'll just log it
            Log::info('Test SMS would be sent', [
                'provider' => $provider,
                'to' => $phone,
                'from' => $from
            ]);

            // TODO: Implement actual SMS sending based on provider
            // Example for Twilio:
            // $client = new \Twilio\Rest\Client($apiKey, $apiSecret);
            // $client->messages->create($phone, ['from' => $from, 'body' => 'Test SMS from ShopSmart']);

            return response()->json([
                'success' => true,
                'message' => 'Test SMS sent successfully (logged)'
            ]);
        } catch (\Exception $e) {
            Log::error('Test SMS failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test SMS: ' . $e->getMessage()
            ], 500);
        }
    }
}
