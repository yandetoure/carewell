@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Services médicaux
                    </h5>
                    <p class="text-muted mb-0">Découvrez nos services médicaux disponibles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.services') }}" class="row g-3">
                        <div class="col-md-8">
                            <label for="search" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Nom ou description du service...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                                <a href="{{ route('patient.services') }}" class="btn btn-outline-secondary mt-2">
                                    <i class="fas fa-times me-2"></i>Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des services -->
    <div class="row">
        @if(isset($services) && $services->count() > 0)
            @foreach($services as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 service-card">
                    @if($service->photo)
                        <img src="{{ asset('storage/' . $service->photo) }}" 
                             class="card-img-top" alt="{{ $service->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-stethoscope fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        
                        @if($service->description)
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($service->description, 120) }}
                        </p>
                        @endif
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">
                                    {{ number_format($service->price, 0, ',', ' ') }} FCFA
                                </span>
                                <span class="badge bg-success">Disponible</span>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('patient.services.show', $service->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-info-circle me-2"></i>Détails
                                </a>
                                
                                <a href="{{ route('patient.appointments.create', ['service_id' => $service->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                    <h5>Aucun service trouvé</h5>
                    <p class="text-muted mb-3">
                        @if(request('search'))
                            Aucun service ne correspond à votre recherche "{{ request('search') }}".
                        @else
                            Aucun service n'est disponible pour le moment.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('patient.services') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times me-2"></i>Effacer la recherche
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($services) && $services->hasPages())
    <div class="row">
        <div class="col-12">
            <!-- Pagination Info -->
            <div class="pagination-info">
                <i class="fas fa-info-circle me-2"></i>
                Affichage de {{ $services->firstItem() }} à {{ $services->lastItem() }} sur {{ $services->total() }} résultats
            </div>
            
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $services->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.service-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e9ecef;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.service-card .card-img-top {
    border-bottom: 1px solid #e9ecef;
}

.service-card .card-title {
    color: #2c3e50;
    font-weight: 600;
}

.service-card .card-text {
    color: #6c757d;
    line-height: 1.5;
}

.service-card .btn {
    border-radius: 8px;
    font-weight: 500;
}

.service-card .text-primary {
    color: #007bff !important;
}

.service-card .badge {
    font-size: 0.8rem;
}
</style>
@endsection
