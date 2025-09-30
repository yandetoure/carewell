<?php declare(strict_types=1); 

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
        'duration',
        'category',
        'requirements',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

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
