<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('fullName');
            $table->string('position');
            $table->string('designation');
            $table->string('contactNumber');
            $table->string('emailAddress');
            $table->string('reqOffice');
            $table->string('reference');
            $table->string('authority');
            $table->string('status');
            $table->string('unitResponsible');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
