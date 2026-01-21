<?php declare(strict_types=1); 

namespace App\Models;

use App\Models\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, BelongsToClinic;

    protected $fillable = [
        'name',
        'photo',
        'description',
        'price',
        'duration',
        'category',
        'requirements',
        'user_id',
        'clinic_id',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

    /**
     * Get the clinic that the service belongs to
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
        
}
