<?php declare(strict_types=1); 

namespace App\Models;

use App\Models\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, BelongsToClinic;


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
        'clinic_id',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    /**
     * Get the clinic that the appointment belongs to
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

}
