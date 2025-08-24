@extends('layouts.app')

@section('title', 'Articles de Santé - CareWell')

@section('content')
<!-- Header Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="section-title">Articles de Santé</h1>
                <p class="section-subtitle">Restez informé avec nos derniers articles, conseils et actualités santé</p>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('articles') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher un article..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">Toutes les catégories</option>
                                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>Santé générale</option>
                                    <option value="prevention" {{ request('category') == 'prevention' ? 'selected' : '' }}>Prévention</option>
                                    <option value="nutrition" {{ request('category') == 'nutrition' ? 'selected' : '' }}>Nutrition</option>
                                    <option value="fitness" {{ request('category') == 'fitness' ? 'selected' : '' }}>Fitness</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Article -->
@if($featuredArticle)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card featured-article">
                    <div class="row g-0">
                        <div class="col-md-6">
                            @if($featuredArticle->photo)
                                <img src="{{ asset('storage/' . $featuredArticle->photo) }}" class="img-fluid h-100" alt="{{ $featuredArticle->title }}" style="object-fit: cover;">
                            @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-newspaper fa-6x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="mb-3">
                                    <span class="badge bg-primary">Article à la une</span>
                                </div>
                                <h2 class="card-title">{{ $featuredArticle->title }}</h2>
                                <p class="card-text lead">{{ Str::limit($featuredArticle->content, 200) }}</p>

                                <div class="mt-auto">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-clock text-muted me-2"></i>
                                        <small class="text-muted">{{ $featuredArticle->created_at->format('d/m/Y') }}</small>
                                    </div>

                                    @if($featuredArticle->symptoms)
                                    <div class="mb-3">
                                        <strong>Symptômes :</strong>
                                        <p class="mb-0 text-muted">{{ $featuredArticle->symptoms }}</p>
                                    </div>
                                    @endif

                                    <a href="{{ route('articles.show', $featuredArticle->id) }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-right me-2"></i>Lire l'article complet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Articles Grid -->
<section class="py-5">
    <div class="container">
        @if($articles->count() > 0)
            <div class="row g-4">
                @foreach($articles as $article)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 article-card">
                        @if($article->photo)
                            <img src="{{ asset('storage/' . $article->photo) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-newspaper fa-4x text-white"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $article->created_at->format('M Y') }}</span>
                            </div>

                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($article->content, 120) }}</p>

                            @if($article->symptoms)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Symptômes :</strong> {{ Str::limit($article->symptoms, 80) }}
                                </small>
                            </div>
                            @endif

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $article->created_at->diffForHumans() }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>Lecture
                                    </small>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-right me-2"></i>Lire plus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $articles->links() }}
            </div>
            @endif

        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-newspaper fa-4x text-muted"></i>
                </div>
                <h3>Aucun article trouvé</h3>
                <p class="text-muted">Aucun article ne correspond à votre recherche. Essayez de modifier vos critères.</p>
                <a href="{{ route('articles') }}" class="btn btn-primary">
                    <i class="fas fa-undo me-2"></i>Voir tous les articles
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2>Restez informé de nos actualités</h2>
                <p class="lead mb-4">Recevez nos derniers articles et conseils santé directement dans votre boîte mail.</p>

                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Votre adresse email" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-light btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>S'abonner
                        </button>
                    </div>
                </form>

                <small class="text-white-50">
                    <i class="fas fa-shield-alt me-1"></i>
                    Nous respectons votre vie privée. Désabonnement en 1 clic.
                </small>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h3 class="text-center mb-5">Explorez par catégorie</h3>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-center category-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-heartbeat fa-3x text-primary"></i>
                        </div>
                        <h5>Santé générale</h5>
                        <p class="text-muted">Conseils et informations sur la santé au quotidien</p>
                        <a href="{{ route('articles', ['category' => 'general']) }}" class="btn btn-outline-primary btn-sm">Explorer</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-center category-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-success"></i>
                        </div>
                        <h5>Prévention</h5>
                        <p class="text-muted">Comment prévenir les maladies et rester en bonne santé</p>
                        <a href="{{ route('articles', ['category' => 'prevention']) }}" class="btn btn-outline-success btn-sm">Explorer</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-center category-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-apple-alt fa-3x text-warning"></i>
                        </div>
                        <h5>Nutrition</h5>
                        <p class="text-muted">Conseils nutritionnels et alimentation équilibrée</p>
                        <a href="{{ route('articles', ['category' => 'nutrition']) }}" class="btn btn-outline-warning btn-sm">Explorer</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-center category-card">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-dumbbell fa-3x text-info"></i>
                        </div>
                        <h5>Fitness</h5>
                        <p class="text-muted">Exercices et conseils pour rester actif</p>
                        <a href="{{ route('articles', ['category' => 'fitness']) }}" class="btn btn-outline-info btn-sm">Explorer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .featured-article {
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .featured-article .card-img-top {
        transition: all 0.3s ease;
    }

    .featured-article:hover .card-img-top {
        transform: scale(1.05);
    }

    .article-card {
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .category-card {
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-link {
        color: var(--primary-color);
        border-color: var(--border-color);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
@endsection
