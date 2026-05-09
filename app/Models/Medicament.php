<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'forme',
        'dosage',
        'description',
        'laboratoire',
        'prix',
        'disponible',
        'date_expiration',
        'quantite_stock',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'disponible' => 'boolean',
        'date_expiration' => 'date',
    ];

    /**
     * Get the ordonnances that include this medicament.
     */
    public function ordonnances()
    {
        return $this->belongsToMany(Ordonnance::class, 'ordonnance_medicament')
                    ->withPivot(['quantite', 'posologie', 'duree_jours', 'instructions_speciales'])
                    ->withTimestamps();
    }

    /**
     * Scope to get only available medicaments.
     */
    public function scopeDisponible($query)
    {
        return $query->where('disponible', true);
    }

    /**
     * Scope to search medicaments by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('forme', 'like', "%{$search}%")
                    ->orWhere('laboratoire', 'like', "%{$search}%");
    }

    /**
     * Get the full name with dosage.
     */
    public function getNomCompletAttribute()
    {
        $nom = $this->nom;
        if ($this->dosage) {
            $nom .= ' ' . $this->dosage;
        }
        if ($this->forme) {
            $nom .= ' (' . $this->forme . ')';
        }
        return $nom;
    }
}