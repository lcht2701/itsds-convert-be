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
        Schema::create('ticket_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets');
            $table->string('title');
            $table->string('description');
            $table->string('note')->nullable();
            $table->integer('priority');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('progress');
            $table->integer('task_status')->default(0);
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
        Schema::dropIfExists('ticket_tasks');
    }
};
