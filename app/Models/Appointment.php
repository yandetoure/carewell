<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'reason',
        'symptoms',
        'is_visited',
        'date',
        'time',
    ];

        // Relation avec le modèle User
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
        // Relation avec le modèle Service
        public function service()
        {
            return $this->belongsTo(Service::class);
        }

            // Relation avec le modèle Ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
}
