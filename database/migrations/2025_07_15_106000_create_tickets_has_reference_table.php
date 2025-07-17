<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_has_reference', function (Blueprint $table) {
            $table->unsignedBigInteger('tickets_id');
            $table->unsignedBigInteger('reference_id');
            $table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('reference_id')->references('id')->on('reference')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_has_reference');
    }
};
