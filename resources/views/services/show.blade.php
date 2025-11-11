@extends('layouts.app')

@section('title', $service->name . ' - CareWell')

@section('content')
@php
    $heroImage = $service->photo
        ? asset('storage/' . $service->photo)
        : asset('images/service.png');

    $highlights = [
        [
            'icon' => 'fa-user-md',
            'title' => 'Experts dédiés',
            'text' => 'Consultations assurées par une équipe médicale qualifiée.'
        ],
        [
            'icon' => 'fa-hand-holding-heart',
            'title' => 'Suivi personnalisé',
            'text' => 'Un accompagnement adapté à votre situation et à vos besoins.'
        ],
        [
            'icon' => 'fa-shield-alt',
            'title' => 'Sécurité des données',
            'text' => 'Vos informations sont protégées et traitées en toute confidentialité.'
        ],
        [
            'icon' => 'fa-calendar-check',
            'title' => 'Flexibilité',
            'text' => 'Des plages de rendez-vous élargies pour s\'adapter à votre agenda.'
        ],
    ];
@endphp

<section class="service-hero" style="background-image: linear-gradient(135deg, rgba(37, 99, 235, 0.88), rgba(30, 64, 175, 0.88)), url('{{ $heroImage }}');">
    <div class="container">
        <nav aria-label="breadcrumb" class="service-breadcrumb mb-4">
            <ol class="breadcrumb breadcrumb-light mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
            </ol>
        </nav>

        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <span class="service-badge">Service de santé CareWell</span>
                <h1 class="service-hero__title">{{ $service->name }}</h1>
                <p class="service-hero__subtitle">{{ $service->description }}</p>

                <div class="service-hero__meta d-flex flex-wrap align-items-center gap-3 mt-4">
                    <span class="price-chip">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                    <div class="service-hero__guarantee">
                        <i class="fas fa-bolt me-2"></i>Confirmation sous 24h
                    </div>
                    <div class="service-hero__guarantee">
                        <i class="fas fa-shield-alt me-2"></i>Plateforme sécurisée
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    @auth
                        <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="btn btn-light btn-lg shadow-sm">
                            <i class="fas fa-calendar-plus me-2"></i>Réserver maintenant
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg shadow-sm">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter pour réserver
                        </a>
                    @endauth
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone-volume me-2"></i>Parler à un expert
                    </a>
                </div>

                <ul class="service-hero__points mt-4">
                    <li><i class="fas fa-check-circle me-2"></i>Soutien médical de bout en bout</li>
                    <li><i class="fas fa-check-circle me-2"></i>Suivi disponible en ligne</li>
                    <li><i class="fas fa-check-circle me-2"></i>Equipe pluridisciplinaire dédiée</li>
                </ul>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="service-hero__summary">
                    <div class="summary-badge">Pourquoi CareWell ?</div>
                    <h3 class="summary-title">Une expérience pensée pour vous</h3>
                    <p class="summary-text">Nous combinons expertise médicale, technologies sécurisées et accompagnement humain pour vous offrir un parcours de soin sans friction.</p>
                    <div class="summary-list">
                        <div class="summary-item">
                            <i class="fas fa-heartbeat"></i>
                            <div>
                                <h4>95% de satisfaction</h4>
                                <p>Retour positif des patients sur la qualité de la prise en charge.</p>
                            </div>
                        </div>
                        <div class="summary-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <h4>Support dédié</h4>
                                <p>Equipe disponible pour vous guider avant, pendant et après votre rendez-vous.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="service-body py-5 py-lg-6">
    <div class="container">
        <div class="row g-4 g-xl-5">
            <div class="col-lg-8">
                <article class="card service-overview-card mb-4">
                    <div class="service-overview-media">
                        @if($service->photo)
                            <img src="{{ asset('storage/' . $service->photo) }}" alt="{{ $service->name }}">
                        @else
                            <div class="service-overview-placeholder">
                                <i class="fas fa-stethoscope"></i>
                                <span>Image à venir</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <span class="service-chip">
                                <i class="fas fa-tag me-2"></i>Investissement santé
                            </span>
                            <span class="service-chip service-chip--muted">
                                <i class="fas fa-clock me-2"></i>Réservation rapide
                            </span>
                        </div>

                        <h2 class="h3 mb-3">Votre parcours de soin</h2>
                        <p class="lead text-muted mb-4">{{ $service->description }}</p>

                        <div class="service-points-grid">
                            <div class="service-point">
                                <div class="service-point__icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <span class="service-point__label">Durée conseillée</span>
                                    <p class="service-point__value mb-0">Consultation standard</p>
                                </div>
                            </div>
                            <div class="service-point">
                                <div class="service-point__icon">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <span class="service-point__label">Professionnels</span>
                                    <p class="service-point__value mb-0">Equipe médicale spécialisée</p>
                                </div>
                            </div>
                            <div class="service-point">
                                <div class="service-point__icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <span class="service-point__label">Sécurité</span>
                                    <p class="service-point__value mb-0">Données protégées et confidentielles</p>
                                </div>
                            </div>
                            <div class="service-point">
                                <div class="service-point__icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <span class="service-point__label">Disponibilité</span>
                                    <p class="service-point__value mb-0">Rendez-vous flexibles et ajustables</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <div class="card service-highlights-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="h4 mb-4">Ce que vous obtenez</h3>
                        <div class="service-highlight-grid">
                            @foreach($highlights as $highlight)
                                <div class="service-highlight">
                                    <div class="service-highlight__icon">
                                        <i class="fas {{ $highlight['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="service-highlight__title">{{ $highlight['title'] }}</h4>
                                        <p class="service-highlight__text mb-0">{{ $highlight['text'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($relatedServices->count() > 0)
                    <div class="card service-related-card mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                                <div>
                                    <h3 class="h4 mb-1">Services complémentaires</h3>
                                    <p class="text-muted mb-0">Découvrez d'autres solutions adaptées à vos besoins.</p>
                                </div>
                                <a href="{{ route('services') }}" class="btn btn-link text-decoration-none text-primary fw-semibold">
                                    Voir tous les services <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>

                            <div class="service-related-grid">
                                @foreach($relatedServices->take(3) as $relatedService)
                                    <a href="{{ route('services.show', $relatedService->id) }}" class="service-related-item card border-0 shadow-sm">
                                        <div class="service-related-media">
                                            @if($relatedService->photo)
                                                <img src="{{ asset('storage/' . $relatedService->photo) }}" alt="{{ $relatedService->name }}">
                                            @else
                                                <div class="service-related-placeholder">
                                                    <i class="fas fa-stethoscope"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h4 class="h6 mb-2">{{ $relatedService->name }}</h4>
                                            <p class="text-muted small mb-3">{{ Str::limit($relatedService->description, 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold">{{ number_format($relatedService->price, 0, ',', ' ') }} FCFA</span>
                                                <span class="text-primary fw-semibold">
                                                    Découvrir <i class="fas fa-arrow-right ms-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="service-sidebar">
                    <div class="service-sidebar__inner sticky-top">
                        <div class="service-booking-card card border-0 shadow-lg mb-4">
                            <div class="card-body p-4 p-lg-5 text-white">
                                <div class="service-booking-card__accent"></div>
                                @auth
                                    <div class="service-booking-card__header mb-4">
                                        <span class="service-booking-chip">Service sélectionné</span>
                                        <h3 class="h4 fw-semibold mb-2 text-white">{{ $service->name }}</h3>
                                        <p class="text-white-50 mb-0">Bénéficiez d'un accompagnement personnalisé à chaque étape.</p>
                                    </div>
                                    <div class="service-booking-card__price mb-4">
                                        {{ number_format($service->price, 0, ',', ' ') }} <span>FCFA</span>
                                    </div>
                                    <ul class="service-booking-list text-white-50 small mb-4">
                                        <li><i class="fas fa-check me-2"></i>Prise en charge prioritaire</li>
                                        <li><i class="fas fa-check me-2"></i>Accès à l'espace patient</li>
                                        <li><i class="fas fa-check me-2"></i>Suivi post-consultation inclus</li>
                                    </ul>
                                    <div class="d-grid gap-3">
                                        <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="btn btn-light btn-lg">
                                            <i class="fas fa-calendar-plus me-2"></i>Planifier mon rendez-vous
                                        </a>
                                        <a href="{{ route('contact') }}" class="btn btn-outline-light">
                                            <i class="fas fa-comments me-2"></i>Parler à un conseiller
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="service-booking-chip mb-3">Espace sécurisé</div>
                                        <h3 class="h4 text-white">Connectez-vous pour réserver</h3>
                                        <p class="text-white-50 mb-4">Accédez à la prise de rendez-vous, au suivi de vos consultations et à vos documents médicaux.</p>
                                        <div class="d-grid gap-3">
                                            <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                            </a>
                                            <a href="{{ route('register') }}" class="btn btn-outline-light">
                                                <i class="fas fa-user-plus me-2"></i>Créer un compte
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>

                        <div class="card service-info-card border-0 shadow-sm mb-4">
                            <div class="card-body p-4 p-lg-5">
                                <h4 class="h6 text-uppercase text-muted mb-4">Informations pratiques</h4>
                                <div class="service-info-item">
                                    <div class="service-info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <span class="service-info-label">Téléphone</span>
                                        <p class="mb-0 fw-semibold">+33 1 23 45 67 89</p>
                                        <small class="text-muted">Lun - Sam : 8h - 20h</small>
                                    </div>
                                </div>
                                <div class="service-info-item">
                                    <div class="service-info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <span class="service-info-label">Email</span>
                                        <p class="mb-0 fw-semibold">contact@carewell.fr</p>
                                        <small class="text-muted">Réponse sous 12h ouvrées</small>
                                    </div>
                                </div>
                                <div class="service-info-item">
                                    <div class="service-info-icon">
                                        <i class="fas fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <span class="service-info-label">Adresse</span>
                                        <p class="mb-0 fw-semibold">123 Rue de la Santé, 75001 Paris</p>
                                        <small class="text-muted">Consultations sur place et à distance</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card service-help-card border-0 shadow-sm">
                            <div class="card-body p-4 p-lg-5">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="service-help-icon">
                                        <i class="fas fa-life-ring"></i>
                                    </div>
                                    <div>
                                        <h4 class="h6 mb-1">Besoin d'assistance ?</h4>
                                        <p class="text-muted mb-3">Notre équipe est disponible pour répondre à vos questions et vous orienter vers le bon spécialiste.</p>
                                        <a href="mailto:contact@carewell.fr" class="btn btn-link px-0 text-primary fw-semibold">
                                            Écrire au support <i class="fas fa-arrow-up-right-from-square ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="service-faq py-5 py-lg-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="service-section-badge">FAQ</span>
                    <h2 class="h2 fw-semibold">Questions fréquentes</h2>
                    <p class="text-muted">Préparez votre rendez-vous en toute sérénité grâce à ces réponses rapides.</p>
                </div>

                <div class="accordion accordion-flush shadow-sm" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                Comment se déroule la consultation ?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                La consultation se déroule en plusieurs étapes : accueil, échange avec le professionnel de santé, examen clinique si nécessaire, diagnostic, puis plan d'action personnalisé.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                Puis-je annuler ou modifier mon rendez-vous ?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Oui, vous pouvez annuler ou déplacer votre rendez-vous jusqu'à 24h avant l'horaire prévu. Contactez-nous par téléphone ou via votre espace patient pour effectuer la modification.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                Que dois-je préparer avant la consultation ?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Munissez-vous de votre carte vitale, de vos documents médicaux récents (ordonnances, examens) et de vos questions prioritaires afin d'optimiser l'échange avec le praticien.
                            </div>
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
    .service-hero {
        position: relative;
        padding: 5.5rem 0 4rem;
        border-bottom-left-radius: 3rem;
        border-bottom-right-radius: 3rem;
        background-size: cover;
        background-position: center;
        color: #fff;
        overflow: hidden;
    }

    .service-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.25), transparent 55%);
        opacity: 0.4;
        pointer-events: none;
    }

    .service-hero .container {
        position: relative;
        z-index: 2;
    }

    .service-breadcrumb .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-light .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }

    .breadcrumb-light .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
    }

    .breadcrumb-light .breadcrumb-item a:hover {
        color: #fff;
    }

    .breadcrumb-light .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }

    .service-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        border-radius: 999px;
        padding: 0.3rem 0.95rem;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .service-hero__title {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .service-hero__subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.85);
        max-width: 640px;
    }

    .price-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 999px;
        padding: 0.65rem 1.35rem;
        font-weight: 600;
        font-size: 1.05rem;
    }

    .service-hero__guarantee {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .service-hero__points {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 0.75rem;
    }

    .service-hero__points li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 0.85rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .service-hero__summary {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 1.75rem;
        padding: 2.25rem;
        backdrop-filter: blur(16px);
        color: #fff;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .service-hero__summary::after {
        content: '';
        position: absolute;
        inset: auto -40% -40% auto;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.35), transparent 60%);
        opacity: 0.45;
        pointer-events: none;
    }

    .summary-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.8rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .summary-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .summary-text {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2rem;
    }

    .summary-list {
        display: grid;
        gap: 1.5rem;
    }

    .summary-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .summary-item i {
        font-size: 1.5rem;
    }

    .summary-item h4 {
        font-size: 1.05rem;
        margin-bottom: 0.25rem;
    }

    .summary-item p {
        margin-bottom: 0;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
    }

    .service-body {
        margin-top: -3.5rem;
        position: relative;
        z-index: 5;
    }

    .service-overview-card,
    .service-highlights-card,
    .service-related-card {
        border: none;
        border-radius: 1.75rem;
        overflow: hidden;
        box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.35);
    }

    .service-overview-media {
        width: 100%;
        max-height: 360px;
        overflow: hidden;
    }

    .service-overview-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-overview-placeholder {
        min-height: 280px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(30, 64, 175, 0.08));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        gap: 0.75rem;
        font-weight: 600;
    }

    .service-overview-placeholder i {
        font-size: 2.5rem;
    }

    .service-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .service-chip--muted {
        background: rgba(148, 163, 184, 0.18);
        color: var(--text-color);
    }

    .service-points-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .service-point {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        border-radius: 1rem;
        background: rgba(148, 163, 184, 0.12);
    }

    .service-point__icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .service-point__label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--primary-color);
        font-weight: 600;
    }

    .service-point__value {
        font-weight: 600;
        color: var(--dark-color);
    }

    .service-highlight-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .service-highlight {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        padding: 1.35rem;
        border-radius: 1.2rem;
        background: rgba(37, 99, 235, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .service-highlight:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -18px rgba(37, 99, 235, 0.45);
    }

    .service-highlight__icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(37, 99, 235, 0.18);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .service-highlight__title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .service-highlight__text {
        font-size: 0.95rem;
        color: var(--text-color);
    }

    .service-related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.5rem;
    }

    .service-related-item {
        overflow: hidden;
        border-radius: 1.25rem;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        color: inherit;
    }

    .service-related-item:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -24px rgba(37, 99, 235, 0.4);
        color: inherit;
    }

    .service-related-media {
        width: 100%;
        height: 180px;
        overflow: hidden;
    }

    .service-related-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-related-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(37, 99, 235, 0.08);
        color: var(--primary-color);
        font-size: 2rem;
    }

    .service-sidebar__inner {
        top: 90px;
    }

    .service-booking-card {
        background: linear-gradient(145deg, var(--primary-color), var(--secondary-color));
        border-radius: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .service-booking-card__accent {
        position: absolute;
        inset: -50% auto auto -30%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.25), transparent 60%);
        opacity: 0.4;
        pointer-events: none;
    }

    .service-booking-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.75rem;
    }

    .service-booking-card__price {
        font-size: 2.3rem;
        font-weight: 700;
    }

    .service-booking-card__price span {
        font-size: 1rem;
        font-weight: 600;
        margin-left: 0.35rem;
    }

    .service-booking-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.65rem;
    }

    .service-booking-list li {
        display: flex;
        align-items: center;
        font-size: 0.95rem;
    }

    .service-info-card {
        border-radius: 1.75rem;
    }

    .service-info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding-bottom: 1.35rem;
    }

    .service-info-item:last-child {
        padding-bottom: 0;
    }

    .service-info-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
    }

    .service-info-label {
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .service-help-card {
        border-radius: 1.75rem;
    }

    .service-help-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .service-faq {
        background: linear-gradient(180deg, rgba(37, 99, 235, 0.06), transparent 25%);
    }

    .service-section-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        border-radius: 999px;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }

    .accordion-flush .accordion-item {
        border-radius: 1.25rem;
        overflow: hidden;
        margin-bottom: 1rem;
        border: 1px solid rgba(37, 99, 235, 0.12);
    }

    .accordion-button {
        font-weight: 600;
        padding: 1.25rem 1.5rem;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(37, 99, 235, 0.4);
    }

    .accordion-body {
        padding: 0 1.5rem 1.5rem;
        color: var(--text-color);
        line-height: 1.7;
    }

    @media (max-width: 991.98px) {
        .service-hero {
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
        }

        .service-hero__summary {
            margin-top: 2rem;
        }

        .service-body {
            margin-top: -2.5rem;
        }

        .service-sidebar__inner {
            position: static;
        }
    }

    @media (max-width: 575.98px) {
        .service-hero {
            padding: 4.5rem 0 3.5rem;
        }

        .service-hero__meta {
            gap: 0.75rem;
        }

        .service-hero__points {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .service-overview-card,
        .service-highlights-card,
        .service-related-card,
        .service-info-card,
        .service-help-card,
        .service-booking-card {
            border-radius: 1.25rem;
        }
    }
</style>
@endsection
