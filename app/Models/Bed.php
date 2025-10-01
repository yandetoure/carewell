<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bed_number',
        'room_number',
        'service_id',
        'status',
        'bed_type',
        'medical_file_id',
        'admission_date',
        'expected_discharge_date',
        'discharge_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'admission_date' => 'date',
        'expected_discharge_date' => 'date',
        'discharge_date' => 'date',
    ];

    /**
     * Get the service that owns the bed.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the medical file (patient) assigned to this bed.
     */
    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }

    /**
     * Get the patient through the medical file.
     */
    public function patient()
    {
        return $this->hasOneThrough(
            User::class,
            MedicalFile::class,
            'id', // Foreign key on medical_files table
            'id', // Foreign key on users table
            'medical_file_id', // Local key on beds table
            'user_id' // Local key on medical_files table
        );
    }

    /**
     * Get all admissions for this bed.
     */
    public function admissions()
    {
        return $this->hasMany(BedAdmission::class);
    }

    /**
     * Get the current active admission.
     */
    public function currentAdmission()
    {
        return $this->hasOne(BedAdmission::class)->whereNull('discharge_date')->latest();
    }

    /**
     * Scope to get only available beds.
     */
    public function scopeLibre($query)
    {
        return $query->where('status', 'libre');
    }

    /**
     * Scope to get only occupied beds.
     */
    public function scopeOccupe($query)
    {
        return $query->where('status', 'occupe');
    }

    /**
     * Scope to get beds in maintenance.
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope to filter beds by service.
     */
    public function scopeByService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    /**
     * Scope to filter beds by room.
     */
    public function scopeByRoom($query, $roomNumber)
    {
        return $query->where('room_number', $roomNumber);
    }

    /**
     * Check if the bed is available for admission.
     */
    public function isAvailable()
    {
        return $this->status === 'libre';
    }

    /**
     * Check if the bed is occupied.
     */
    public function isOccupied()
    {
        return $this->status === 'occupe';
    }

    /**
     * Admit a patient to this bed.
     */
    public function admitPatient($medicalFileId, $admissionDate = null, $expectedDischargeDate = null, $notes = null, $admittedBy = null)
    {
        // Mettre à jour le lit
        $this->update([
            'status' => 'occupe',
            'medical_file_id' => $medicalFileId,
            'admission_date' => $admissionDate ?? now(),
            'expected_discharge_date' => $expectedDischargeDate,
        ]);

        // Créer un enregistrement d'historique
        BedAdmission::create([
            'bed_id' => $this->id,
            'medical_file_id' => $medicalFileId,
            'admitted_by' => $admittedBy ?? auth()->id(),
            'admission_date' => $admissionDate ?? now(),
            'expected_discharge_date' => $expectedDischargeDate,
            'admission_notes' => $notes,
        ]);
    }

    /**
     * Discharge the patient from this bed.
     */
    public function dischargePatient($dischargeDate = null, $dischargeReason = null, $notes = null, $dischargedBy = null)
    {
        // Enregistrer la date de sortie dans le lit
        $actualDischargeDate = $dischargeDate ?? now()->toDateString();
        
        // Mettre à jour l'enregistrement d'admission actif
        $currentAdmission = $this->currentAdmission;
        if ($currentAdmission) {
            $currentAdmission->update([
                'discharge_date' => $actualDischargeDate,
                'discharge_reason' => $dischargeReason,
                'discharge_notes' => $notes,
                'discharged_by' => $dischargedBy ?? auth()->id(),
            ]);
        }
        
        // Libérer le lit
        $this->update([
            'status' => 'libre',
            'medical_file_id' => null,
            'admission_date' => null,
            'expected_discharge_date' => null,
            'discharge_date' => $actualDischargeDate,
            'notes' => null,
        ]);
    }

    /**
     * Set the bed to maintenance mode.
     */
    public function setMaintenance($notes = null)
    {
        $this->update([
            'status' => 'maintenance',
            'notes' => $notes,
        ]);
    }

    /**
     * Set the bed as unavailable for admission.
     */
    public function setAdmissionImpossible($notes = null)
    {
        $this->update([
            'status' => 'admission_impossible',
            'notes' => $notes,
        ]);
    }

    /**
     * Make the bed available again.
     */
    public function makeAvailable()
    {
        $this->update([
            'status' => 'libre',
            'notes' => null,
        ]);
    }

    /**
     * Get the patient name if the bed is occupied.
     */
    public function getPatientNameAttribute()
    {
        if ($this->medicalFile && $this->medicalFile->user) {
            return $this->medicalFile->user->first_name . ' ' . $this->medicalFile->user->last_name;
        }
        return null;
    }

    /**
     * Get the number of days the patient has been admitted.
     */
    public function getDaysAdmittedAttribute()
    {
        if ($this->admission_date) {
            return now()->diffInDays($this->admission_date);
        }
        return 0;
    }
}
