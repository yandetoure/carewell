<?php declare(strict_types=1); 

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
        'doctor_id',
        'status',
        'files'
    ];

    protected $casts = [
        'files' => 'array'
    ];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }
    
    public function examprescription(){
        return $this->belongsTo(MedicalFileExam::class);
    }
    
    public function doctor(){
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
}
