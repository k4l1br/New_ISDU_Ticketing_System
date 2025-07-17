<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_has_status', function (Blueprint $table) {
            $table->unsignedBigInteger('tickets_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_has_status');
    }
};
