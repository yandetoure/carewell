@extends('layouts.app')

@section('title', $article->title . ' - CareWell')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3 bg-light">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles') }}">Articles</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 50) }}</li>
        </ol>
    </div>
</nav>

<!-- Article Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Article -->
            <div class="col-lg-8">
                <article class="article-content">
                    <!-- Article Header -->
                    <header class="mb-4">
                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $article->created_at->format('d/m/Y') }}</span>
                            <span class="badge bg-secondary ms-2">Santé</span>
                        </div>

                        <h1 class="display-5 fw-bold mb-3">{{ $article->title }}</h1>

                        <div class="d-flex align-items-center text-muted mb-4">
                            <i class="fas fa-clock me-2"></i>
                            <span>{{ $article->created_at->diffForHumans() }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-user me-2"></i>
                            <span>Équipe CareWell</span>
                        </div>
                    </header>

                    <!-- Article Image -->
                    @if($article->photo)
                    <div class="article-image mb-4">
                        <img src="{{ asset('storage/' . $article->photo) }}" class="img-fluid rounded" alt="{{ $article->title }}" style="width: 100%; max-height: 400px; object-fit: cover;">
                    </div>
                    @endif

                    <!-- Article Body -->
                    <div class="article-body">
                        <div class="content-text">
                            {!! nl2br(e($article->content)) !!}
                        </div>

                        <!-- Symptoms Section -->
                        @if($article->symptoms)
                        <div class="symptoms-section mt-5">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Symptômes à surveiller
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $article->symptoms }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Advice Section -->
                        @if($article->advices)
                        <div class="advice-section mt-5">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-lightbulb me-2"></i>Conseils et recommandations
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $article->advices }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Article Footer -->
                    <footer class="article-footer mt-5 pt-4 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Partager cet article</h6>
                                <div class="social-share">
                                    <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fab fa-facebook-f me-1"></i>Facebook
                                    </a>
                                    <a href="#" class="btn btn-outline-info btn-sm me-2">
                                        <i class="fab fa-twitter me-1"></i>Twitter
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-sm me-2">
                                        <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-link me-1"></i>Copier le lien
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6>Tags</h6>
                                <div class="tags">
                                    <span class="badge bg-light text-dark me-2">Santé</span>
                                    <span class="badge bg-light text-dark me-2">Conseils</span>
                                    <span class="badge bg-light text-dark">Prévention</span>
                                </div>
                            </div>
                        </div>
                    </footer>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Author Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>À propos de l'auteur</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-4x text-primary"></i>
                        </div>
                        <h6>Équipe CareWell</h6>
                        <p class="text-muted small">Notre équipe de professionnels de santé partage avec vous des conseils et informations pour vous aider à maintenir une bonne santé.</p>
                    </div>
                </div>

                <!-- Related Articles -->
                @if($relatedArticles->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-newspaper me-2"></i>Articles similaires</h6>
                    </div>
                    <div class="card-body">
                        @foreach($relatedArticles->take(3) as $relatedArticle)
                        <div class="related-article mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            @if($relatedArticle->photo)
                                <img src="{{ asset('storage/' . $relatedArticle->photo) }}" class="float-start me-3" alt="{{ $relatedArticle->title }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div class="float-start me-3 bg-secondary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                    <i class="fas fa-newspaper text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('articles.show', $relatedArticle->id) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($relatedArticle->title, 60) }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $relatedArticle->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Newsletter Signup -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Newsletter</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Recevez nos derniers articles et conseils santé.</p>
                        <form>
                            <div class="mb-3">
                                <input type="email" class="form-control form-control-sm" placeholder="Votre email" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-paper-plane me-2"></i>S'abonner
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comments Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="text-center mb-5">Commentaires et discussions</h3>

                <div class="card">
                    <div class="card-body">
                        @auth
                            <form class="mb-4">
                                <div class="mb-3">
                                    <textarea class="form-control" rows="3" placeholder="Partagez votre avis ou posez une question..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Publier
                                </button>
                            </form>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">Connectez-vous pour laisser un commentaire</p>
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </a>
                            </div>
                        @endauth

                        <hr>

                        <!-- Sample Comments -->
                        <div class="comments-list">
                            <div class="comment mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle fa-2x text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">Marie D.</h6>
                                            <small class="text-muted">Il y a 2 jours</small>
                                        </div>
                                        <p class="mb-1">Très bon article, très informatif ! J'ai appris beaucoup de choses.</p>
                                        <small class="text-muted">
                                            <a href="#" class="text-decoration-none">Répondre</a>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="comment mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle fa-2x text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">Pierre L.</h6>
                                            <small class="text-muted">Il y a 1 semaine</small>
                                        </div>
                                        <p class="mb-1">Merci pour ces conseils pratiques. C'est exactement ce que je cherchais.</p>
                                        <small class="text-muted">
                                            <a href="#" class="text-decoration-none">Répondre</a>
                                        </small>
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

<!-- Related Articles Section -->
@if($relatedArticles->count() > 0)
<section class="py-5">
    <div class="container">
        <h3 class="text-center mb-5">Articles connexes</h3>

        <div class="row g-4">
            @foreach($relatedArticles->take(3) as $relatedArticle)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    @if($relatedArticle->photo)
                        <img src="{{ asset('storage/' . $relatedArticle->photo) }}" class="card-img-top" alt="{{ $relatedArticle->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-newspaper fa-4x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($relatedArticle->title, 60) }}</h5>
                        <p class="card-text">{{ Str::limit($relatedArticle->content, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $relatedArticle->created_at->format('d/m/Y') }}</small>
                            <a href="{{ route('articles.show', $relatedArticle->id) }}" class="btn btn-outline-primary btn-sm">Lire</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('articles') }}" class="btn btn-primary">
                <i class="fas fa-newspaper me-2"></i>Voir tous les articles
            </a>
        </div>
    </div>
</section>
@endif
@endsection

@section('styles')
<style>
    .article-content {
        line-height: 1.8;
    }

    .article-content h1, .article-content h2, .article-content h3 {
        color: var(--dark-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .article-content p {
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    .article-image {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .symptoms-section, .advice-section {
        border-radius: 12px;
        overflow: hidden;
    }

    .social-share .btn {
        margin-bottom: 0.5rem;
    }

    .related-article:hover h6 a {
        color: var(--primary-color) !important;
    }

    .comments-list .comment {
        padding: 1rem;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .breadcrumb a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: var(--secondary-color);
    }

    .tags .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endsection
