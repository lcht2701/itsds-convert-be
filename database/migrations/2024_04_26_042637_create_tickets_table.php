<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users');
            $table->string('title');
            $table->string('description');
            $table->foreignId('service_id')->constrained('services');
            $table->integer('ticketStatus')->default(0);
            $table->integer('priority');
            $table->dateTime('completed_time');
            $table->integer('impact');
            $table->string('impact_detail');
            $table->integer('type');
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
