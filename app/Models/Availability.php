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

    // Générer des récursions hebdomadaires
    public function generateWeeklyRecurrences($startDate, $endDate)
    {
        $recurrences = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            if ($currentDate->format('l') == $this->day_of_week) {
                $recurrences[] = [
                    'doctor_id' => $this->doctor_id,
                    'service_id' => $this->service_id,
                    'day_of_week' => $this->day_of_week,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'appointment_duration' => $this->appointment_duration,
                    'date' => $currentDate->toDateString(),
                ];
            }
            $currentDate->addWeek();
        }

        return $recurrences;
    }
    
    // Récupérer les disponibilités d'un médecin
    public function getDoctorAvailabilities($doctorId)
    {
        return $this->where('doctor_id', $doctorId)->get();
    }
}
