<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns exist and add them if they don't
        if (!Schema::hasColumn('offices', 'username')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('offices', 'password')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->string('password')->nullable()->after('username');
            });
        }

        if (!Schema::hasColumn('offices', 'email_verified_at')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('offices', 'remember_token')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->rememberToken()->after('email_verified_at');
            });
        }

        // Update existing offices with default usernames and passwords
        $offices = DB::table('offices')->whereNull('username')->get();
        foreach ($offices as $office) {
            $username = strtolower(str_replace(' ', '_', $office->name));
            $password = Hash::make('password123'); // Default password
            DB::table('offices')
                ->where('id', $office->id)
                ->update([
                    'username' => $username,
                    'password' => $password
                ]);
        }

        // Make username unique and required
        if (Schema::hasColumn('offices', 'username')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->string('username')->nullable(false)->unique()->change();
                $table->string('password')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            if (Schema::hasColumn('offices', 'username')) {
                $table->dropUnique(['username']);
                $table->dropColumn(['username', 'password', 'email_verified_at', 'remember_token']);
            }
        });
    }
};
