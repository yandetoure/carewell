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
        Schema::create('e_medical_file_exams', function (Blueprint $table) {
            $table->id();     
            $table->foreignId('medical_file_id')->constrained()->onDelete('cascade');       
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_done')->default(false); 
            $table->timestamps();
            $table->id();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_medical_file_exams');
    }
};
