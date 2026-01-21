<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;

trait BelongsToClinic
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToClinic()
    {
        // Automatically set clinic_id when creating
        static::creating(function ($model) {
            if (Auth::check() && !Auth::user()->hasRole('Super Admin')) {
                $user = Auth::user();
                if ($user->clinic_id && !isset($model->clinic_id)) {
                    $model->clinic_id = $user->clinic_id;
                }
            }
        });
    }

    /**
     * Scope to filter by clinic
     */
    public function scopeForClinic($query, $clinicId = null)
    {
        if ($clinicId) {
            return $query->where('clinic_id', $clinicId);
        }
        
        if (Auth::check() && !Auth::user()->hasRole('Super Admin')) {
            $user = Auth::user();
            if ($user->clinic_id) {
                return $query->where('clinic_id', $user->clinic_id);
            }
        }
        
        return $query;
    }

    /**
     * Scope to get all records (including for Super Admin)
     */
    public function scopeAllClinics($query)
    {
        return $query->withoutGlobalScope('clinic');
    }
}

