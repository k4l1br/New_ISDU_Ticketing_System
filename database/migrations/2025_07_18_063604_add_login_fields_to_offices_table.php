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
        Schema::table('offices', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('password')->nullable()->after('username');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->rememberToken()->after('email_verified_at');
        });

        // Update existing offices with default usernames
        $offices = DB::table('offices')->get();
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

        // Now make username unique and required
        Schema::table('offices', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
            $table->string('password')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn(['username', 'password', 'email_verified_at', 'remember_token']);
        });
    }
};
