<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_file_id',
        'nurse_id',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate',
        'temperature',
        'oxygen_saturation',
        'respiratory_rate',
        'weight',
        'height',
        'notes',
        'recorded_at'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'blood_pressure_systolic' => 'decimal:2',
        'blood_pressure_diastolic' => 'decimal:2',
        'temperature' => 'decimal:2',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Get the medical file that owns the vital signs.
     */
    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }

    /**
     * Get the nurse who recorded the vital signs.
     */
    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    /**
     * Get the blood pressure as a formatted string.
     */
    public function getBloodPressureAttribute()
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return $this->blood_pressure_systolic . '/' . $this->blood_pressure_diastolic . ' mmHg';
        }
        return null;
    }

    /**
     * Get the temperature with unit.
     */
    public function getTemperatureFormattedAttribute()
    {
        return $this->temperature ? $this->temperature . '°C' : null;
    }

    /**
     * Get the heart rate with unit.
     */
    public function getHeartRateFormattedAttribute()
    {
        return $this->heart_rate ? $this->heart_rate . ' BPM' : null;
    }

    /**
     * Get the oxygen saturation with unit.
     */
    public function getOxygenSaturationFormattedAttribute()
    {
        return $this->oxygen_saturation ? $this->oxygen_saturation . '%' : null;
    }
}
