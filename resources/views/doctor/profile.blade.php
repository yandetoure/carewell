@extends('layouts.doctor')

@section('title', 'Mon Profil - Docteur')
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'Gestion de votre profil professionnel')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ $doctor->first_name ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ $doctor->last_name ?? '' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ $doctor->email ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                   value="{{ $doctor->phone_number ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="adress" class="form-label">Adresse</label>
                            <textarea class="form-control" id="adress" name="adress" rows="3">{{ $doctor->adress ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="day_of_birth" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="day_of_birth" name="day_of_birth" 
                                   value="{{ $doctor->day_of_birth ?? '' }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>Informations professionnelles
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="specialite" class="form-label">Spécialité</label>
                            <input type="text" class="form-control" id="specialite" name="specialite" 
                                   value="{{ $doctor->specialite ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="numero_ordre" class="form-label">Numéro d'ordre</label>
                            <input type="text" class="form-control" id="numero_ordre" name="numero_ordre" 
                                   value="{{ $doctor->numero_ordre ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="experience_years" class="form-label">Années d'expérience</label>
                            <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                   value="{{ $doctor->experience_years ?? '' }}" min="0" max="50">
                        </div>

                        <div class="mb-3">
                            <label for="consultation_fee" class="form-label">Frais de consultation (FCFA)</label>
                            <input type="number" class="form-control" id="consultation_fee" name="consultation_fee" 
                                   value="{{ $doctor->consultation_fee ?? '' }}" min="0">
                        </div>

                        <div class="mb-3">
                            <label for="biographie" class="form-label">Biographie</label>
                            <textarea class="form-control" id="biographie" name="biographie" rows="4">{{ $doctor->biographie ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations médicales -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat me-2"></i>Informations médicales
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Poids (kg)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" 
                                           value="{{ $doctor->weight ?? '' }}" min="0" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="height" class="form-label">Taille (cm)</label>
                                    <input type="number" class="form-control" id="height" name="height" 
                                           value="{{ $doctor->height ?? '' }}" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="blood_type" class="form-label">Groupe sanguin</label>
                            <select class="form-select" id="blood_type" name="blood_type">
                                <option value="">Sélectionner un groupe</option>
                                <option value="A+" {{ $doctor->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ $doctor->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ $doctor->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ $doctor->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ $doctor->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ $doctor->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ $doctor->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ $doctor->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Photo de profil -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-camera me-2"></i>Photo de profil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($doctor->photo)
                            <img src="{{ asset('storage/' . $doctor->photo) }}" alt="Photo de profil" 
                                 class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; margin: 0 auto;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Formats acceptés: JPG, PNG, GIF (max 2MB)</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Changer la photo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Changement de mot de passe -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Changement de mot de passe
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistiques du profil -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Statistiques du profil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $doctor->created_at ? \Carbon\Carbon::parse($doctor->created_at)->diffInDays(now()) : 0 }}</h4>
                                <p class="text-muted mb-0">Jours d'activité</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success">{{ $doctor->experience_years ?? 0 }}</h4>
                                <p class="text-muted mb-0">Années d'expérience</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-info">{{ $doctor->consultation_fee ?? 0 }}</h4>
                                <p class="text-muted mb-0">Frais de consultation</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $doctor->numero_ordre ? 'Validé' : 'Non validé' }}</h4>
                                <p class="text-muted mb-0">Statut professionnel</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header h5 {
    color: #495057;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.btn {
    border-radius: 8px;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}
</style>
@endpush
