<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_responsible_has_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_responsible_id');
            $table->unsignedBigInteger('tickets_id');
            $table->foreign('unit_responsible_id')->references('id')->on('unit_responsible')->onDelete('cascade');
            $table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_responsible_has_tickets');
    }
};
