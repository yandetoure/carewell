<?php declare(strict_types=1);

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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('title'); // Titre de l'absence (ex: "Congé annuel", "Formation", "Maladie")
            $table->text('description')->nullable(); // Description détaillée
            $table->enum('type', ['congé', 'formation', 'maladie', 'personnel', 'autre'])->default('congé');
            $table->date('start_date'); // Date de début
            $table->date('end_date'); // Date de fin
            $table->time('start_time')->nullable(); // Heure de début (optionnel)
            $table->time('end_time')->nullable(); // Heure de fin (optionnel)
            $table->boolean('is_full_day')->default(true); // Absence toute la journée ou partielle
            $table->enum('status', ['planned', 'confirmed', 'cancelled'])->default('planned');
            $table->boolean('appointments_pending')->default(false); // Si des RDV sont en attente
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['doctor_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};