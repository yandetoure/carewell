<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_full_day',
        'status',
        'appointments_pending',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_full_day' => 'boolean',
        'appointments_pending' => 'boolean',
    ];

    /**
     * Relation avec le médecin
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Scope pour les absences futures
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString());
    }

    /**
     * Scope pour les absences en cours
     */
    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    /**
     * Scope pour les absences d'un médecin
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Vérifier si une date est dans la période d'absence
     */
    public function coversDate($date): bool
    {
        $date = Carbon::parse($date)->toDateString();
        return $date >= $this->start_date && $date <= $this->end_date;
    }

    /**
     * Vérifier si une heure est dans la période d'absence (pour les absences partielles)
     */
    public function coversTime($time): bool
    {
        if ($this->is_full_day) {
            return true;
        }

        $time = Carbon::parse($time)->format('H:i');
        return $time >= $this->start_time->format('H:i') && $time <= $this->end_time->format('H:i');
    }

    /**
     * Obtenir la durée de l'absence en jours
     */
    public function getDurationInDays(): int
    {
        return (int) (Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1);
    }

    /**
     * Obtenir le statut formaté
     */
    public function getFormattedStatus(): string
    {
        return match($this->status) {
            'planned' => 'Planifiée',
            'confirmed' => 'Confirmée',
            'cancelled' => 'Annulée',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir le type formaté
     */
    public function getFormattedType(): string
    {
        return match($this->type) {
            'congé' => 'Congé',
            'formation' => 'Formation',
            'maladie' => 'Maladie',
            'personnel' => 'Personnel',
            'autre' => 'Autre',
            default => 'Inconnu'
        };
    }
}