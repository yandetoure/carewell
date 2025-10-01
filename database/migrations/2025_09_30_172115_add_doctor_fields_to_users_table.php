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
        Schema::table('users', function (Blueprint $table) {
            $table->string('specialite')->nullable()->after('biographie');
            $table->string('numero_ordre')->nullable()->after('specialite');
            $table->integer('experience_years')->nullable()->after('numero_ordre');
            $table->decimal('consultation_fee', 10, 2)->nullable()->after('experience_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['specialite', 'numero_ordre', 'experience_years', 'consultation_fee']);
        });
    }
};
