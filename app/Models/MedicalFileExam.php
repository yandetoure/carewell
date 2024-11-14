<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalFileExam extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'medical_files_id',
        'exam_id',
        'is_done',
        'doctor_id',
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

}
