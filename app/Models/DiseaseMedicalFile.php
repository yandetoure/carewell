<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiseaseMedicalFile extends Model
{
    use HasFactory;
    
    protected $fillable = [
       'disease_id',
       'medicalfile_id',
       'state',
       'treatment',
       ];
       
       public function disease() {
           return $this->belongsTo(Disease::class);
       }
}
