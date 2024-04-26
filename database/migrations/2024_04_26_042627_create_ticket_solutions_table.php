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
        Schema::create('ticket_solutions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('owner_id')->constrained('users');
            $table->datetime('review_date');
            $table->string('keyword');
            $table->foreignId('create_by_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_solutions');
    }
};
