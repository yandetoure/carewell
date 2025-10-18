<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalFilePrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_files_id',
        'prescription_id',
        'is_done',
        'doctor_id',
        'dosage',
        'duration',
        'instructions',
        'quantity',
        'frequency',
        'medication_name'
    ];

 
        public function medicalFile()
        {
            return $this->belongsTo(MedicalFile::class, 'medical_files_id');
        }

        /**
         * Get the status based on is_done
         */
        public function getStatusAttribute()
        {
            if ($this->is_done) {
                return 'administered';
            } else {
                return 'pending';
            }
        }

        /**
         * Check if prescription is pending
         */
        public function isPending()
        {
            return $this->status === 'pending';
        }

        /**
         * Check if prescription is in progress
         */
        public function isInProgress()
        {
            return $this->status === 'in_progress';
        }

        /**
         * Check if prescription is administered
         */
        public function isAdministered()
        {
            return $this->status === 'administered';
        }

        /**
         * Mark prescription as in progress (same as administered for simplicity)
         */
        public function markAsInProgress()
        {
            $this->update(['is_done' => true]);
        }

        /**
         * Mark prescription as administered
         */
        public function markAsAdministered()
        {
            $this->update([
                'is_done' => true
            ]);
        }


        public function prescription() 
        {
            return $this->belongsTo(Prescription::class); 
        }

        public function doctor()
        {
            return $this->belongsTo(User::class, 'doctor_id');
        }

}
