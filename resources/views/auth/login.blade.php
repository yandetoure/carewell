@extends('layouts.app')

@section('title', 'Connexion - CareWell')

@section('content')
<!-- Hero -->
<section class="auth-hero position-relative overflow-hidden text-white">
    <div class="hero-background" style="background-image: url('{{ asset('images/banner1.png') }}');"></div>
    <span class="hero-gradient"></span>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="auth-hero-badge">
                    <i class="fas fa-shield-heart me-2"></i>Connexion sécurisée
                </span>
                <h1 class="auth-hero-title">Retrouvez votre espace de soins en toute confiance</h1>
                <p class="auth-hero-subtitle">
                    Suivez vos rendez-vous, vos traitements et vos documents médicaux depuis une plateforme unifiée, protégée et disponible à tout moment.
                </p>
                <ul class="auth-hero-list">
                    <li><i class="fas fa-check"></i>Rappels automatiques de rendez-vous et notifications</li>
                    <li><i class="fas fa-check"></i>Accès instantané à votre dossier médical sécurisé</li>
                    <li><i class="fas fa-check"></i>Échanges directs avec votre équipe soignante</li>
                </ul>
                <div class="auth-hero-highlights">
                    <div class="highlight-card">
                        <i class="fas fa-lock"></i>
                        <div>
                            <span class="highlight-title">Certifié HDS</span>
                            <span class="highlight-subtitle">Hébergement de données de santé</span>
                        </div>
                    </div>
                    <div class="highlight-card">
                        <i class="fas fa-clock"></i>
                        <div>
                            <span class="highlight-title">24h/24</span>
                            <span class="highlight-subtitle">Plateforme disponible en continu</span>
                        </div>
                    </div>
                    <div class="highlight-card">
                        <i class="fas fa-user-nurse"></i>
                        <div>
                            <span class="highlight-title">+500</span>
                            <span class="highlight-subtitle">Professionnels connectés</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="auth-card">
                    <div class="auth-card-header text-center">
                        <h3><i class="fas fa-right-to-bracket me-2"></i>Connexion</h3>
                        <p>Accédez à votre espace patient CareWell</p>
                    </div>
                    <div class="auth-card-body">
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

                        <form class="auth-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Adresse email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Mot de passe
                                </label>
                                <div class="input-group auth-input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Afficher ou masquer le mot de passe">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="auth-options">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="auth-link">
                                    <i class="fas fa-key me-1"></i>Mot de passe oublié ?
                                </a>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-right-to-bracket me-2"></i>Se connecter
                                </button>
                            </div>
                        </form>

                        <div class="auth-divider"><span>Ou continuez avec</span></div>

                        <div class="auth-social">
                            <button type="button" class="btn auth-social-btn google">
                                    <i class="fab fa-google me-2"></i>Google
                                </button>
                            <button type="button" class="btn auth-social-btn facebook">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </button>
                        </div>
                    </div>
                    <div class="auth-card-footer text-center">
                            Pas encore de compte ?
                        <a href="{{ route('register') }}">Créez un compte CareWell</a>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
</section>

