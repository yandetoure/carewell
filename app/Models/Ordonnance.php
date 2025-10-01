<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ordonnance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_ordonnance',
        'patient_id',
        'medecin_id',
        'patient_first_name',
        'patient_last_name',
        'medecin_first_name',
        'medecin_last_name',
        'instructions',
        'date_prescription',
        'date_validite',
        'statut',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_prescription' => 'date',
        'date_validite' => 'date',
    ];

    /**
     * Boot function to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate numero_ordonnance when creating a new ordonnance
        static::creating(function ($ordonnance) {
            if (empty($ordonnance->numero_ordonnance)) {
                $ordonnance->numero_ordonnance = self::generateUniqueNumber();
            }
        });
    }

    /**
     * Generate a unique ordonnance number.
     *
     * @return string
     */
    private static function generateUniqueNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = Str::random(4);
        $numero = $prefix . $date . $random;

        // Vérifie que le numéro est unique
        while (self::where('numero_ordonnance', $numero)->exists()) {
            $random = Str::random(4);
            $numero = $prefix . $date . $random;
        }

        return $numero;
    }

    /**
     * Get the patient that owns the ordonnance.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor that created the ordonnance.
     */
    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Get the medicaments for this ordonnance.
     */
    public function medicaments()
    {
        return $this->belongsToMany(Medicament::class, 'ordonnance_medicament')
                    ->withPivot(['quantite', 'posologie', 'duree_jours', 'instructions_speciales'])
                    ->withTimestamps();
    }

    /**
     * Get the patient's full name.
     */
    public function getPatientNomCompletAttribute()
    {
        return $this->patient_first_name . ' ' . $this->patient_last_name;
    }

    /**
     * Get the doctor's full name.
     */
    public function getMedecinNomCompletAttribute()
    {
        return $this->medecin_first_name . ' ' . $this->medecin_last_name;
    }

    /**
     * Get the patient's email.
     */
    public function getPatientEmailAttribute()
    {
        return $this->patient ? $this->patient->email : 'N/A';
    }

    /**
     * Get the patient's phone.
     */
    public function getPatientPhoneAttribute()
    {
        return $this->patient ? $this->patient->phone : 'N/A';
    }

    /**
     * Get the patient's date of birth.
     */
    public function getPatientDateNaissanceAttribute()
    {
        return $this->patient ? $this->patient->day_of_birth : null;
    }

    /**
     * Scope to get ordonnances by patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope to get ordonnances by doctor.
     */
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('medecin_id', $doctorId);
    }

    /**
     * Scope to get active ordonnances.
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Scope to get expired ordonnances.
     */
    public function scopeExpired($query)
    {
        return $query->where('statut', 'expiree')
                    ->orWhere(function ($q) {
                        $q->where('date_validite', '<', now())
                          ->where('statut', 'active');
                    });
    }

    /**
     * Check if the ordonnance is expired.
     */
    public function isExpired()
    {
        return $this->date_validite && $this->date_validite < now();
    }

    /**
     * Mark the ordonnance as expired.
     */
    public function markAsExpired()
    {
        $this->update(['statut' => 'expiree']);
    }
}