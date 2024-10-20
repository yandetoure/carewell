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
            $table->enum('day_of_week', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']); // Pour les disponibilités répétées par semaine
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('appointment_duration'); // Durée de chaque rendez-vous en minutes
            $table->enum('recurrence_type', ['none', 'weekly', 'monthly'])->default('none'); // Répétition par semaine ou mois
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
