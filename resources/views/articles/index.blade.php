@extends('layouts.app')

@section('title', 'Articles de Santé - CareWell')

@section('content')
@php
    $categoriesConfig = [
        'general' => [
            'label' => 'Santé générale',
            'description' => 'Conseils et informations sur la santé au quotidien',
            'icon' => 'fa-heartbeat',
            'theme' => 'primary',
        ],
        'prevention' => [
            'label' => 'Prévention',
            'description' => 'Adoptez les bons réflexes pour anticiper les risques',
            'icon' => 'fa-shield-alt',
            'theme' => 'success',
        ],
        'nutrition' => [
            'label' => 'Nutrition',
            'description' => 'Alimentation équilibrée et recettes bien-être',
            'icon' => 'fa-apple-alt',
            'theme' => 'warning',
        ],
        'fitness' => [
            'label' => 'Fitness',
            'description' => 'Exercices et mouvements pour rester actif au quotidien',
            'icon' => 'fa-dumbbell',
            'theme' => 'info',
        ],
    ];

    $articleCount = method_exists($articles, 'total') ? $articles->total() : $articles->count();
@endphp

<section class="article-hero position-relative overflow-hidden text-white">
    <div class="hero-background" style="background-image: url('{{ asset('images/banner1.png') }}');"></div>
    <span class="hero-gradient"></span>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="article-hero-badge">
                    <i class="fas fa-newspaper me-2"></i>Magazine CareWell
                </span>
                <h1 class="article-hero-title">Inspirez votre routine santé au quotidien</h1>
                <p class="article-hero-description">
                    Découvrez des contenus rédigés par nos spécialistes pour mieux comprendre, prévenir et prendre soin de votre santé.
                </p>

                <div class="article-stats">
                    <div class="article-metric">
                        <span class="metric-value">{{ $articleCount }}</span>
                        <span class="metric-label">Articles publiés</span>
                    </div>
                    <div class="article-divider"></div>
                    <div class="article-metric">
                        <span class="metric-value">{{ count($categoriesConfig) }}</span>
                        <span class="metric-label">Thématiques clés</span>
                    </div>
                    <div class="article-divider"></div>
                    <div class="article-metric">
                        @if($featuredArticle)
                            <span class="metric-value">{{ $featuredArticle->created_at->format('d/m') }}</span>
                            <span class="metric-label">Dernière mise en ligne</span>
                        @else
                            <span class="metric-value">100%</span>
                            <span class="metric-label">Contenus vérifiés</span>
                        @endif
                    </div>
                </div>

                <div class="hero-actions mt-4">
                    <a href="#articles-list" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-down me-2"></i>Explorer les articles
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-heartbeat me-2"></i>Découvrir nos services
                    </a>
                </div>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="article-hero-card">
                    <h5 class="mb-3">Thématiques populaires</h5>
                    <p class="text-white-50 mb-4">Parcourez nos sélections d’articles selon vos besoins et objectifs santé.</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categoriesConfig as $slug => $category)
                            <a href="{{ route('articles', ['category' => $slug]) }}" class="article-hero-pill {{ request('category') === $slug ? 'active' : '' }}">
                                <i class="fas {{ $category['icon'] }} me-2"></i>{{ $category['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="article-filter-card shadow-lg">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-4 mb-4">
                <div class="filter-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h2 class="filter-title">Affinez votre lecture</h2>
                    <p class="filter-subtitle mb-0">Trouvez un conseil, un symptôme ou un thème de santé en un clin d'œil.</p>
                </div>
                @if(request()->hasAny(['search', 'category']) && (request('search') || request('category')))
                    <a href="{{ route('articles') }}" class="btn btn-reset ms-lg-auto">
                        <i class="fas fa-undo me-2"></i>Réinitialiser
                    </a>
                @endif
            </div>

            <form action="{{ route('articles') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="article-search" class="form-label">Rechercher</label>
                    <div class="input-icon">
                        <i class="fas fa-search"></i>
                        <input id="article-search" type="text" name="search" class="form-control" placeholder="Article, symptôme, conseil..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="article-category" class="form-label">Catégorie</label>
                    <select id="article-category" name="category" class="form-select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categoriesConfig as $slug => $category)
                            <option value="{{ $slug }}" {{ request('category') === $slug ? 'selected' : '' }}>{{ $category['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Appliquer
                    </button>
                </div>

                @if(request()->hasAny(['search', 'category']) && (request('search') || request('category')))
                    <div class="col-12">
                        <div class="article-active-filters">
                            <span class="pill-label">Filtres actifs :</span>
                            <div class="pill-group">
                                @if(request('search'))
                                    <span class="pill active"><i class="fas fa-search me-2"></i>{{ request('search') }}</span>
                                @endif
                                @if(request('category') && isset($categoriesConfig[request('category')]))
                                    <span class="pill active"><i class="fas fa-tag me-2"></i>{{ $categoriesConfig[request('category')]['label'] }}</span>
                                @endif
                            </div>
                            <a href="{{ route('articles') }}" class="article-reset-filter">
                                Réinitialiser les filtres
                            </a>
                        </div>
                    </div>
                @endif
            </form>

            <div class="filter-pills mt-4">
                <span class="pill-label">Thématiques populaires :</span>
                <div class="pill-group">
                    @foreach(array_slice($categoriesConfig, 0, 4, true) as $slug => $category)
                        <a class="pill {{ request('category') === $slug ? 'active' : '' }}" href="{{ route('articles', ['category' => $slug]) }}">
                            {{ $category['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@if($featuredArticle)
<section class="article-featured py-5">
    <div class="container">
        <div class="section-heading text-center mb-5">
            <span class="section-eyebrow">Article à la une</span>
            <h2 class="section-title">{{ $featuredArticle->title }}</h2>
            <p class="section-subtitle">Un condensé d’expertise et de conseils pratiques sélectionnés par notre équipe.</p>
        </div>

        <div class="featured-article-card">
            <div class="row g-0 align-items-center">
                <div class="col-lg-5">
                    <div class="featured-article-media">
                        @if($featuredArticle->photo)
                            <img src="{{ asset('storage/' . $featuredArticle->photo) }}" alt="{{ $featuredArticle->title }}" class="img-fluid">
                        @else
                            <div class="featured-article-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="featured-article-content">
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                            <span class="badge rounded-pill bg-white text-primary">
                                <i class="fas fa-star me-2"></i>Article du moment
                            </span>
                            <span class="featured-article-date">
                                <i class="fas fa-clock me-2"></i>{{ $featuredArticle->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <p class="featured-article-excerpt">{{ Str::limit(strip_tags($featuredArticle->content ?? ''), 240) }}</p>

                        @if($featuredArticle->symptoms)
                            <div class="featured-article-meta">
                                <strong class="d-block mb-1">
                                    <i class="fas fa-stethoscope me-2"></i>Symptômes surveillés
                                </strong>
                                <p class="mb-0">{{ $featuredArticle->symptoms }}</p>
                            </div>
                        @endif

                        @if($featuredArticle->advices)
                            <div class="featured-article-advice">
                                <span class="badge rounded-pill bg-primary-subtle text-primary">
                                    <i class="fas fa-lightbulb me-2"></i>Conseil clé
                                </span>
                                <p class="mb-0">{{ Str::limit(strip_tags($featuredArticle->advices), 160) }}</p>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('articles.show', $featuredArticle->id) }}" class="btn btn-primary btn-lg">
                                Lire l'article complet
                            </a>
                            <a href="{{ route('articles') }}" class="btn btn-outline-light btn-lg">
                                Découvrir d'autres thèmes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section id="articles-list" class="article-grid py-5 position-relative">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <span class="section-eyebrow">Articles récents</span>
                <h2 class="section-title">Nos dernières publications</h2>
            </div>
            <div class="article-view-options d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="text-muted small">Triés par date de publication</span>
            </div>
        </div>

        @if($articles->count() > 0)
            @php
                $palette = ['gradient-blue', 'gradient-green', 'gradient-purple'];
            @endphp
            <div class="row g-4">
                @foreach($articles as $article)
                    @php
                        $gradientClass = $palette[$loop->index % count($palette)];
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <article class="article-card h-100 {{ $gradientClass }}">
                            <div class="article-card-media">
                                @if($article->photo)
                                    <img src="{{ asset('storage/' . $article->photo) }}" alt="{{ $article->title }}">
                                @else
                                    <div class="article-card-placeholder">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="article-card-body">
                                <div class="article-card-meta">
                                    <span>
                                        <i class="fas fa-calendar-day me-2"></i>{{ $article->created_at->format('d M Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock me-2"></i>{{ $article->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h3 class="article-card-title">{{ $article->title }}</h3>
                                <p class="article-card-excerpt">{{ Str::limit(strip_tags($article->content ?? ''), 140) }}</p>

                                @if($article->symptoms)
                                    <div class="article-card-tagline">
                                        <i class="fas fa-stethoscope me-2"></i>
                                        <span>{{ Str::limit($article->symptoms, 80) }}</span>
                                    </div>
                                @endif

                                @if($article->advices)
                                    <div class="article-card-advice">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        <span>{{ Str::limit(strip_tags($article->advices), 80) }}</span>
                                    </div>
                                @endif

                                <div class="article-card-footer">
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-soft-primary">
                                        <i class="fas fa-book-open me-2"></i>Lire l'article
                                    </a>
                                    <a href="{{ route('services') }}" class="btn btn-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            @if($articles->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $articles->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        @else
            <div class="article-empty-state text-center py-5">
                <div class="article-empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Aucun article trouvé</h3>
                <p class="text-muted">Aucun article ne correspond à votre recherche. Essayez de modifier vos critères ou explorez nos catégories.</p>
                <a href="{{ route('articles') }}" class="btn btn-primary btn-lg">
                    Réinitialiser la recherche
                </a>
            </div>
        @endif
    </div>
</section>

<section class="article-newsletter py-5">
    <div class="container">
        <div class="article-newsletter-card">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h2 class="text-white mb-3">Restez informé de nos actualités</h2>
                    <p class="text-white-50 mb-0">Recevez nos derniers articles, conseils santé et programmes bien-être directement dans votre boîte mail.</p>
                </div>
                <div class="col-lg-5">
                    <form class="article-newsletter-form">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" placeholder="Votre adresse email" required>
                            <button type="submit" class="btn btn-light">S'abonner</button>
                        </div>
                        <small class="text-white-50 d-block mt-2">
                            <i class="fas fa-shield-check me-2"></i>Nous respectons votre vie privée. Désabonnement en 1 clic.
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="article-categories py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Explorer par thématique</span>
            <h2 class="section-title">Choisissez votre prochaine lecture</h2>
            <p class="section-subtitle">Des dossiers complets et des conseils pratiques pour chaque moment de votre parcours santé.</p>
        </div>

        <div class="row g-4">
            @foreach($categoriesConfig as $slug => $category)
                <div class="col-lg-3 col-md-6">
                    <div class="category-card h-100">
                        <div class="category-icon text-{{ $category['theme'] }}">
                            <i class="fas {{ $category['icon'] }}"></i>
                        </div>
                        <h3>{{ $category['label'] }}</h3>
                        <p>{{ $category['description'] }}</p>
                        <a href="{{ route('articles', ['category' => $slug]) }}" class="category-link">
                            Explorer la catégorie
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .article-hero {
        padding: 6rem 0 5rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(30, 64, 175, 0.9));
    }

    .article-hero .hero-background {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.35;
        filter: blur(2px);
        transform: scale(1.05);
    }

    .article-hero .hero-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.65) 100%);
        mix-blend-mode: multiply;
    }

    .article-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1.35rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        margin-bottom: 1.5rem;
    }

    .article-hero-title {
        font-size: clamp(2.4rem, 4vw, 3.15rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.5rem;
    }

    .article-hero-description {
        font-size: 1.1rem;
        max-width: 620px;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .article-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-radius: 1.5rem;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 520px;
    }

    .article-metric {
        text-align: center;
    }

    .article-divider {
        width: 1px;
        background: rgba(255, 255, 255, 0.25);
        margin: 0 auto;
    }

    .metric-value {
        display: block;
        font-size: 1.9rem;
        font-weight: 700;
    }

    .metric-label {
        font-size: 0.95rem;
        opacity: 0.75;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hero-actions .btn-lg {
        padding: 0.9rem 1.9rem;
        border-radius: 0.85rem;
        font-weight: 600;
    }

    .article-hero-card {
        background: rgba(15, 23, 42, 0.55);
        border-radius: 1.75rem;
        padding: 2.25rem;
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
    }

    .article-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.2rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .article-hero-pill:hover,
    .article-hero-pill.active {
        background: rgba(255, 255, 255, 0.22);
        color: #fff;
    }

    .article-filter-card {
        position: relative;
        margin-top: 3.5rem;
        border-radius: 1.75rem;
        padding: 2.5rem;
        background: linear-gradient(140deg, rgba(255, 255, 255, 0.95), rgba(226, 232, 240, 0.9));
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    .article-filter-card::after {
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
        color: var(--dark-color);
    }

    .filter-subtitle {
        color: #475569;
        opacity: 0.85;
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

    .form-label {
        font-weight: 600;
        color: #1f2937;
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
        border-radius: 0.95rem;
        border-color: rgba(148, 163, 184, 0.4);
        height: 54px;
    }

    .form-select {
        border-radius: 0.95rem;
        border-color: rgba(148, 163, 184, 0.4);
        height: 54px;
        padding-left: 1.1rem;
    }

    .article-active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem 1rem;
        align-items: center;
        margin-top: 0.5rem;
    }

    .article-active-filters .pill-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .article-reset-filter {
        margin-left: auto;
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .article-reset-filter:hover {
        text-decoration: underline;
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
        padding: 0.5rem 1.1rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.08);
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .pill:hover,
    .pill.active {
        background: rgba(37, 99, 235, 0.18);
        color: var(--secondary-color);
    }

    .article-featured {
        position: relative;
    }

    .section-heading .section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 1rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 0.75rem;
    }

    .section-title {
        font-size: clamp(1.9rem, 3vw, 2.6rem);
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .section-subtitle {
        max-width: 640px;
        margin: 0 auto;
        color: #475569;
    }

    .featured-article-card {
        position: relative;
        border-radius: 2rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 25px 60px -30px rgba(15, 23, 42, 0.35);
    }

    .featured-article-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.35), rgba(125, 211, 252, 0.15));
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .featured-article-media {
        height: 100%;
        min-height: 320px;
        overflow: hidden;
    }

    .featured-article-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-article-placeholder {
        height: 100%;
        display: grid;
        place-items: center;
        background: rgba(37, 99, 235, 0.08);
        color: var(--primary-color);
        font-size: 2.75rem;
    }

    .featured-article-content {
        padding: 2.5rem 2.75rem;
    }

    .featured-article-excerpt {
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .featured-article-meta,
    .featured-article-advice {
        background: rgba(37, 99, 235, 0.06);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        color: #1f2937;
    }

    .featured-article-meta {
        margin-bottom: 1.25rem;
    }

    .featured-article-advice span.badge {
        background: rgba(37, 99, 235, 0.12) !important;
        color: var(--primary-color);
    }

    .article-grid {
        overflow: hidden;
    }

    .article-grid::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), rgba(59, 130, 246, 0));
        pointer-events: none;
    }

    .article-card {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.4);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .article-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: var(--card-gradient, linear-gradient(135deg, rgba(37, 99, 235, 0.35), rgba(59, 130, 246, 0.12)));
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: 0.65;
        transition: opacity 0.35s ease;
    }

    .article-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px -30px rgba(37, 99, 235, 0.45);
    }

    .article-card:hover::before {
        opacity: 1;
    }

    .article-card-media {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .article-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .article-card:hover .article-card-media img {
        transform: scale(1.05);
    }

    .article-card-placeholder {
        height: 100%;
        display: grid;
        place-items: center;
        background: rgba(148, 163, 184, 0.18);
        color: var(--primary-color);
        font-size: 2.5rem;
    }

    .article-card-body {
        padding: 1.9rem 1.9rem 1.5rem;
        flex-grow: 1;
    }

    .article-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .article-card-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .article-card-excerpt {
        color: #475569;
        margin-bottom: 1.25rem;
        min-height: 72px;
    }

    .article-card-tagline,
    .article-card-advice {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: #1e293b;
        font-weight: 500;
        background: rgba(37, 99, 235, 0.06);
        border-radius: 0.85rem;
        padding: 0.65rem 0.9rem;
        margin-bottom: 0.75rem;
    }

    .article-card-footer {
        display: flex;
        flex-wrap: wrap;
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
        border: none;
    }

    .btn-soft-primary:hover {
        background: rgba(37, 99, 235, 0.22);
        color: var(--secondary-color);
    }

    .article-card .btn-primary {
        border-radius: 0.85rem;
        flex: 1 1 180px;
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

    .article-empty-state {
        max-width: 540px;
        margin: 0 auto;
    }

    .article-empty-icon {
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

    .article-newsletter-card {
        position: relative;
        border-radius: 2rem;
        padding: 3rem;
        background: linear-gradient(125deg, rgba(30, 64, 175, 0.95), rgba(37, 99, 235, 0.9));
        box-shadow: 0 30px 60px -30px rgba(30, 64, 175, 0.6);
        overflow: hidden;
    }

    .article-newsletter-card::before {
        content: "";
        position: absolute;
        top: -30%;
        right: -15%;
        width: 55%;
        height: 160%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0));
        opacity: 0.6;
    }

    .article-newsletter-form .input-group {
        border-radius: 1rem;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(6px);
    }

    .article-newsletter-form .input-group-text {
        background: transparent;
        border: none;
        color: #fff;
        padding-left: 1.25rem;
    }

    .article-newsletter-form .form-control {
        border: none;
        background: transparent;
        color: #fff;
        padding: 0.85rem 1.25rem;
    }

    .article-newsletter-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.65);
    }

    .article-newsletter-form .btn {
        border-radius: 0.85rem;
        font-weight: 600;
    }

    .article-categories .section-title {
        color: var(--dark-color);
    }

    .category-card {
        position: relative;
        border-radius: 1.5rem;
        padding: 2rem;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px -30px rgba(37, 99, 235, 0.35);
    }

    .category-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1.5rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.12);
        display: grid;
        place-items: center;
        font-size: 1.8rem;
    }

    .category-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
    }

    .category-card p {
        color: #475569;
        min-height: 66px;
    }

    .category-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .category-link:hover {
        color: var(--secondary-color);
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

    @media (max-width: 1200px) {
        .article-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .article-divider:nth-of-type(3) {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .article-hero {
            padding: 5rem 0 4.5rem;
        }

        .hero-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .article-stats {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .article-divider {
            display: none;
        }

        .article-filter-card {
            padding: 2rem;
        }

        .article-card-footer {
            flex-direction: column;
        }

        .featured-article-content {
            padding: 2.1rem;
        }

        .article-newsletter-card {
            padding: 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .article-hero {
            padding: 4.5rem 0 4rem;
        }

        .article-stats {
            padding: 1.25rem 1.6rem;
        }

        .article-filter-card {
            padding: 1.75rem;
        }

        .filter-pills {
            flex-direction: column;
            align-items: flex-start;
        }

        .article-active-filters {
            flex-direction: column;
            align-items: flex-start;
        }

        .article-reset-filter {
            margin-left: 0;
        }

        .article-card-body,
        .article-card-footer {
            padding: 1.6rem;
        }

        .featured-article-card {
            border-radius: 1.5rem;
        }

        .article-newsletter-card {
            text-align: center;
        }

        .article-newsletter-form .input-group {
            flex-direction: column;
        }

        .article-newsletter-form .input-group-text,
        .article-newsletter-form .btn {
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
