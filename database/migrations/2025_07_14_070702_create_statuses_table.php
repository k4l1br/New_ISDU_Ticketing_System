<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Seed default values
        DB::table('statuses')->insert([
            ['name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'No Action', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Complete', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
