<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFilePrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_files_id',
        'prescription_id',
        'is_done',
    ];

        // Déclaration de la relation avec MedicalFile
        public function medicalFile()
        {
            return $this->belongsTo(MedicalFile::class);
        }
    
}
