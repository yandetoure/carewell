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
        Schema::table('medical_file_exams', function (Blueprint $table) {
            $table->string('type')->nullable()->after('is_done');
            $table->text('instructions')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_file_exams', function (Blueprint $table) {
            $table->dropColumn(['type', 'instructions']);
        });
    }
};
