<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'city',
        'country',
        'logo',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all users (employees and patients) belonging to this clinic
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all appointments for this clinic
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get all services for this clinic
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get all medical files for this clinic
     */
    public function medicalFiles()
    {
        return $this->hasMany(MedicalFile::class);
    }

    /**
     * Get all availabilities for this clinic
     */
    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Get all absences for this clinic
     */
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    /**
     * Get all tickets for this clinic
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all beds for this clinic
     */
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
