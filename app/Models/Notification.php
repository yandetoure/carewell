<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',       // ID de l'utilisateur qui reçoit la notification
        'title',         // Titre de la notification
        'message',       // Message de la notification
        'is_read',       // Indique si la notification a été lue
        'type',          // Type de notification (e.g., "appointment", "status_change")
    ];

    /**
     * Relation avec le modèle User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}