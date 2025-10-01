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
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('forme')->nullable(); // Comprimé, sirop, injection, etc.
            $table->string('dosage')->nullable(); // 500mg, 10ml, etc.
            $table->text('description')->nullable();
            $table->string('laboratoire')->nullable();
            $table->decimal('prix', 10, 2)->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('nom');
            $table->index('disponible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};