<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable  implements JWTSubject
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
        'phone_number',
        'photo',
        'service_id',
        'biographie',
        'height',
        'weight',
        'blood_type',
        'grade_id',
        'status',
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

        // Création automatique du dossier médical pour les patients
        static::created(function ($user) {
            // Vérifier si l'utilisateur a le rôle Patient
            // Note: Le rôle peut être assigné après la création
            if ($user->hasRole('Patient')) {
                $user->createMedicalFile();
            }
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
     * Create a medical file for the patient.
     *
     * @return \App\Models\MedicalFile
     */
    public function createMedicalFile()
    {
        return MedicalFile::create([
            'user_id' => $this->id,
        ]);
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


    public function getMedicalFile()
    {
        // Récupération du dossier médical
        return $this->hasOne(MedicalFile::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function availabilities()
    {
        return $this->hasMany(Availability::class, 'doctor_id');
    }

    public function Ticket(){
        return $this->hasMany(Ticket::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function medicalFiles(){
        return $this->hasMany(MedicalFile::class);
    }

    public function ordonnancesAsPatient()
    {
        return $this->hasMany(Ordonnance::class, 'patient_id');
    }

    public function ordonnancesAsMedecin()
    {
        return $this->hasMany(Ordonnance::class, 'medecin_id');
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    //relation avec grade
    public function grade(){
    return $this->belongsTo(Grade::class);
}

    

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}