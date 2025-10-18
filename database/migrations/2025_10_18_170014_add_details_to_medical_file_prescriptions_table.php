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
        Schema::table('medical_file_prescriptions', function (Blueprint $table) {
            $table->string('dosage')->nullable()->after('is_done');
            $table->string('duration')->nullable()->after('dosage');
            $table->text('instructions')->nullable()->after('duration');
            $table->integer('quantity')->nullable()->after('instructions');
            $table->string('frequency')->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_file_prescriptions', function (Blueprint $table) {
            $table->dropColumn(['dosage', 'duration', 'instructions', 'quantity', 'frequency']);
        });
    }
};
