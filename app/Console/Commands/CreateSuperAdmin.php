<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-super 
                            {--name= : The name of the super admin}
                            {--email= : The email of the super admin}
                            {--username= : The username of the super admin}
                            {--password= : The password of the super admin}
                            {--unit= : The unit of the super admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super admin user for the CEISSAFP Ticketing System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 CEISSAFP Ticketing System - Super Admin Creator');
        $this->line('==============================================');

        // Get user input
        $name = $this->option('name') ?: $this->ask('Enter the super admin name', 'System Administrator');
        $email = $this->option('email') ?: $this->ask('Enter the super admin email', 'superadmin@ceissafp.gov.ph');
        $username = $this->option('username') ?: $this->ask('Enter the username', 'superadmin');
        $password = $this->option('password') ?: $this->secret('Enter the password (leave empty for auto-generated)');
        $unit = $this->option('unit') ?: $this->ask('Enter the unit', 'CEISSAFP');

        // Generate password if not provided
        if (empty($password)) {
            $password = 'SuperAdmin@' . date('Y');
            $this->warn("Auto-generated password: {$password}");
        }

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ], [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|min:3|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $this->error('❌ Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("   • {$error}");
            }
            return Command::FAILURE;
        }

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            if (!$this->confirm("User with email {$email} already exists. Update existing user?")) {
                return Command::FAILURE;
            }
            
            $user = User::where('email', $email)->first();
            $user->update([
                'name' => $name,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => User::ROLE_SUPER_ADMIN,
                'unit' => $unit,
                'email_verified_at' => now(),
            ]);
            
            $this->info("✅ Super admin user updated successfully!");
        } else {
            // Create new user
            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => User::ROLE_SUPER_ADMIN,
                'unit' => $unit,
                'email_verified_at' => now(),
            ]);
            
            $this->info("✅ Super admin user created successfully!");
        }

        // Display user details
        $this->line('');
        $this->info('📋 Super Admin Details:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $user->name],
                ['Email', $user->email],
                ['Username', $user->username],
                ['Role', $user->role],
                ['Unit', $user->unit],
                ['Password', $password],
                ['Created', $user->created_at->format('Y-m-d H:i:s')],
            ]
        );

        $this->line('');
        $this->warn('⚠️  Please save these credentials securely!');
        $this->info('🎉 You can now login to the system with these credentials.');

        return Command::SUCCESS;
    }
}
