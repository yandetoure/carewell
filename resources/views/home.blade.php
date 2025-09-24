@extends('layouts.app')

@section('title', 'CareWell - Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Votre santé, notre priorité</h1>
                <p class="lead mb-4">CareWell connecte patients et professionnels de santé pour des soins optimaux et un suivi médical personnalisé.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Commencer
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-stethoscope me-2"></i>Nos Services
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/medecin.png') }}" alt="Médecin" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Pourquoi choisir CareWell ?</h2>
            <p class="section-subtitle">Une plateforme complète pour tous vos besoins de santé</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-calendar-check fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Rendez-vous en ligne</h5>
                        <p class="card-text">Prenez rendez-vous avec nos spécialistes en quelques clics, 24h/24 et 7j/7.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-file-medical fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Dossier médical digital</h5>
                        <p class="card-text">Accédez à votre dossier médical complet et sécurisé depuis n'importe où.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-user-md fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Experts qualifiés</h5>
                        <p class="card-text">Une équipe de professionnels de santé expérimentés et certifiés.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-mobile-alt fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Application mobile</h5>
                        <p class="card-text">Gérez votre santé depuis votre smartphone avec notre application intuitive.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title">Sécurité garantie</h5>
                        <p class="card-text">Vos données médicales sont protégées par les plus hauts standards de sécurité.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-3x text-secondary"></i>
                        </div>
                        <h5 class="card-title">Disponible 24/7</h5>
                        <p class="card-text">Accédez à vos informations médicales et prenez rendez-vous à tout moment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos Services Médicaux</h2>
            <p class="section-subtitle">Une gamme complète de services pour tous vos besoins de santé</p>
        </div>

        <div class="row g-4">
            @foreach(\App\Models\Service::take(6)->get() as $service)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    @if($service->photo)
                        <img src="{{ asset('storage/' . $service->photo) }}" class="card-img-top" alt="{{ $service->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-stethoscope fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p class="card-text">{{ Str::limit($service->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                            <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-primary btn-sm">En savoir plus</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('services') }}" class="btn btn-primary btn-lg">
                Voir tous nos services
            </a>
        </div>
    </div>
</section>

<!-- Articles Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Articles de Santé</h2>
            <p class="section-subtitle">Restez informé avec nos derniers articles et conseils santé</p>
        </div>

        <div class="row g-4">
            @foreach(\App\Models\Article::take(3)->get() as $article)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    @if($article->photo)
                        <img src="{{ asset('storage/' . $article->photo) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-newspaper fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">{{ Str::limit($article->content, 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $article->created_at->diffForHumans() }}
                            </small>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-outline-primary btn-sm">Lire plus</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('articles') }}" class="btn btn-primary btn-lg">
                Voir tous les articles
            </a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <h3 class="fw-bold">{{ \App\Models\User::count() }}+</h3>
                <p class="mb-0">Patients satisfaits</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-user-md fa-3x"></i>
                </div>
                <h3 class="fw-bold">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'doctor'); })->count() }}+</h3>
                <p class="mb-0">Médecins spécialistes</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-calendar-check fa-3x"></i>
                </div>
                <h3 class="fw-bold">{{ \App\Models\Appointment::count() }}+</h3>
                <p class="mb-0">Rendez-vous réalisés</p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="mb-3">
                    <i class="fas fa-star fa-3x"></i>
                </div>
                <h3 class="fw-bold">4.9/5</h3>
                <p class="mb-0">Note moyenne</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">Prêt à prendre soin de votre santé ?</h2>
                <p class="section-subtitle">Rejoignez des milliers de patients qui font confiance à CareWell pour leurs soins médicaux.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Créer un compte
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
