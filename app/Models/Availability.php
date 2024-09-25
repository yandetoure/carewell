<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'service_id', 
        'available_date',
        'start_time',
        'end_time',
    ];

    // Relation avec le médecin
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->where('role', 'doctor'); // Filtrer pour inclure uniquement les médecins
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
}
