@extends('layouts.app')

@section('title', 'Nos Services - CareWell')

@section('content')
@php
    $totalServices = method_exists($services, 'total') ? $services->total() : $services->count();
    $displayedServices = $services->count();
    $displayedLabel = $displayedServices > 1 ? 'services affichés' : 'service affiché';
    $resultLabel = $totalServices > 1 ? 'résultats' : 'résultat';
@endphp

<!-- Hero Section -->
<section class="services-hero position-relative overflow-hidden text-white">
    <div class="hero-background" style="background-image: url('{{ asset('images/service.png') }}');"></div>
    <span class="hero-gradient"></span>
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="hero-badge mb-4">
                    <span><i class="fas fa-heartbeat me-2"></i>Expertise médicale certifiée</span>
                </div>
                <h1 class="hero-title">Des soins modernes pour chaque besoin de santé</h1>
                <p class="hero-subtitle">Explorez notre sélection de services spécialisés, pensés pour accompagner votre parcours de soins et simplifier la prise de rendez-vous.</p>
                <div class="hero-actions">
                    <a href="#services-list" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-down me-2"></i>Voir les services
                    </a>
                    <a href="{{ route('appointments.create') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Prendre rendez-vous
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="stats-card">
                    <div class="stats-item">
                        <span class="stats-value">{{ number_format($totalServices, 0, ',', ' ') }}+</span>
                        <span class="stats-label">Services disponibles</span>
                    </div>
                    <div class="stats-divider"></div>
                    <div class="stats-item">
                        <span class="stats-value">24h/7</span>
                        <span class="stats-label">Support & suivi patient</span>
                    </div>
                    <div class="stats-divider"></div>
                    <div class="stats-item">
                        <span class="stats-value">100%</span>
                        <span class="stats-label">Professionnels certifiés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="service-filter-card shadow-lg">
                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-4 mb-4">
                        <div class="filter-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div>
                            <h2 class="filter-title">Affinez votre recherche</h2>
                            <p class="filter-subtitle mb-0">Sélectionnez un service, comparez les tarifs et réservez en quelques clics.</p>
                        </div>
                        @if(request()->hasAny(['search', 'sort']) && (request('search') || request('sort')))
                            <a href="{{ route('services') }}" class="btn btn-reset ms-lg-auto">
                                <i class="fas fa-undo me-2"></i>Réinitialiser
                            </a>
                        @endif
                    </div>

                    <form id="services-filter" action="{{ route('services') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                            <label for="service-search" class="form-label">Rechercher</label>
                            <div class="input-icon">
                                <i class="fas fa-search"></i>
                                <input id="service-search" type="text" name="search" class="form-control" placeholder="Nom, spécialité, mot-clé..." value="{{ request('search') }}">
                            </div>
                            </div>
                            <div class="col-md-3">
                            <label for="service-sort" class="form-label">Trier par</label>
                            <select id="service-sort" name="sort" class="form-select">
                                <option value="">Pertinence</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Prix décroissant</option>
                                </select>
                            </div>
                        <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Appliquer
                                </button>
                            </div>
                        </form>

                    <div class="filter-pills mt-4">
                        <span class="pill-label">Suggestions :</span>
                        <div class="pill-group">
                            <a class="pill" href="{{ route('services', ['search' => 'Consultation']) }}">Consultation</a>
                            <a class="pill" href="{{ route('services', ['search' => 'Cardiologie']) }}">Cardiologie</a>
                            <a class="pill" href="{{ route('services', ['search' => 'Urgence']) }}">Urgence</a>
                            <a class="pill" href="{{ route('services', ['search' => 'Diagnostic']) }}">Diagnostic</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section id="services-list" class="py-5 position-relative">
    <div class="bg-gradient-split"></div>
    <div class="container position-relative">
        @if($services->count() > 0)
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
                <div>
                    <h2 class="section-heading">{{ $displayedServices }} {{ $displayedLabel }}</h2>
                    @if(request('search'))
                        <p class="section-description mb-0">Résultats pour « {{ request('search') }} » — {{ number_format($totalServices, 0, ',', ' ') }} {{ $resultLabel }} correspondants.</p>
                    @else
                        <p class="section-description mb-0">Sélectionnez l'offre médicale qui correspond à vos besoins et prenez rendez-vous en ligne.</p>
                    @endif
                </div>
                <div class="text-muted fst-italic small">
                    <i class="fas fa-info-circle me-2"></i>Tarifs indicatifs susceptibles d'évoluer selon le niveau de prise en charge.
                </div>
            </div>

            <div class="row g-4">
                @foreach($services as $service)
                    @php
                        $palette = ['gradient-blue', 'gradient-green', 'gradient-purple'];
                        $gradientClass = $palette[$loop->index % count($palette)];
                    @endphp
                    <div class="col-xl-4 col-md-6">
                        <article class="service-card h-100 {{ $gradientClass }}">
                            <div class="service-card__media">
                        @if($service->photo)
                                    <img src="{{ asset('storage/' . $service->photo) }}" alt="{{ $service->name }}">
                        @else
                                    <div class="service-card__placeholder">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                @endif
                                <span class="price-tag">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="service-card__body">
                                <div class="service-card__meta">
                                    <span class="meta-item"><i class="fas fa-clock me-2"></i>Consultation</span>
                                    <span class="meta-item"><i class="fas fa-shield-heart me-2"></i>Équipe dédiée</span>
                                </div>
                                <h3 class="service-card__title">{{ $service->name }}</h3>
                                <p class="service-card__excerpt">{{ Str::limit($service->description, 140) }}</p>
                                <ul class="service-card__features">
                                    <li><i class="fas fa-check"></i>Prise en charge rapide</li>
                                    <li><i class="fas fa-check"></i>Assistance patient personnalisée</li>
                                    <li><i class="fas fa-check"></i>Suivi sécurisé de vos données</li>
                                </ul>
                            </div>
                            <div class="service-card__footer">
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-soft-primary">
                                    <i class="fas fa-circle-info me-2"></i>Détails du service
                                    </a>
                                    @auth
                                        <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="btn btn-primary">
                                            <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="fas fa-user-lock me-2"></i>Se connecter
                                        </a>
                                    @endauth
                            </div>
                        </article>
                </div>
                @endforeach
            </div>

            @if($services->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $services->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif

        @else
            <div class="empty-state text-center py-5">
                <div class="empty-state__icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-state__title">Aucun service trouvé</h3>
                <p class="empty-state__subtitle">Nous n'avons trouvé aucun service correspondant à votre recherche. Ajustez vos critères ou parcourez l'ensemble du catalogue.</p>
                <a href="{{ route('services') }}" class="btn btn-primary">
                    <i class="fas fa-undo me-2"></i>Réinitialiser la recherche
                </a>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section text-white py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="cta-label mb-3">Accompagnement sur-mesure</div>
                <h2 class="cta-title">Besoin d'un service personnalisé ou d'un devis détaillé ?</h2>
                <p class="cta-subtitle">Notre équipe vous répond sous 24 heures pour organiser une consultation, préparer un diagnostic ou planifier une prise en charge spécifique.</p>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column flex-md-row flex-lg-column justify-content-lg-end gap-3">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg flex-grow-1">
                        <i class="fas fa-envelope-open-text me-2"></i>Nous contacter
                    </a>
                    <a href="{{ route('appointments.create') }}" class="btn btn-outline-light btn-lg flex-grow-1">
                        <i class="fas fa-video me-2"></i>Fixer un rendez-vous
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .services-hero {
        padding: 6rem 0 5rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(30, 64, 175, 0.92));
    }

    .services-hero .hero-background {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.35;
        filter: blur(2px);
        transform: scale(1.05);
    }

    .services-hero .hero-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.6) 100%);
        mix-blend-mode: multiply;
    }

    .hero-badge span {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        font-weight: 500;
        letter-spacing: 0.02em;
    }

    .hero-title {
        font-size: clamp(2.4rem, 4vw, 3.2rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        max-width: 580px;
        opacity: 0.85;
        margin-bottom: 2rem;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hero-actions .btn-lg {
        padding: 0.9rem 1.8rem;
        border-radius: 0.75rem;
        font-weight: 600;
    }

    .stats-card {
        background: rgba(15, 23, 42, 0.55);
        border-radius: 1.5rem;
        padding: 2rem;
        backdrop-filter: blur(8px);
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        gap: 1rem;
    }

    .stats-item {
        text-align: center;
    }

    .stats-value {
        display: block;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .stats-label {
        font-size: 0.95rem;
        opacity: 0.75;
    }

    .stats-divider {
        width: 1px;
        height: 60px;
        background: rgba(255, 255, 255, 0.25);
        justify-self: center;
    }

    .service-filter-card {
        position: relative;
        border-radius: 1.5rem;
        padding: 2.5rem;
        background: linear-gradient(140deg, rgba(255, 255, 255, 0.95), rgba(226, 232, 240, 0.92));
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    .service-filter-card::after {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.25), rgba(59, 130, 246, 0.05));
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }

    .filter-icon {
        width: 64px;
        height: 64px;
        border-radius: 1rem;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        display: grid;
        place-items: center;
        font-size: 1.5rem;
    }

    .filter-title {
        font-size: 1.45rem;
        font-weight: 600;
        margin-bottom: 0.35rem;
    }

    .filter-subtitle {
        color: var(--text-color);
        opacity: 0.8;
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        background: rgba(37, 99, 235, 0.08);
        border-radius: 999px;
        padding: 0.65rem 1.4rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .btn-reset:hover {
        background: rgba(37, 99, 235, 0.16);
        color: var(--secondary-color);
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: #64748b;
    }

    .input-icon .form-control {
        padding-left: 2.75rem;
        border-radius: 0.9rem;
        border-color: rgba(148, 163, 184, 0.4);
        height: 54px;
    }

    .form-select {
        border-radius: 0.9rem;
        border-color: rgba(148, 163, 184, 0.4);
        height: 54px;
    }

    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem 1rem;
        align-items: center;
    }

    .pill-label {
        font-weight: 600;
        color: #475569;
    }

    .pill-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 1.1rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.08);
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .pill:hover {
        background: rgba(37, 99, 235, 0.16);
        color: var(--secondary-color);
    }

    .bg-gradient-split {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), rgba(59, 130, 246, 0));
        pointer-events: none;
    }

    .section-heading {
        font-size: 1.85rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .section-description {
        color: #475569;
        max-width: 620px;
    }

    .service-card {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.4);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .service-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: var(--card-gradient, linear-gradient(135deg, rgba(37, 99, 235, 0.4), rgba(59, 130, 246, 0.15)));
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        transition: opacity 0.35s ease;
        opacity: 0.65;
    }

    .service-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px -30px rgba(37, 99, 235, 0.45);
    }

    .service-card:hover::before {
        opacity: 1;
    }

    .service-card__media {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .service-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-card:hover .service-card__media img {
        transform: scale(1.05);
    }

    .service-card__placeholder {
        height: 100%;
        display: grid;
        place-items: center;
        background: rgba(148, 163, 184, 0.2);
        color: var(--primary-color);
        font-size: 2.5rem;
    }

    .price-tag {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: rgba(15, 23, 42, 0.85);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);
    }

    .service-card__body {
        padding: 1.9rem 1.9rem 1.5rem;
        flex-grow: 1;
    }

    .service-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .service-card__title {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .service-card__excerpt {
        color: #475569;
        margin-bottom: 1.25rem;
        min-height: 72px;
    }

    .service-card__features {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.45rem;
    }

    .service-card__features li {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: #1e293b;
        font-weight: 500;
    }

    .service-card__features i {
        color: var(--success-color);
    }

    .service-card__footer {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 0 1.9rem 1.9rem;
    }

    .btn-soft-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: rgba(37, 99, 235, 0.12);
        color: var(--primary-color);
        font-weight: 600;
        border-radius: 0.85rem;
        padding: 0.75rem 1.35rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-soft-primary:hover {
        background: rgba(37, 99, 235, 0.22);
        color: var(--secondary-color);
    }

    .btn-primary {
        border-radius: 0.85rem;
    }

    .gradient-blue {
        --card-gradient: linear-gradient(135deg, rgba(37, 99, 235, 0.45), rgba(59, 130, 246, 0.15));
    }

    .gradient-green {
        --card-gradient: linear-gradient(135deg, rgba(16, 185, 129, 0.45), rgba(34, 197, 94, 0.15));
    }

    .gradient-purple {
        --card-gradient: linear-gradient(135deg, rgba(124, 58, 237, 0.45), rgba(168, 85, 247, 0.12));
    }

    .empty-state {
        max-width: 540px;
        margin: 0 auto;
    }

    .empty-state__icon {
        width: 96px;
        height: 96px;
        margin: 0 auto 1.5rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
        display: grid;
        place-items: center;
        font-size: 2rem;
    }

    .empty-state__title {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .empty-state__subtitle {
        color: #64748b;
        margin-bottom: 2rem;
    }

    .cta-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(125deg, rgba(30, 64, 175, 0.95), rgba(37, 99, 235, 0.9));
        border-radius: 2.5rem 2.5rem 0 0;
        margin-top: 4rem;
    }

    .cta-section::before {
        content: "";
        position: absolute;
        top: -30%;
        left: -10%;
        width: 60%;
        height: 160%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.4), rgba(59, 130, 246, 0));
        opacity: 0.7;
    }

    .cta-label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1.1rem;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .cta-title {
        font-size: clamp(1.9rem, 3.2vw, 2.6rem);
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.05rem;
        opacity: 0.85;
        max-width: 620px;
    }

    .cta-section .btn-lg {
        border-radius: 1rem;
        font-weight: 600;
    }

    .pagination {
        margin-bottom: 0;
        gap: 0.6rem;
    }

    .pagination .page-item {
        border-radius: 999px;
        overflow: hidden;
    }

    .pagination .page-link {
        border-radius: 999px !important;
        color: var(--primary-color);
        border: 1px solid rgba(37, 99, 235, 0.25);
        padding: 0.55rem 1rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        color: #fff;
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .pagination .page-item.active .page-link {
        color: #fff;
        background: var(--secondary-color);
        border-color: transparent;
    }

    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
        border-color: rgba(148, 163, 184, 0.3);
    }

    @media (max-width: 992px) {
        .hero-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-card {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-divider:nth-of-type(3) {
            display: none;
        }

        .service-filter-card {
            padding: 2rem;
        }

        .cta-section {
            border-radius: 2rem 2rem 0 0;
        }

        .service-card__footer {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .services-hero {
            padding: 4.5rem 0 4rem;
        }

        .stats-card {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .stats-divider {
            display: none;
        }

        .filter-pills {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-heading {
            font-size: 1.6rem;
        }

        .service-card__body,
        .service-card__footer {
            padding: 1.6rem;
        }

        .cta-section {
            text-align: center;
        }

        .cta-section .btn-lg {
            width: 100%;
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
