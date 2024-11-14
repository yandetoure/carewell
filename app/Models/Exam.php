<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
    ];


    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function results(){
        return $this->hasMany(Result::class); 
    }
    
    public function medicalFileExam(){
        return $this->hasMany(MedicalFileExam::class);
    }

    public function tickets()
    {
        return $this->hasOne(Ticket::class);
    }
    

}
