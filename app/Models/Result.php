<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'exam_id',
        'image',
        'description',
        'doctor_id'
    ];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }
    
    public function examprescription(){
        return $this->belongsTo(MedicalFileExam::class);
    }
    
}
