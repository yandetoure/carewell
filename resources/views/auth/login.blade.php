@extends('layouts.app')

@section('title', 'Connexion - CareWell')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </h3>
                        <p class="mb-0 mt-2">Accédez à votre espace patient</p>
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

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Adresse email
                                </label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Mot de passe
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    <i class="fas fa-key me-1"></i>Mot de passe oublié ?
                                </a>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-3">Ou connectez-vous avec</p>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-dark btn-lg">
                                    <i class="fab fa-google me-2"></i>Google
                                </button>
                                <button class="btn btn-outline-primary btn-lg">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center py-3">
                        <p class="mb-0">
                            Pas encore de compte ?
                            <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                                Inscrivez-vous ici
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="row mt-5 g-4">
                    <div class="col-md-4 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-3"></i>
                            <h6>Rendez-vous en ligne</h6>
                            <p class="small text-muted">Prenez rendez-vous 24h/24</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-file-medical fa-2x text-success mb-3"></i>
                            <h6>Dossier médical</h6>
                            <p class="small text-muted">Accédez à vos informations</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="benefit-item">
                            <i class="fas fa-user-md fa-2x text-warning mb-3"></i>
                            <h6>Suivi personnalisé</h6>
                            <p class="small text-muted">Suivi médical adapté</p>
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

    .form-control {
        border-radius: 0.5rem;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }

    .form-control-lg {
        padding: 0.75rem 1rem;
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
        border-color: var(--primary-color);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
</script>
@endsection
