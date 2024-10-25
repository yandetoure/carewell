<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'price',
        'service_id'
    ];
    
    public function service(){
        return $this->belongsTo(Service::class);
    }
    
    public function medicalFilePrescriptions(){
        return $this->hasMany(MedicalFilePrescription::class);
    }
    
    
    
}
