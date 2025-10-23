@extends('layouts.app')

@section('title', $service->name . ' - CareWell')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3 bg-light">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
        </ol>
    </div>
</nav>

<!-- Service Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Service Image and Info -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    @if($service->photo)
                        <img src="{{ asset('storage/' . $service->photo) }}" class="card-img-top" alt="{{ $service->name }}" style="height: 400px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-stethoscope fa-6x text-white"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="card-title h2">{{ $service->name }}</h1>
                            <span class="badge bg-primary fs-5">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                        </div>

                        <p class="card-text lead">{{ $service->description }}</p>

                        <!-- Service Features -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-clock text-primary me-3 fa-lg"></i>
                                    <div>
                                        <strong>Durée</strong>
                                        <p class="mb-0 text-muted">Consultation standard</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-user-md text-primary me-3 fa-lg"></i>
                                    <div>
                                        <strong>Spécialiste</strong>
                                        <p class="mb-0 text-muted">Médecin qualifié</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar-check text-primary me-3 fa-lg"></i>
                                    <div>
                                        <strong>Disponibilité</strong>
                                        <p class="mb-0 text-muted">Rendez-vous flexible</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-shield-alt text-primary me-3 fa-lg"></i>
                                    <div>
                                        <strong>Confidentialité</strong>
                                        <p class="mb-0 text-muted">Données sécurisées</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Services -->
                @if($relatedServices->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Services similaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($relatedServices->take(3) as $relatedService)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    @if($relatedService->photo)
                                        <img src="{{ asset('storage/' . $relatedService->photo) }}" class="card-img-top" alt="{{ $relatedService->name }}" style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <i class="fas fa-stethoscope fa-2x text-white"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $relatedService->name }}</h6>
                                        <p class="card-text small">{{ Str::limit($relatedService->description, 60) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">{{ number_format($relatedService->price, 0) }} FCFA</span>
                                            <a href="{{ route('services.show', $relatedService->id) }}" class="btn btn-outline-primary btn-sm">Voir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Booking Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Prendre rendez-vous</h5>
                    </div>
                    <div class="card-body">
                        @auth
                            <div class="mb-3">
                                <label class="form-label">Service sélectionné</label>
                                <div class="form-control-plaintext">{{ $service->name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prix</label>
                                <div class="form-control-plaintext fw-bold text-primary">{{ number_format($service->price, 0) }} FCFA</div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-plus me-2"></i>Réserver maintenant
                                </a>

                                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-question-circle me-2"></i>Questions ?
                                </a>
                            </div>

                            <hr>

                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Rendez-vous confirmé sous 24h
                                </small>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                <h6>Connexion requise</h6>
                                <p class="text-muted">Connectez-vous pour prendre rendez-vous</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <div>
                                <strong>Téléphone</strong>
                                <p class="mb-0">+33 1 23 45 67 89</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <strong>Email</strong>
                                <p class="mb-0">contact@carewell.fr</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary me-3"></i>
                            <div>
                                <strong>Horaires</strong>
                                <p class="mb-0">Lun-Ven: 8h-20h<br>Sam: 9h-17h</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Questions fréquentes</h2>

                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Comment se déroule la consultation ?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                La consultation se déroule en plusieurs étapes : accueil, examen clinique, diagnostic, prescription si nécessaire, et conseils de suivi.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Puis-je annuler mon rendez-vous ?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Oui, vous pouvez annuler votre rendez-vous jusqu'à 24h avant la consultation. Contactez-nous par téléphone ou via votre espace patient.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Que dois-je apporter ?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Apportez votre carte vitale, votre mutuelle, et si possible vos derniers examens ou ordonnances pour un suivi optimal.
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
    .sticky-top {
        z-index: 1020;
    }

    .breadcrumb a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: var(--secondary-color);
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--primary-color);
        color: white;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
    }
</style>
@endsection
