<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFilePrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_file_id',
        'prescription_id',
        'status',
    ];
}
