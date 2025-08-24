@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Service principal -->
            <div class="card">
                @if($service->photo)
                <img src="{{ asset('storage/' . $service->photo) }}" 
                     class="card-img-top" alt="{{ $service->name }}"
                     style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Service disponible
                        </small>
                    </div>
                    
                    <h1 class="card-title mb-4">{{ $service->name }}</h1>
                    
                    @if($service->description)
                    <div class="service-description mb-4">
                        <h6 class="text-muted mb-3">Description du service</h6>
                        <p class="lead">{{ $service->description }}</p>
                    </div>
                    @endif
                    
                    <div class="service-pricing mb-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2">{{ number_format($service->price, 0, ',', ' ') }} FCFA</h3>
                                <p class="text-muted mb-0">Prix du service</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="service-actions">
                        <div class="d-grid gap-2">
                            <a href="{{ route('patient.appointments.create', ['service_id' => $service->id]) }}" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus me-2"></i>Prendre rendez-vous
                            </a>
                            
                            <a href="{{ route('patient.services') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux services
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Services connexes -->
            @if(isset($relatedServices) && $relatedServices->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Autres services
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($relatedServices as $relatedService)
                    <div class="related-service mb-3 pb-3 border-bottom">
                        <h6 class="card-title">
                            <a href="{{ route('patient.services.show', $relatedService->id) }}" 
                               class="text-decoration-none">
                                {{ Str::limit($relatedService->name, 60) }}
                            </a>
                        </h6>
                        
                        @if($relatedService->description)
                        <p class="card-text small text-muted">
                            {{ Str::limit($relatedService->description, 80) }}
                        </p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">
                                {{ number_format($relatedService->price, 0, ',', ' ') }} FCFA
                            </span>
                            <a href="{{ route('patient.services.show', $relatedService->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Navigation -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        Navigation
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('patient.services') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-list me-2"></i>Retour à la liste
                    </a>
                    
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-home me-2"></i>Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-description {
    line-height: 1.8;
}

.service-pricing .card {
    border: 2px solid #e9ecef;
}

.service-pricing .text-primary {
    color: #007bff !important;
}

.related-service:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.related-service .card-title a {
    color: #2c3e50;
    font-weight: 600;
}

.related-service .card-title a:hover {
    color: #007bff;
}

.card-img-top {
    border-bottom: 1px solid #e9ecef;
}

.service-actions .btn {
    border-radius: 8px;
    font-weight: 500;
}
</style>
@endsection
