<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 45);
            $table->string('position', 45);
            $table->string('designation', 45);
            $table->string('contact_number', 45);
            $table->string('email_address', 45);
            $table->string('req_office', 45)->nullable();
            $table->string('reference', 45)->nullable();
            $table->string('authority', 45)->nullable();
            $table->string('status', 45)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
