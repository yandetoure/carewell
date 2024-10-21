<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'service_id',
        'day_of_week',
        'start_time',
        'end_time',
        'available_date',
        'appointment_duration',
        'recurrence_type',
    ];

    // Relation avec le médecin
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->where('role', 'doctor');
    }

    // Relation avec le service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
