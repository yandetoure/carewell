<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'identification_number',
        'password',
        'adress',
        'day_of_birth',
        'call',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Boot function to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Creation automatique du numero d'identification unique
        static::creating(function ($user) {
            $user->identification_number = self::generateUniqueIdentificationNumber();
        });
    }

    /**
     * Generate a unique identification number.
     *
     * @return string
     */
    private static function generateUniqueIdentificationNumber()
    {
        $identification_number = Str::random(10); // Génère une chaîne aléatoire de 10 caractères

        // Vérifie que l'identification_number est unique
        while (self::where('identification_number', $identification_number)->exists()) {
            $identification_number = Str::random(10);
        }

        return $identification_number;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function medicalFiles()
    {
        return $this->hasMany(MedicalFile::class);
    }

}