<!-- Benefits -->
<section class="auth-benefits py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-blue"><i class="fas fa-calendar-check"></i></span>
                    <h4>Rendez-vous facilités</h4>
                    <p>Prenez ou modifiez vos rendez-vous 24h/24 depuis votre espace personnel.</p>
                        </div>
                    </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-green"><i class="fas fa-file-medical"></i></span>
                    <h4>Dossier centralisé</h4>
                    <p>Retrouvez vos ordonnances, résultats d’examen et comptes rendus en un clic.</p>
                        </div>
                    </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-purple"><i class="fas fa-comments"></i></span>
                    <h4>Suivi collaboratif</h4>
                    <p>Discutez avec l’équipe soignante et recevez des conseils personnalisés.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .auth-hero {
        padding: 4.5rem 0 4rem;
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.9), rgba(37, 99, 235, 0.9));
    }

    .auth-hero .hero-background {
        position: absolute;
        inset: 0;
        background-position: center;
        background-size: cover;
        opacity: 0.35;
        filter: blur(2px);
        transform: scale(1.05);
    }

    .auth-hero .hero-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.65) 100%);
        mix-blend-mode: multiply;
    }

    .auth-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.16);
        padding: 0.5rem 1.3rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        margin-bottom: 1.5rem;
    }

    .auth-hero-title {
        font-size: clamp(2rem, 3.2vw, 2.6rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .auth-hero-subtitle {
        font-size: 1rem;
        max-width: 620px;
        opacity: 0.9;
        margin-bottom: 1.75rem;
    }

    .auth-hero-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2.2rem;
        display: grid;
        gap: 0.65rem;
    }

    .auth-hero-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .auth-hero-list i {
        color: #22c55e;
    }

    .auth-hero-highlights {
        display: grid;
        gap: 0.85rem;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .highlight-card {
        display: flex;
        gap: 1rem;
        align-items: center;
        background: rgba(15, 23, 42, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 1.15rem;
        padding: 0.85rem 1.1rem;
        backdrop-filter: blur(8px);
    }

    .highlight-card i {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: rgba(255, 255, 255, 0.2);
        font-size: 1.1rem;
    }

    .highlight-title {
        display: block;
        font-weight: 700;
        font-size: 1.05rem;
    }

    .highlight-subtitle {
        font-size: 0.85rem;
        opacity: 0.7;
    }

    .auth-card {
        background: #fff;
        border-radius: 1.4rem;
        box-shadow: 0 24px 55px -40px rgba(15, 23, 42, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.2);
        overflow: hidden;
    }

    .auth-card-header {
        padding: 1.8rem 2rem 1.25rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.07), rgba(129, 140, 248, 0.1));
    }

    .auth-card-header h3 {
        font-weight: 700;
        margin-bottom: 0.35rem;
        color: var(--dark-color);
    }

    .auth-card-header p {
        margin: 0;
        color: #475569;
    }

    .auth-card-body {
        padding: 2rem 2.1rem;
    }

    .auth-card-footer {
        padding: 1.25rem 2rem 1.75rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(59, 130, 246, 0.07));
        font-weight: 500;
        color: #1f2937;
    }

    .auth-card-footer a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .auth-form .form-label {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .auth-form .form-control {
        border-radius: 0.85rem;
        border: 1px solid rgba(148, 163, 184, 0.35);
        padding: 0.68rem 0.9rem;
        transition: all 0.3s ease;
    }

    .auth-form .form-control:focus {
        border-color: rgba(37, 99, 235, 0.75);
        box-shadow: 0 0 0 0.16rem rgba(37, 99, 235, 0.18);
    }

    .auth-input-group .btn {
        border-top-right-radius: 0.95rem;
        border-bottom-right-radius: 0.95rem;
    }

    .auth-input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .auth-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 0.75rem;
    }

    .auth-link {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .auth-divider {
        position: relative;
        text-align: center;
        margin: 2rem 0;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 40%;
        height: 1px;
        background: rgba(148, 163, 184, 0.4);
    }

    .auth-divider::before {
        left: 0;
    }

    .auth-divider::after {
        right: 0;
    }

    .auth-divider span {
        display: inline-block;
        padding: 0 0.75rem;
        background: #fff;
    }

    .auth-social {
        display: grid;
        gap: 0.75rem;
    }

    .auth-social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        border-radius: 0.85rem;
        padding: 0.7rem 1.05rem;
        font-weight: 600;
        border: 1px solid rgba(148, 163, 184, 0.32);
        transition: all 0.3s ease;
    }

    .auth-social-btn.google {
        color: #1f2937;
        background: #fff;
    }

    .auth-social-btn.facebook {
        color: #1d4ed8;
        background: rgba(37, 99, 235, 0.08);
        border-color: rgba(37, 99, 235, 0.16);
    }

    .auth-social-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -25px rgba(37, 99, 235, 0.55);
    }

    .auth-benefits .benefit-card {
        height: 100%;
        background: #fff;
        border-radius: 1.35rem;
        padding: 1.9rem 1.6rem;
        text-align: center;
        box-shadow: 0 22px 55px -45px rgba(15, 23, 42, 0.3);
        border: 1px solid rgba(148, 163, 184, 0.2);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 35px 70px -45px rgba(37, 99, 235, 0.35);
    }

    .benefit-icon {
        width: 62px;
        height: 62px;
        border-radius: 1.1rem;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.6rem;
        margin: 0 auto 1.1rem;
    }

    .benefit-card h4 {
        font-weight: 700;
        margin-bottom: 0.65rem;
        color: var(--dark-color);
    }

    .benefit-card p {
        color: #475569;
        margin: 0;
    }

    .gradient-blue {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(59, 130, 246, 0.85));
    }

    .gradient-green {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(34, 197, 94, 0.85));
    }

    .gradient-purple {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.9), rgba(168, 85, 247, 0.85));
    }

    @media (max-width: 992px) {
        .auth-hero {
            padding: 5rem 0 4.5rem;
        }

        .auth-hero-highlights {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .auth-card {
            margin-top: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .auth-hero {
            padding: 4.5rem 0 4rem;
        }

        .auth-hero-subtitle {
            font-size: 1rem;
        }

        .auth-card-header,
        .auth-card-body,
        .auth-card-footer {
            padding: 2rem;
        }

        .auth-benefits .benefit-card {
        padding: 1.8rem 1.45rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
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
