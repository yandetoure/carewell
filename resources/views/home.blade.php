@extends('layouts.app')

@section('title', 'CareWell - Accueil')

@section('content')
@section('styles')
<style>
    :root {
        --hero-dark: #052f1b;
        --hero-light: #0f6b3c;
        --success-100: #e8f6ee;
        --success-200: #c8ead8;
        --success-500: #1f8f57;
        --success-600: #167848;
        --success-700: #0e5f36;
        --neutral-900: #0f172a;
    }

    .hero-modern {
        position: relative;
        padding: 7rem 0 6rem;
        background: radial-gradient(circle at top left, rgba(255, 255, 255, 0.08), transparent 55%),
                    linear-gradient(120deg, rgba(5, 47, 27, 0.95), rgba(15, 107, 60, 0.85));
        color: #fff;
        overflow: hidden;
    }

    .hero-modern::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url('{{ asset('images/pattern-mesh.svg') }}') center/cover;
        opacity: 0.12;
        mix-blend-mode: screen;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(4px);
        font-weight: 500;
    }

    .hero-title {
        font-size: clamp(2.75rem, 4vw, 3.8rem);
        line-height: 1.08;
        font-weight: 700;
        margin-bottom: 1.25rem;
        letter-spacing: -0.02em;
    }

    .hero-desc {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.84);
        margin-bottom: 2rem;
    }

    .hero-metrics {
        display: grid;
        gap: 1.25rem;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        margin-top: 3rem;
    }

    .metric-card {
        padding: 1.5rem;
        border-radius: 1.25rem;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.14);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        display: block;
    }

    .hero-card {
        position: relative;
        padding: 2rem;
        background: linear-gradient(160deg, rgba(255, 255, 255, 0.98), rgba(232, 246, 238, 0.85));
        border-radius: 1.75rem;
        box-shadow: 0 40px 80px rgba(5, 47, 27, 0.25);
        color: var(--neutral-900);
        overflow: hidden;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        border: 1px solid rgba(31, 143, 87, 0.18);
        pointer-events: none;
    }

    .hero-card .badge-soft-success {
        background: var(--success-200);
        color: var(--success-600);
        border-radius: 999px;
        padding: 0.4rem 0.9rem;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .btn-pill-success {
        background: linear-gradient(135deg, #20bf6b, #16a34a);
        border: none;
        color: #fff;
        padding: 0.85rem 2rem;
        border-radius: 999px;
        font-weight: 600;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 20px 32px rgba(14, 95, 54, 0.25);
    }

    .btn-pill-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 28px 48px rgba(14, 95, 54, 0.35);
    }

    .btn-pill-soft {
        background: rgba(31, 143, 87, 0.12);
        color: var(--success-700);
        border: 1px solid rgba(31, 143, 87, 0.25);
        border-radius: 999px;
        padding: 0.85rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-pill-soft:hover {
        background: rgba(31, 143, 87, 0.22);
        color: var(--success-700);
    }

    .feature-grid {
        margin-top: -4rem;
        position: relative;
        z-index: 2;
    }

    .feature-card {
        height: 100%;
        padding: 2.2rem 2rem;
        border-radius: 1.5rem;
        background: #fff;
        border: 1px solid rgba(15, 107, 60, 0.08);
        box-shadow: 0 24px 60px rgba(15, 107, 60, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 32px 80px rgba(15, 107, 60, 0.12);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(31, 143, 87, 0.12);
        color: var(--success-600);
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .section-label {
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--success-600);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .services-showcase .card {
        border-radius: 1.6rem;
        border: none;
        overflow: hidden;
        box-shadow: 0 24px 48px rgba(15, 107, 60, 0.12);
    }

    .services-showcase .card-img-top {
        height: 220px;
        object-fit: cover;
    }

    .services-showcase .card-body {
        padding: 1.75rem;
    }

    .services-showcase .price-badge {
        background: rgba(31, 143, 87, 0.12);
        color: var(--success-700);
        font-weight: 600;
        border-radius: 999px;
        padding: 0.35rem 1rem;
    }

    .articles-stream {
        background: linear-gradient(120deg, rgba(232, 246, 238, 0.6), rgba(200, 234, 216, 0.7));
        border-radius: 2rem;
        padding: 3.5rem 2.5rem;
    }

    .articles-stream .card {
        border-radius: 1.5rem;
        border: 1px solid rgba(31, 143, 87, 0.12);
        box-shadow: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .articles-stream .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 28px 48px rgba(15, 107, 60, 0.18);
    }

    .articles-stream .card-img-top {
        height: 220px;
        object-fit: cover;
    }

    .stats-panel {
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.08), transparent 60%),
                    linear-gradient(135deg, var(--success-600), var(--hero-dark));
        color: #fff;
        padding: 5rem 0;
        position: relative;
    }

    .stats-panel .stat-box {
        padding: 2rem 1.5rem;
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
        margin-top: 2.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0.25rem;
        bottom: 0.25rem;
        width: 2px;
        background: linear-gradient(180deg, rgba(31, 143, 87, 0.2), rgba(31, 143, 87, 0.6));
    }

    .timeline-step {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 1.75rem;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    .timeline-step::before {
        content: '';
        position: absolute;
        left: -1.03rem;
        top: 0.25rem;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #fff;
        border: 4px solid var(--success-500);
    }

    .cta-wrapper {
        padding: 5rem 0 6rem;
        background: #fff;
    }

    .cta-card {
        border-radius: 2rem;
        padding: 3.5rem 3rem;
        background: linear-gradient(135deg, rgba(31, 143, 87, 0.08), rgba(31, 143, 87, 0.2));
        border: 1px solid rgba(31, 143, 87, 0.16);
        box-shadow: 0 40px 80px rgba(15, 107, 60, 0.1);
    }

    @media (max-width: 992px) {
        .feature-grid {
            margin-top: 2rem;
        }

        .hero-card {
            margin-top: 3rem;
        }
    }

    @media (max-width: 768px) {
        .hero-modern {
            padding: 4.5rem 0 4rem;
        }

        .hero-metrics {
            margin-top: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .articles-stream {
            padding: 2.5rem 1.5rem;
        }
    }
</style>
@endsection

<!-- Hero Section -->
<section class="hero-modern">
    <div class="container position-relative">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                <div class="hero-badge mb-3">
                    <i class="fas fa-leaf"></i>
                    <span>Plateforme de soins connectés & durables</span>
                </div>
                <h1 class="hero-title">Une équipe médicale engagée pour votre bien-être</h1>
                <p class="hero-desc">CareWell réunit médecins, spécialistes et patients autour d'un parcours de soins personnalisés, sécurisé et disponible en permanence.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-pill-success">
                        <i class="fas fa-user-plus me-2"></i>Créer mon espace patient
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-pill-soft">
                        <i class="fas fa-stethoscope me-2"></i>Explorer les services
                    </a>
                </div>

                <div class="hero-metrics">
                    <div class="metric-card">
                        <span class="metric-value">{{ \App\Models\Appointment::count() }}+</span>
                        <span>Rendez-vous orchestrés en toute simplicité</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-value">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'doctor'); })->count() }}+</span>
                        <span>Professionnels de santé vérifiés</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-value">{{ \App\Models\User::count() }}+</span>
                        <span>Patients accompagnés au quotidien</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-card">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <span class="badge-soft-success"><i class="fas fa-bell me-2"></i>Prochain créneau disponible</span>
                        <span class="text-muted small">Temps réel</span>
                    </div>
                    <h4 class="fw-semibold mb-3">Planifiez une consultation</h4>
                    <p class="mb-4">Choisissez un spécialiste, sélectionnez un horaire et laissez notre équipe gérer la suite.</p>
                    <div class="timeline">
                        <div class="timeline-step">
                            <h6 class="fw-semibold mb-1">1. Sélectionnez votre besoin</h6>
                            <p class="mb-0 text-muted">Cardiologie, pédiatrie, suivi en ligne, examens et plus.</p>
                        </div>
                        <div class="timeline-step">
                            <h6 class="fw-semibold mb-1">2. Confirmez votre rendez-vous</h6>
                            <p class="mb-0 text-muted">Recevez une validation instantanée avec rappels automatiques.</p>
                        </div>
                        <div class="timeline-step">
                            <h6 class="fw-semibold mb-1">3. Bénéficiez d'un suivi connecté</h6>
                            <p class="mb-0 text-muted">Télé-consultation, compte-rendu et ordonnance en ligne.</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3">
                        <a href="{{ route('appointments.create') }}" class="btn btn-pill-success w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Réserver maintenant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Features -->
<section class="feature-grid pb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Parcours coordonné</h5>
                    <p class="text-muted mb-0">Reliez médecins traitants, spécialistes et diagnostic sur une même plateforme.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Application tout-en-un</h5>
                    <p class="text-muted mb-0">Gérez vos rendez-vous, documents et téléconsultations où que vous soyez.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-virus"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Sécurité renforcée</h5>
                    <p class="text-muted mb-0">Hébergement certifié, chiffrement des données et conformité RGPD.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Suivi préventif</h5>
                    <p class="text-muted mb-0">Alertes santé, programmes bien-être et coaching personnalisé.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="py-6">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <span class="section-label">Nos expertises</span>
                <h2 class="mt-2 mb-3">Des services médicaux pensés pour chaque étape de votre santé</h2>
                <p class="text-muted mb-0">Consultez les spécialités disponibles et découvrez comment nos équipes vous accompagnent, en cabinet comme à distance.</p>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('services') }}" class="btn btn-pill-soft">Voir tous les services</a>
            </div>
        </div>

        <div class="row g-4 services-showcase">
            @foreach(\App\Models\Service::take(6)->get() as $service)
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    @if($service->photo)
                        <img src="{{ asset('storage/' . $service->photo) }}" alt="{{ $service->name }}" class="card-img-top">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 220px;">
                            <i class="fas fa-stethoscope fa-3x text-success"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="price-badge">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                            <span class="text-muted small">{{ $service->duration ?? '30 min' }}</span>
                        </div>
                        <h5 class="fw-semibold">{{ $service->name }}</h5>
                        <p class="text-muted flex-grow-1">{{ Str::limit($service->description, 120) }}</p>
                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-pill-soft mt-3">En savoir plus</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Knowledge hub -->
<section class="py-6">
    <div class="container">
        <div class="articles-stream">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <span class="section-label">Conseils & prévention</span>
                    <h2 class="mt-2 mb-0">Restez informé avec notre équipe médicale</h2>
                </div>
                <a href="{{ route('articles') }}" class="btn btn-pill-soft mt-4 mt-sm-0">Tous les articles</a>
            </div>
            <div class="row g-4">
                @foreach(\App\Models\Article::take(3)->get() as $article)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        @if($article->photo)
                            <img src="{{ asset('storage/' . $article->photo) }}" alt="{{ $article->title }}" class="card-img-top">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-white" style="height: 220px;">
                                <i class="fas fa-newspaper fa-3x text-success"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-semibold">{{ $article->title }}</h5>
                            <p class="text-muted flex-grow-1">{{ Str::limit($article->content, 150) }}</p>
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ $article->created_at->diffForHumans() }}</small>
                                <a href="{{ route('articles.show', $article->id) }}" class="btn btn-pill-soft btn-sm">Lire plus</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="stats-panel">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <span class="section-label text-white">Impact réel</span>
                <h2 class="mt-3 mb-3">Une communauté médicale qui grandit avec vous</h2>
                <p class="mb-0">Nous accompagnons patients, praticiens et secrétariats médicaux dans la transformation de leurs parcours de soins.</p>
            </div>
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="stat-box text-center">
                            <div class="mb-2"><i class="fas fa-users fa-2x"></i></div>
                            <h3 class="fw-bold">{{ \App\Models\User::count() }}+</h3>
                            <p class="mb-0">Patients connectés</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box text-center">
                            <div class="mb-2"><i class="fas fa-user-md fa-2x"></i></div>
                            <h3 class="fw-bold">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'doctor'); })->count() }}+</h3>
                            <p class="mb-0">Médecins spécialistes</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box text-center">
                            <div class="mb-2"><i class="fas fa-calendar-check fa-2x"></i></div>
                            <h3 class="fw-bold">{{ \App\Models\Appointment::count() }}+</h3>
                            <p class="mb-0">Consultations assurées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to action -->
<section class="cta-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9">
                <div class="cta-card text-center">
                    <span class="section-label">Passez à l'action</span>
                    <h2 class="mt-3 mb-3">Prêt à simplifier votre suivi de santé ?</h2>
                    <p class="text-muted">Rejoignez une plateforme pensée pour la collaboration entre patients, médecins et secrétaires médicaux. Inscription rapide, support dédié et accompagnement humain.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <a href="{{ route('register') }}" class="btn btn-pill-success">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-pill-soft">
                            <i class="fas fa-envelope me-2"></i>Parler à un conseiller
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
