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
        Schema::create('ordonnance_medicament', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordonnance_id');
            $table->unsignedBigInteger('medicament_id');
            $table->integer('quantite')->default(1);
            $table->string('posologie')->nullable(); // 2 fois par jour, matin et soir, etc.
            $table->integer('duree_jours')->nullable(); // Durée du traitement en jours
            $table->text('instructions_speciales')->nullable();
            $table->timestamps();
            
            // Clés étrangères
            $table->foreign('ordonnance_id')->references('id')->on('ordonnances')->onDelete('cascade');
            $table->foreign('medicament_id')->references('id')->on('medicaments')->onDelete('cascade');
            
            // Index pour optimiser les recherches
            $table->index('ordonnance_id');
            $table->index('medicament_id');
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['ordonnance_id', 'medicament_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnance_medicament');
    }
};