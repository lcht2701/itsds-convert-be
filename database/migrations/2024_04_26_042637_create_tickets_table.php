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
            $table->foreignId('requester_id')->constrained('users');
            $table->string('title');
            $table->string('description')->nullable();
            $table->foreignId('service_id')->constrained('services');
            $table->integer('ticketStatus')->default(0);
            $table->integer('priority')->nullable();
            $table->dateTime('completed_time')->nullable();
            $table->integer('impact')->nullable();
            $table->string('impact_detail')->nullable();
            $table->integer('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
