<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relation avec les services
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'category', 'slug');
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour trier par ordre d'affichage
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Accessor pour obtenir le nombre de services
     */
    public function getServicesCountAttribute()
    {
        return $this->services()->count();
    }

    /**
     * Générer un slug à partir du nom
     */
    public static function generateSlug($name)
    {
        return strtolower(str_replace([' ', '_', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'ô', 'ö', 'ù', 'û', 'ü', 'ÿ', 'ç', 'ñ'], ['-', '-', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'o', 'o', 'u', 'u', 'u', 'y', 'c', 'n'], $name));
    }
}