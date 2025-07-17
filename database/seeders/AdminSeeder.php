<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@isdu.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );

        // Create Admin for ISDU
        User::firstOrCreate(
            ['email' => 'admin.isdu@isdu.com'],
            [
                'name' => 'ISDU Admin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'unit' => 'ISDU (INFORMATION SYSTEMS DEVELOPMENT UNIT)',
            ]
        );

        // Create Admin for NMU
        User::firstOrCreate(
            ['email' => 'admin.nmu@isdu.com'],
            [
                'name' => 'NMU Admin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'unit' => 'NMU (NETWORK MANAGEMENT UNIT)',
            ]
        );

        // Create Admin for REPAIR
        User::firstOrCreate(
            ['email' => 'admin.repair@isdu.com'],
            [
                'name' => 'Repair Admin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'unit' => 'REPAIR',
            ]
        );

        // Create Admin for MB
        User::firstOrCreate(
            ['email' => 'admin.mb@isdu.com'],
            [
                'name' => 'Management Branch Admin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'unit' => 'MB (MANAGEMENT BRANCH)',
            ]
        );
    }
}
