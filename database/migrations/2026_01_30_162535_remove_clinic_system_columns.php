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
        $tables = [
            'users',
            'appointments',
            'services',
            'medicaments',
            'prescriptions',
            'beds',
            'medical_files',
            'availabilities',
            'absences',
            'articles',
            'tickets'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'clinic_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropForeign(['clinic_id']);

                    // Pour beds, on doit supprimer l'index unique composite
                    if ($tableName === 'beds') {
                        $table->dropUnique('beds_clinic_bed_number_unique');
                    }

                    $table->dropColumn('clinic_id');
                });
            }
        }

        Schema::dropIfExists('clinics');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On ne recrée pas tout le système en cas de rollback, car c'est une opération majeure
        // Mais par principe, on pourrait recréer la table et les colonnes
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        $tables = [
            'users',
            'appointments',
            'services',
            'medicaments',
            'prescriptions',
            'beds',
            'medical_files',
            'availabilities',
            'absences',
            'articles',
            'tickets'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('clinic_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }
};
