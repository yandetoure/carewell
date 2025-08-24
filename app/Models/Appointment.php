<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'service_id',
        'reason',
        'symptoms',
        'is_visited',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'is_urgent',
        'price',
        'status',
    ];

        
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function tickets()
    {
        return $this->hasOne(Ticket::class);
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

}
