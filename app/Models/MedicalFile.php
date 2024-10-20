<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalFile extends Model
{


    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identification_number',
        'user_id'
    ];

    /**
     * Boot function to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate identification_number when creating a new user
        static::creating(function ($user) {
            $user->identification_number = self::generateUniqueIdentificationNumber();
        });
    }

    /**
     * Generate a unique identification number.
     *
     * @return string
     */
    private static function generateUniqueIdentificationNumber()
    {
        $identification_number = Str::random(10); // Génère une chaîne aléatoire de 10 caractères

        // Vérifie que l'identification_number est unique
        while (self::where('identification_number', $identification_number)->exists()) {
            $identification_number = Str::random(10);
        }

        return $identification_number;
    }
    // Définir la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

        // Déclaration de la relation avec MedicalFilePrescription
        public function medicalprescription()
        {
            return $this->hasMany(MedicalFilePrescription::class, 'medical_files_id');
        }
        // Déclaration de la relation avec Examen
         public function medicalexam()
                {
          return $this->hasMany(Exam::class, 'medical_files_id');
        }

        // Déclaration de la relation avec MedicalHistory
        public function medicalHistories()
        {
            return $this->hasMany(MedicalHistory::class,  'medical_files_id');
        }

        public function note()
        {
            return $this->hasMany(Note::class, 'medical_files_id');
        }        
}
