<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 
        'medical_files_id',
        'doctor_id',
    ];

            // Relation avec le modèle MedicalFile
    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }
}
