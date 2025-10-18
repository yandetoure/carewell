<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'subject',
        'photo',
        'is_deleted',
        'updated_message',
        'deleted_at',
        'modified_at',
        'is_read',
        'read_at',
    ];

        // Méthode pour marquer un message comme lu
        public function markAsRead()
        {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }

        public function sender()
        {
            return $this->belongsTo(User::class, 'sender_id');
        }
    
        public function receiver()
        {
            return $this->belongsTo(User::class, 'receiver_id');
        }

        
        // Méthode pour récupérer les messages non lus de l'utilisateur
        // public function scopeUnreadMessages($query, $userId)
        // {
        //     return $query->where('receiver_id', $userId)
        //         ->where('is_read', false)
        //         ->orderBy('created_at', 'desc');
        // }
}