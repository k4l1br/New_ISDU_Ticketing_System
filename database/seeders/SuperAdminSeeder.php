<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main Super Admin account
        User::firstOrCreate(
            ['email' => 'superadmin@ceissafp.gov.ph'],
            [
                'name' => 'System Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('SuperAdmin@2025'),
                'role' => User::ROLE_SUPER_ADMIN,
                'unit' => 'CEISSAFP',
                'phone' => null,
                'email_verified_at' => now(),
            ]
        );

        // Create a backup Super Admin account
        User::firstOrCreate(
            ['email' => 'admin@ceissafp.gov.ph'],
            [
                'name' => 'CEISSAFP Administrator',
                'username' => 'ceissafp_admin',
                'password' => Hash::make('CeissafpAdmin@2025'),
                'role' => User::ROLE_SUPER_ADMIN,
                'unit' => 'CEISSAFP',
                'phone' => null,
                'email_verified_at' => now(),
            ]
        );

        // Create a development Super Admin account (for testing)
        User::firstOrCreate(
            ['email' => 'dev@admin.local'],
            [
                'name' => 'Development Admin',
                'username' => 'devadmin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
                'unit' => 'DEVELOPMENT',
                'phone' => null,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super Admin accounts created successfully!');
        $this->command->line('');
        $this->command->line('Super Admin Accounts:');
        $this->command->line('1. Email: superadmin@ceissafp.gov.ph | Password: SuperAdmin@2025');
        $this->command->line('2. Email: admin@ceissafp.gov.ph | Password: CeissafpAdmin@2025');
        $this->command->line('3. Email: dev@admin.local | Password: password123 (Development only)');
        $this->command->line('');
    }
}
