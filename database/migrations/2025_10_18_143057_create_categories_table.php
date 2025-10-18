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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la catégorie (ex: "Santé générale")
            $table->string('slug')->unique(); // Slug unique (ex: "general")
            $table->text('description')->nullable(); // Description de la catégorie
            $table->string('icon')->default('fas fa-heartbeat'); // Icône FontAwesome
            $table->string('color')->default('primary'); // Couleur Bootstrap
            $table->boolean('is_active')->default(true); // Statut actif/inactif
            $table->integer('sort_order')->default(0); // Ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
