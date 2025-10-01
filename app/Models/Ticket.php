<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'prescription_id',
        'doctor_id',
        'exam_id',
        'is_paid',
        'user_id'
    ];

        public function appointment()
        {
            return $this->belongsTo(Appointment::class);
        }
    
        public function prescription()
        {
            return $this->belongsTo(Prescription::class);
        }
    
        public function exam()
        {
            return $this->belongsTo(Exam::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }
        
        public function doctor()
        {
            return $this->belongsTo(User::class, 'doctor_id');
        }
}
