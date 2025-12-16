@extends('layouts.app')

@section('title', 'Inscription - CareWell')

@section('content')
<!-- Hero -->
<section class="auth-hero position-relative overflow-hidden text-white">
    <div class="hero-background" style="background-image: url('{{ asset('images/banner1.png') }}');"></div>
    <span class="hero-gradient"></span>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <div class="col-xxl-5 col-xl-12">
                <span class="auth-hero-badge">
                    <i class="fas fa-user-plus me-2"></i>Nouveau patient CareWell
                </span>
                <h1 class="auth-hero-title">Créez votre espace santé en quelques minutes</h1>
                <p class="auth-hero-subtitle">
                    Centralisez vos informations médicales, prenez rendez-vous instantanément et partagez vos documents en toute sécurité avec votre équipe soignante.
                </p>
                <div class="auth-hero-steps">
                    <div class="step-card">
                        <span class="step-number">1</span>
                        <div>
                            <h4>Profil personnel</h4>
                            <p>Indiquez vos coordonnées afin que nous puissions communiquer facilement.</p>
                        </div>
                    </div>
                    <div class="step-card">
                        <span class="step-number">2</span>
                        <div>
                            <h4>Données médicales</h4>
                            <p>Ajoutez vos mesures et antécédents pour un suivi personnalisé.</p>
                        </div>
                    </div>
                    <div class="step-card">
                        <span class="step-number">3</span>
                        <div>
                            <h4>Sécurité renforcée</h4>
                            <p>Définissez un mot de passe robuste et validez nos conditions.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-7 col-xl-12">
                <div class="auth-card">
                    <div class="auth-card-header text-center">
                        <h3><i class="fas fa-user-plus me-2"></i>Inscription</h3>
                        <p>Rejoignez la communauté CareWell dès aujourd’hui</p>
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

                        <form class="auth-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="auth-section">
                                <div class="auth-section-header">
                                    <span class="auth-section-icon gradient-green"><i class="fas fa-user"></i></span>
                                    <div>
                                        <h4>Informations personnelles</h4>
                                        <p>Ces informations nous permettent de personnaliser votre suivi.</p>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label"><i class="fas fa-user me-2"></i>Prénom *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label"><i class="fas fa-user me-2"></i>Nom *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Adresse email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label"><i class="fas fa-phone me-2"></i>Numéro de téléphone *</label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-6">
                                        <label for="day_of_birth" class="form-label"><i class="fas fa-calendar me-2"></i>Date de naissance *</label>
                                    <input type="date" class="form-control @error('day_of_birth') is-invalid @enderror"
                                           id="day_of_birth" name="day_of_birth" value="{{ old('day_of_birth') }}" required>
                                    @error('day_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-6">
                                        <label for="adress" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Adresse *</label>
                                    <input type="text" class="form-control @error('adress') is-invalid @enderror"
                                           id="adress" name="adress" value="{{ old('adress') }}" required>
                                    @error('adress')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            </div>

                            <div class="auth-section-divider"></div>

                            <div class="auth-section">
                                <div class="auth-section-header">
                                    <span class="auth-section-icon gradient-teal"><i class="fas fa-heartbeat"></i></span>
                                    <div>
                                        <h4>Informations médicales</h4>
                                        <p>Partagez les éléments clés de votre santé pour un accompagnement optimal.</p>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="height" class="form-label"><i class="fas fa-ruler-vertical me-2"></i>Taille (cm)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror"
                                           id="height" name="height" value="{{ old('height') }}" min="100" max="250">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-4">
                                        <label for="weight" class="form-label"><i class="fas fa-weight me-2"></i>Poids (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                           id="weight" name="weight" value="{{ old('weight') }}" min="20" max="300" step="0.1">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    <div class="col-md-4">
                                        <label for="blood_type" class="form-label"><i class="fas fa-tint me-2"></i>Groupe sanguin</label>
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
                                    <div class="col-12">
                                        <label for="biographie" class="form-label"><i class="fas fa-file-medical me-2"></i>Antécédents médicaux</label>
                                    <textarea class="form-control @error('biographie') is-invalid @enderror"
                                              id="biographie" name="biographie" rows="3"
                                              placeholder="Décrivez vos antécédents médicaux, allergies, traitements en cours...">{{ old('biographie') }}</textarea>
                                    @error('biographie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            </div>

                            <div class="auth-section-divider"></div>

                            <div class="auth-section">
                                <div class="auth-section-header">
                                    <span class="auth-section-icon gradient-orange"><i class="fas fa-shield-alt"></i></span>
                                    <div>
                                        <h4>Sécurité du compte</h4>
                                        <p>Créez des identifiants fiables pour protéger vos informations sensibles.</p>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Mot de passe *</label>
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
                                        <small class="form-text text-muted">Au moins 8 caractères, avec lettres et chiffres.</small>
                                </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock me-2"></i>Confirmer le mot de passe *</label>
                                        <div class="input-group auth-input-group">
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" aria-label="Afficher ou masquer la confirmation du mot de passe">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="auth-section-divider"></div>

                            <div class="auth-section">
                                <div class="auth-section-header">
                                    <span class="auth-section-icon gradient-sky"><i class="fas fa-handshake"></i></span>
                                    <div>
                                        <h4>Consentements</h4>
                                        <p>Choisissez vos préférences et validez nos engagements.</p>
                                    </div>
                                </div>
                                <div class="auth-consents">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror"
                                           type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                            J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a> et la <a href="#" class="auth-link">politique de confidentialité</a> *
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
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Créer mon compte
                                </button>
                            </div>
                        </form>

                        <div class="auth-divider"><span>Ou inscrivez-vous avec</span></div>

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
                        Déjà un compte ? <a href="{{ route('login') }}">Connectez-vous ici</a>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
</section>

<!-- Benefits -->
<section class="auth-benefits py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-green"><i class="fas fa-stethoscope"></i></span>
                    <h4>Suivi médical global</h4>
                    <p>Un espace unique pour vos spécialistes, vos ordonnances et vos examens.</p>
                        </div>
                    </div>
            <div class="col-md-3">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-teal"><i class="fas fa-lock"></i></span>
                    <h4>Données protégées</h4>
                    <p>Hébergement certifié HDS et conformité RGPD pour vos documents sensibles.</p>
                        </div>
                    </div>
            <div class="col-md-3">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-orange"><i class="fas fa-bell"></i></span>
                    <h4>Notifications intelligentes</h4>
                    <p>Rappels automatiques de rendez-vous et alertes médicaments personnalisées.</p>
                        </div>
                    </div>
            <div class="col-md-3">
                <div class="benefit-card">
                    <span class="benefit-icon gradient-sky"><i class="fas fa-mobile-screen"></i></span>
                    <h4>Accès multicanal</h4>
                    <p>Connectez-vous depuis le web ou mobile, à toute heure et où que vous soyez.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .auth-hero {
        padding: 6rem 0 5rem;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(34, 197, 94, 0.9));
    }

    .auth-hero .hero-background {
        position: absolute;
        inset: 0;
        background-position: center;
        background-size: cover;
        opacity: 0.3;
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
        background: rgba(15, 118, 110, 0.25);
        padding: 0.5rem 1.35rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        margin-bottom: 1.5rem;
    }

    .auth-hero-title {
        font-size: clamp(2.4rem, 4vw, 3.1rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .auth-hero-subtitle {
        font-size: 1.08rem;
        max-width: 620px;
        opacity: 0.92;
        margin-bottom: 2.2rem;
    }

    .auth-hero-steps {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .step-card {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        background: rgba(15, 118, 110, 0.32);
        border: 1px solid rgba(209, 250, 229, 0.25);
        border-radius: 1.35rem;
        padding: 1.1rem 1.4rem;
        backdrop-filter: blur(8px);
    }

    .step-number {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: rgba(16, 185, 129, 0.95);
        font-weight: 700;
    }

    .step-card h4 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .step-card p {
        margin: 0;
        opacity: 0.85;
        font-size: 0.95rem;
    }

    .auth-card {
        background: #fff;
        border-radius: 1.75rem;
        box-shadow: 0 30px 70px -45px rgba(15, 118, 110, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.25);
        overflow: hidden;
    }

    .auth-card-header {
        padding: 2.2rem 2.5rem 1.5rem;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(45, 212, 191, 0.16));
    }

    .auth-card-header h3 {
        font-weight: 700;
        margin-bottom: 0.4rem;
        color: var(--dark-color);
    }

    .auth-card-header p {
        margin: 0;
        color: #0f766e;
        font-weight: 500;
    }

    .auth-card-body {
        padding: 2.6rem;
    }

    .auth-card-footer {
        padding: 1.4rem 2.6rem 2.2rem;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(45, 212, 191, 0.12));
        font-weight: 500;
        color: #0f172a;
    }

    .auth-card-footer a {
        color: #047857;
        font-weight: 600;
        text-decoration: none;
    }

    .auth-form .form-label {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.45rem;
    }

    .auth-form .form-control,
    .auth-form .form-select {
        border-radius: 0.95rem;
        border: 1px solid rgba(148, 163, 184, 0.38);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .auth-form .form-control:focus,
    .auth-form .form-select:focus {
        border-color: rgba(16, 185, 129, 0.8);
        box-shadow: 0 0 0 0.18rem rgba(16, 185, 129, 0.2);
    }

    .auth-input-group .btn {
        border-top-right-radius: 0.95rem;
        border-bottom-right-radius: 0.95rem;
    }

    .auth-input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .auth-section {
        background: linear-gradient(135deg, rgba(240, 253, 244, 0.65), rgba(220, 252, 231, 0.45));
        border: 1px solid rgba(167, 243, 208, 0.45);
        border-radius: 1.5rem;
        padding: 1.6rem 1.8rem;
    }

    .auth-section + .auth-section {
        margin-top: 1.8rem;
    }

    .auth-section-header {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.4rem;
    }

    .auth-section-icon {
        width: 50px;
        height: 50px;
        border-radius: 1.1rem;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.4rem;
    }

    .auth-section-header h4 {
        margin: 0 0 0.25rem;
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
    }

    .auth-section-header p {
        margin: 0;
        color: #0f766e;
        font-size: 0.95rem;
    }

    .auth-section-divider {
        height: 1px;
        background: rgba(148, 163, 184, 0.35);
        margin: 1.8rem 0;
    }

    .auth-consents .form-check + .form-check {
        margin-top: 0.75rem;
    }

    .auth-link {
        color: #047857;
        font-weight: 600;
        text-decoration: none;
    }

    .form-check-input:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.18rem rgba(16, 185, 129, 0.25);
    }

    .auth-divider {
        position: relative;
        text-align: center;
        margin: 2.5rem 0;
        color: #64748b;
        font-weight: 600;
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
        gap: 0.85rem;
    }

    .auth-social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        border-radius: 0.85rem;
        padding: 0.75rem 1.2rem;
        font-weight: 600;
        border: 1px solid rgba(148, 163, 184, 0.35);
        transition: all 0.3s ease;
    }

    .auth-social-btn.google {
        color: #1f2937;
        background: #fff;
    }

    .auth-social-btn.facebook {
        color: #0f172a;
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.2);
    }

    .auth-social-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -25px rgba(16, 185, 129, 0.45);
    }

    .auth-benefits .benefit-card {
        height: 100%;
        background: #fff;
        border-radius: 1.5rem;
        padding: 2.2rem 1.8rem;
        text-align: center;
        box-shadow: 0 25px 60px -45px rgba(15, 118, 110, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.2);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .auth-benefits .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 35px 70px -45px rgba(16, 185, 129, 0.4);
    }

    .benefit-icon {
        width: 70px;
        height: 70px;
        border-radius: 1.2rem;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.8rem;
        margin: 0 auto 1.3rem;
    }

    .auth-benefits h4 {
        font-weight: 700;
        margin-bottom: 0.65rem;
        color: #0f172a;
    }

    .auth-benefits p {
        color: #475569;
        margin: 0;
    }

    .gradient-green {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.92), rgba(34, 197, 94, 0.85));
    }

    .gradient-teal {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.95), rgba(45, 212, 191, 0.85));
    }

    .gradient-orange {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.92), rgba(251, 191, 36, 0.85));
    }

    .gradient-sky {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.9), rgba(59, 130, 246, 0.85));
    }

    @media (max-width: 1200px) {
        .auth-hero {
            padding: 5rem 0 4.5rem;
        }
    }

    @media (max-width: 992px) {
        .auth-card {
            margin-top: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .auth-hero {
            padding: 4.5rem 0 4rem;
        }

        .auth-card-header,
        .auth-card-body,
        .auth-card-footer {
            padding: 2rem;
        }

        .auth-hero-steps {
            grid-template-columns: 1fr;
        }

        .auth-benefits .benefit-card {
            padding: 2rem 1.6rem;
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
