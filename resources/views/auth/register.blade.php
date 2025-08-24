@extends('layouts.app')

@section('title', 'Inscription - CareWell')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Inscription
                        </h3>
                        <p class="mb-0 mt-2">Rejoignez CareWell et prenez soin de votre santé</p>
                    </div>

                    <div class="card-body p-5">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Personal Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Informations personnelles
                                    </h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Prénom *
                                    </label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nom *
                                    </label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Adresse email *
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Numéro de téléphone *
                                    </label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="day_of_birth" class="form-label">
                                        <i class="fas fa-calendar me-2"></i>Date de naissance *
                                    </label>
                                    <input type="date" class="form-control @error('day_of_birth') is-invalid @enderror"
                                           id="day_of_birth" name="day_of_birth" value="{{ old('day_of_birth') }}" required>
                                    @error('day_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="adress" class="form-label">
                                        <i class="fas fa-map-marker-alt me-2"></i>Adresse *
                                    </label>
                                    <input type="text" class="form-control @error('adress') is-invalid @enderror"
                                           id="adress" name="adress" value="{{ old('adress') }}" required>
                                    @error('adress')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Medical Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-heartbeat me-2"></i>Informations médicales
                                    </h5>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="height" class="form-label">
                                        <i class="fas fa-ruler-vertical me-2"></i>Taille (cm)
                                    </label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror"
                                           id="height" name="height" value="{{ old('height') }}" min="100" max="250">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="weight" class="form-label">
                                        <i class="fas fa-weight me-2"></i>Poids (kg)
                                    </label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                           id="weight" name="weight" value="{{ old('weight') }}" min="20" max="300" step="0.1">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="blood_type" class="form-label">
                                        <i class="fas fa-tint me-2"></i>Groupe sanguin
                                    </label>
                                    <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                        <option value="">Sélectionner</option>
                                        <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    @error('blood_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="biographie" class="form-label">
                                        <i class="fas fa-file-medical me-2"></i>Antécédents médicaux
                                    </label>
                                    <textarea class="form-control @error('biographie') is-invalid @enderror"
                                              id="biographie" name="biographie" rows="3"
                                              placeholder="Décrivez vos antécédents médicaux, allergies, traitements en cours...">{{ old('biographie') }}</textarea>
                                    @error('biographie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Account Security -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-shield-alt me-2"></i>Sécurité du compte
                                    </h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Mot de passe *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Le mot de passe doit contenir au moins 8 caractères
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Confirmer le mot de passe *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror"
                                           type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a>
                                        et la <a href="#" class="text-decoration-none">politique de confidentialité</a> *
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Je souhaite recevoir la newsletter avec les actualités santé
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Créer mon compte
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-3">Ou inscrivez-vous avec</p>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button class="btn btn-outline-dark">
                                    <i class="fab fa-google me-2"></i>Google
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center py-3">
                        <p class="mb-0">
                            Déjà un compte ?
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                Connectez-vous ici
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="row mt-5 g-4">
                    <div class="col-md-3 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-3"></i>
                            <h6>Rendez-vous</h6>
                            <p class="small text-muted">Prenez RDV en ligne</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-file-medical fa-2x text-success mb-3"></i>
                            <h6>Dossier médical</h6>
                            <p class="small text-muted">Suivi complet</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-bell fa-2x text-warning mb-3"></i>
                            <h6>Notifications</h6>
                            <p class="small text-muted">Rappels automatiques</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-mobile-alt fa-2x text-info mb-3"></i>
                            <h6>Mobile</h6>
                            <p class="small text-muted">Accès partout</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 1rem;
    }

    .card-header {
        border-radius: 1rem 1rem 0 0 !important;
        border: none;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--success-color);
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }

    .benefit-item {
        padding: 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .benefit-item:hover {
        transform: translateY(-5px);
    }

    .input-group .btn {
        border-left: none;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .input-group .form-control {
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group .form-control:focus {
        border-right: none;
    }

    .input-group .form-control:focus + .btn {
        border-color: var(--success-color);
    }

    .form-check-input:checked {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.setAttribute('type', type);

        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // Password strength indicator
    password.addEventListener('input', function() {
        const value = this.value;
        const strength = 0;

        if (value.length >= 8) strength++;
        if (/[a-z]/.test(value)) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[^A-Za-z0-9]/.test(value)) strength++;

        // You can add visual feedback here
    });
});
</script>
@endsection
