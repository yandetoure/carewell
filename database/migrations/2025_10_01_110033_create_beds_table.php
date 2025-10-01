<?php declare(strict_types=1); 

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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_number')->unique(); // Numéro du lit
            $table->string('room_number'); // Numéro de la salle
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null'); // Service
            $table->enum('status', ['libre', 'occupe', 'maintenance', 'admission_impossible'])->default('libre'); // Statut du lit
            $table->enum('bed_type', ['standard', 'premium', 'vip'])->default('standard'); // Type de lit
            $table->foreignId('medical_file_id')->nullable()->constrained()->onDelete('set null'); // Dossier médical du patient (si occupé)
            $table->date('admission_date')->nullable(); // Date d'admission
            $table->date('expected_discharge_date')->nullable(); // Date de sortie prévue
            $table->date('discharge_date')->nullable(); // Date de sortie réelle
            $table->text('notes')->nullable(); // Notes supplémentaires
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('status');
            $table->index('room_number');
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
