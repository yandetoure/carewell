<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillqble =[
        'appointment_id',
        'prescription_id',
        'exam_id',
        'is_paid'
    ];
}
