<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification_number',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->identification_number = self::generateUniqueIdentificationNumber();
        });
    }

    private static function generateUniqueIdentificationNumber()
    {
        $identification_number = Str::random(10); 

        while (self::where('identification_number', $identification_number)->exists()) {
            $identification_number = Str::random(10);
        }

        return $identification_number;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalprescription()
    {
        return $this->hasMany(MedicalFilePrescription::class, 'medical_files_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(MedicalFilePrescription::class, 'medical_files_id');
    }

    public function medicalexam()
    {
        return $this->hasMany(MedicalFileExam::class, 'medical_file_id');
    }

    public function exams()
    {
        return $this->hasMany(MedicalFileExam::class, 'medical_file_id');
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class,  'medical_files_id');
    }

    public function note()
    {
        return $this->hasMany(Note::class, 'medical_files_id');
    }       
    
    public function medicaldisease()
    {
        return $this->hasMany(DiseaseMedicalFile::class, 'medical_file_id');
    } 

    public function beds()
    {
        return $this->hasMany(Bed::class, 'medical_file_id');
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function latestVitalSigns()
    {
        return $this->hasOne(VitalSign::class)->latest('recorded_at');
    }

    public function bedAdmissions()
    {
        return $this->hasMany(BedAdmission::class, 'medical_file_id');
    }
}
