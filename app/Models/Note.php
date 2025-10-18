<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 
        'medical_files_id',
        'doctor_id',
    ];

            // Relation avec le modèle MedicalFile
    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
