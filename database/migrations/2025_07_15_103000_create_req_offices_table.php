<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('req_offices', function (Blueprint $table) {
            $table->id();
            $table->string('reqOffice', 45); // Changed from req_office
            $table->timestamps();
        });
    }

    public function down(): void
    {
          Schema::dropIfExists('req_offices');
    }
};
