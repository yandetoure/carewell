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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->foreignId('medical_files_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('doctor_id'); 
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            });
        }
    
        public function down()
        {
            Schema::table('notes', function (Blueprint $table) {
                $table->dropForeign(['doctor_id']);
            });
    
            Schema::dropIfExists('notes');
        }
};
