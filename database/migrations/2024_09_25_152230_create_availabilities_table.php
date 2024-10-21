<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('service_id')->constrained('services');
            $table->date('available_date'); 
            $table->enum('day_of_week', ['0', '1', '2', '3', '4', '5', '6']); 
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('appointment_duration'); 
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'mouthly'])->default('none'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
