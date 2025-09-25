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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->string('group')->nullable()->after('display_name');
            $table->text('description')->nullable()->after('group');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'group', 'description']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description']);
        });
    }
};