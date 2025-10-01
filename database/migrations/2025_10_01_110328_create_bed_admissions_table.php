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
        Schema::create('bed_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bed_id')->constrained()->onDelete('cascade');
            $table->foreignId('medical_file_id')->constrained()->onDelete('cascade');
            $table->foreignId('admitted_by')->nullable()->constrained('users')->onDelete('set null'); // Qui a admis le patient
            $table->foreignId('discharged_by')->nullable()->constrained('users')->onDelete('set null'); // Qui a fait sortir le patient
            $table->date('admission_date'); // Date d'admission
            $table->date('expected_discharge_date')->nullable(); // Date de sortie prévue
            $table->date('discharge_date')->nullable(); // Date de sortie réelle
            $table->enum('discharge_reason', ['gueri', 'transfert', 'deces', 'autre'])->nullable(); // Raison de la sortie
            $table->text('admission_notes')->nullable(); // Notes d'admission
            $table->text('discharge_notes')->nullable(); // Notes de sortie
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('bed_id');
            $table->index('medical_file_id');
            $table->index('admission_date');
            $table->index('discharge_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_admissions');
    }
};
