<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'prescription_id',
        'doctor_id',
        'exam_id',
        'is_paid'
    ];

        // Relation avec le modèle Appointment
        public function appointment()
        {
            return $this->belongsTo(Appointment::class);
        }
    
        // Si vous avez d'autres relations, ajoutez-les ici
        public function prescription()
        {
            return $this->belongsTo(Prescription::class);
        }
    
        public function exam()
        {
            return $this->belongsTo(Exam::class);
        }

        public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

}
