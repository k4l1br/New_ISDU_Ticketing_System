<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_responsible', function (Blueprint $table) {
            $table->id();
            $table->string('email', 45);
            $table->string('password', 45);
            $table->string('roles', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_responsible');
    }
};
