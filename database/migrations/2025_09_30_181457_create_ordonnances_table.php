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
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ordonnance')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('medecin_id');
            $table->string('patient_first_name');
            $table->string('patient_last_name');
            $table->string('medecin_first_name');
            $table->string('medecin_last_name');
            $table->text('instructions')->nullable();
            $table->date('date_prescription');
            $table->date('date_validite')->nullable();
            $table->enum('statut', ['active', 'expiree', 'annulee'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Clés étrangères
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('medecin_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index pour optimiser les recherches
            $table->index('numero_ordonnance');
            $table->index('patient_id');
            $table->index('medecin_id');
            $table->index('date_prescription');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnances');
    }
};