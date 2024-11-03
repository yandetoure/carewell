<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'description',
        'price',
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

        // Déclaration de la relation avec MedicalFilePrescription
        public function prescriptions()
        {
            return $this->hasMany(MedicalFilePrescription::class);
        }
        
}
