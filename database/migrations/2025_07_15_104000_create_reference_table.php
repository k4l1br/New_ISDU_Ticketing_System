<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reference', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference');
    }
};
