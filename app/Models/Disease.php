<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disease extends Model
{
    use HasFactory;
    
    
    protected $fillable = [
        'name'
       ];
       
       public function medicalfiles() {
           return $this->hasMany(Medicalfile::class);
       }
}
