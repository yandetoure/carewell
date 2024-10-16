<?php

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
        Schema::create('medical_file_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_files_id')->constrained()->onDelete('cascade');
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_done')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_file_prescriptions');
    }
};
