<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalFileExam extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'medical_file_id',
        'exam_id',
        'is_done',
        'doctor_id',
        'type',
        'instructions'
    ];
    
    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }

    public function exam() 
    {
        return $this->belongsTo(Exam::class); 
    }

    public function result()
    {
        return $this->hasOne(Result::class, 'exam_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

}
