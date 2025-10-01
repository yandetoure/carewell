<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedAdmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bed_id',
        'medical_file_id',
        'admitted_by',
        'discharged_by',
        'admission_date',
        'expected_discharge_date',
        'discharge_date',
        'discharge_reason',
        'admission_notes',
        'discharge_notes',
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
     * Get the bed for this admission.
     */
    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    /**
     * Get the medical file (patient) for this admission.
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
            'id',
            'id',
            'medical_file_id',
            'user_id'
        );
    }

    /**
     * Get the user who admitted the patient.
     */
    public function admittedByUser()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    /**
     * Get the user who discharged the patient.
     */
    public function dischargedByUser()
    {
        return $this->belongsTo(User::class, 'discharged_by');
    }

    /**
     * Scope to get active admissions (not yet discharged).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('discharge_date');
    }

    /**
     * Scope to get completed admissions (discharged).
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('discharge_date');
    }

    /**
     * Get the duration of stay in days.
     */
    public function getDurationAttribute()
    {
        $endDate = $this->discharge_date ?? now();
        return $this->admission_date->diffInDays($endDate);
    }

    /**
     * Check if the admission is still active.
     */
    public function isActive()
    {
        return is_null($this->discharge_date);
    }
}
