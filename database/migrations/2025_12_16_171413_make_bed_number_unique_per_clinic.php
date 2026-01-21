<?php

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
        Schema::table('beds', function (Blueprint $table) {
            // Supprimer l'index unique existant sur bed_number
            $table->dropUnique(['bed_number']);
            
            // Créer un index unique composite sur clinic_id et bed_number
            // Cela permet à chaque clinique d'avoir ses propres numéros de lits uniques
            $table->unique(['clinic_id', 'bed_number'], 'beds_clinic_bed_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beds', function (Blueprint $table) {
            // Supprimer l'index unique composite
            $table->dropUnique('beds_clinic_bed_number_unique');
            
            // Restaurer l'index unique simple sur bed_number
            $table->unique('bed_number');
        });
    }
};
