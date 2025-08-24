@extends('layouts.app')

@section('title', 'Mon Profil - CareWell')

@section('content')
<!-- Header Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="section-title">Mon Profil</h1>
                <p class="section-subtitle">Gérez vos informations personnelles et médicales</p>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Profile Sidebar -->
            <div class="col-lg-4 mb-4">
                <div class="card profile-sidebar">
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Photo de profil" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="profile-avatar-placeholder">
                                    <i class="fas fa-user fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <h5 class="mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                        <p class="text-muted mb-3">{{ Auth::user()->email }}</p>

                        <div class="profile-stats">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h6 class="mb-1">{{ Auth::user()->appointments()->count() }}</h6>
                                        <small class="text-muted">Rendez-vous</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h6 class="mb-1">{{ Auth::user()->getMedicalFile() ? 'Oui' : 'Non' }}</h6>
                                        <small class="text-muted">Dossier médical</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="profile-actions">
                            <button class="btn btn-outline-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#photoModal">
                                <i class="fas fa-camera me-2"></i>Changer la photo
                            </button>
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                <i class="fas fa-key me-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                            </a>
                            <a href="{{ route('medical-files') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-medical me-2"></i>Mon dossier médical
                            </a>
                            <a href="{{ route('appointments') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-calendar-check me-2"></i>Mes rendez-vous
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Informations personnelles
                        </h5>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <!-- Personal Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Informations de base
                                    </h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ Auth::user()->first_name }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ Auth::user()->last_name }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ Auth::user()->email }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="day_of_birth" class="form-label">Date de naissance *</label>
                                    <input type="date" class="form-control @error('day_of_birth') is-invalid @enderror"
                                           id="day_of_birth" name="day_of_birth" value="{{ Auth::user()->day_of_birth }}" required>
                                    @error('day_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="adress" class="form-label">Adresse *</label>
                                    <input type="text" class="form-control @error('adress') is-invalid @enderror"
                                           id="adress" name="adress" value="{{ Auth::user()->adress }}" required>
                                    @error('adress')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Medical Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-heartbeat me-2"></i>Informations médicales
                                    </h6>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="height" class="form-label">Taille (cm)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror"
                                           id="height" name="height" value="{{ Auth::user()->height }}" min="100" max="250">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="weight" class="form-label">Poids (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                           id="weight" name="weight" value="{{ Auth::user()->weight }}" min="20" max="300" step="0.1">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="blood_type" class="form-label">Groupe sanguin</label>
                                    <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                        <option value="">Sélectionner</option>
                                        <option value="A+" {{ Auth::user()->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ Auth::user()->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ Auth::user()->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ Auth::user()->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ Auth::user()->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ Auth::user()->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ Auth::user()->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ Auth::user()->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    @error('blood_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="biographie" class="form-label">Antécédents médicaux</label>
                                    <textarea class="form-control @error('biographie') is-invalid @enderror"
                                              id="biographie" name="biographie" rows="4"
                                              placeholder="Décrivez vos antécédents médicaux, allergies, traitements en cours...">{{ Auth::user()->biographie }}</textarea>
                                    @error('biographie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Activité récente</h6>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->appointments()->latest()->take(5)->count() > 0)
                            <div class="activity-list">
                                @foreach(Auth::user()->appointments()->latest()->take(5)->get() as $appointment)
                                <div class="activity-item d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="activity-icon me-3">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Rendez-vous {{ $appointment->service ? 'avec ' . $appointment->service->name : '' }}</h6>
                                        <small class="text-muted">{{ $appointment->appointment_date->format('d/m/Y à H:i') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">Aucune activité récente</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer la photo de profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="photo" class="form-label">Sélectionner une image</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                        <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Mettre à jour la photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-sidebar {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        background: var(--light-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .stat-item {
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background-color: var(--light-color);
    }

    .profile-actions .btn {
        width: 100%;
    }

    .activity-item {
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background-color: var(--light-color);
        border-radius: 0.5rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        background: var(--light-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }
</style>
@endsection
