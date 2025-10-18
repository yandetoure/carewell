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
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_file_id')->constrained('medical_files')->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained('users')->onDelete('cascade');
            $table->decimal('blood_pressure_systolic', 5, 2)->nullable(); // Pression systolique
            $table->decimal('blood_pressure_diastolic', 5, 2)->nullable(); // Pression diastolique
            $table->integer('heart_rate')->nullable(); // Fréquence cardiaque (BPM)
            $table->decimal('temperature', 4, 2)->nullable(); // Température (°C)
            $table->integer('oxygen_saturation')->nullable(); // Saturation en oxygène (%)
            $table->integer('respiratory_rate')->nullable(); // Fréquence respiratoire
            $table->decimal('weight', 6, 2)->nullable(); // Poids (kg)
            $table->decimal('height', 6, 2)->nullable(); // Taille (cm)
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamp('recorded_at')->useCurrent(); // Heure d'enregistrement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
