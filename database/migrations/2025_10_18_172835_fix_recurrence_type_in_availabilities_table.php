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
        Schema::table('availabilities', function (Blueprint $table) {
            // Modifier la colonne recurrence_type pour corriger la faute de frappe et ajouter 'monthly'
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly'])->default('none')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // Revenir à l'ancienne définition avec la faute de frappe
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'mouthly'])->default('none')->change();
        });
    }
};