@extends('layouts.app')

@section('title', 'Nos Services - CareWell')

@section('content')
<!-- Header Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="section-title">Nos Services Médicaux</h1>
                <p class="section-subtitle">Découvrez notre gamme complète de services de santé pour tous vos besoins</p>
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
                        <form action="{{ route('services') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher un service..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="sort" class="form-select">
                                    <option value="">Trier par</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Prix décroissant</option>
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

<!-- Services Grid -->
<section class="py-5">
    <div class="container">
        @if($services->count() > 0)
            <div class="row g-4">
                @foreach($services as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 service-card">
                        @if($service->photo)
                            <img src="{{ asset('storage/' . $service->photo) }}" class="card-img-top" alt="{{ $service->name }}" style="height: 250px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="fas fa-stethoscope fa-4x text-white"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $service->name }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($service->description, 120) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary fs-6">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Consultation
                                    </small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-info-circle me-2"></i>Détails
                                    </a>
                                    @auth
                                        <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="btn btn-primary">
                                            <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary">
                                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $services->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif

        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted"></i>
                </div>
                <h3>Aucun service trouvé</h3>
                <p class="text-muted">Aucun service ne correspond à votre recherche. Essayez de modifier vos critères.</p>
                <a href="{{ route('services') }}" class="btn btn-primary">
                    <i class="fas fa-undo me-2"></i>Voir tous les services
                </a>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2>Besoin d'un service personnalisé ?</h2>
                <p class="lead mb-4">Contactez-nous pour discuter de vos besoins spécifiques et obtenir un devis personnalisé.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                    <a href="{{ route('appointments.create') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Prendre rendez-vous
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .service-card {
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .service-card .card-img-top {
        transition: all 0.3s ease;
    }

    .service-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }

    /* Style personnalisé pour la pagination */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: #5a5c69;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 2px;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
    }

    .pagination .page-link:hover {
        color: #fff;
        background-color: #5a5c69;
        border-color: #5a5c69;
    }

    .pagination .page-item.active .page-link {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
    }

    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
</style>
@endsection
