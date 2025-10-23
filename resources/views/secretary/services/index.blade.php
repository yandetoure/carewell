@extends('layouts.secretary')

@section('title', 'Services Médicaux - Secrétariat')
@section('page-title', 'Services Médicaux')
@section('page-subtitle', 'Gérer les services du centre médical')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques des services -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $services->count() }}</h4>
                            <p class="text-muted mb-0">Total services</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $recentServices }}</h4>
                            <p class="text-muted mb-0">Services récents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $servicesWithAppointments }}</h4>
                            <p class="text-muted mb-0">Avec rendez-vous</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalServices }}</h4>
                            <p class="text-muted mb-0">Total services</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Services Médicaux
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.services.categories') }}" class="btn btn-outline-primary">
                            <i class="fas fa-tags me-2"></i>Catégories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($services->count() > 0)
                        <div class="row">
                            @foreach($services as $service)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 service-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($service->photo)
                                                    <img src="{{ asset('storage/' . $service->photo) }}" 
                                                         alt="Photo" 
                                                         class="rounded me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-stethoscope text-white fa-lg"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $service->name }}</h6>
                                                    @if($service->category)
                                                        <small class="text-muted">{{ ucfirst($service->category) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-money-bill text-success me-2"></i>
                                                    <strong>{{ number_format($service->price, 0) }} FCFA</strong>
                                                </div>
                                                @if($service->description)
                                                    <p class="text-muted small mb-0">{{ Str::limit($service->description, 100) }}</p>
                                                @endif
                                            </div>
                                            
                                            <!-- Statistiques du service -->
                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <div class="border-end">
                                                        <h6 class="mb-1 text-primary">
                                                            @php
                                                                $doctorsCount = \App\Models\User::role('Doctor')
                                                                    ->where('service_id', $service->id)
                                                                    ->count();
                                                            @endphp
                                                            {{ $doctorsCount }}
                                                        </h6>
                                                        <small class="text-muted">Médecins</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="border-end">
                                                        <h6 class="mb-1 text-success">
                                                            @php
                                                                $todayAppointments = \App\Models\Appointment::where('service_id', $service->id)
                                                                    ->whereDate('appointment_date', now()->toDateString())
                                                                    ->count();
                                                            @endphp
                                                            {{ $todayAppointments }}
                                                        </h6>
                                                        <small class="text-muted">RDV aujourd'hui</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <h6 class="mb-1 text-info">
                                                        @php
                                                            $totalAppointments = \App\Models\Appointment::where('service_id', $service->id)->count();
                                                        @endphp
                                                        {{ $totalAppointments }}
                                                    </h6>
                                                    <small class="text-muted">Total RDV</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm flex-fill" 
                                                        onclick="viewService({{ $service->id }})" 
                                                        title="Voir les détails">
                                                    <i class="fas fa-eye me-1"></i>Détails
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm flex-fill" 
                                                        onclick="viewDoctors({{ $service->id }})" 
                                                        title="Voir les médecins">
                                                    <i class="fas fa-user-md me-1"></i>Médecins
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm flex-fill" 
                                                        onclick="viewAppointments({{ $service->id }})" 
                                                        title="Voir les RDV">
                                                    <i class="fas fa-calendar me-1"></i>RDV
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($services->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $services->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-stethoscope fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun service trouvé</h5>
                            <p class="text-muted">Il n'y a aucun service médical disponible pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails du service -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-stethoscope me-2"></i>Détails du Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="serviceDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="viewDoctorsFromModal()">
                    <i class="fas fa-user-md me-1"></i>Voir les Médecins
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.service-card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e3e6f0;
}

.service-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
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
@endpush

@push('scripts')
<script>
let selectedServiceId = null;

// Voir les détails d'un service
function viewService(serviceId) {
    selectedServiceId = serviceId;
    
    document.getElementById('serviceDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('serviceDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-stethoscope me-2"></i>Informations du service</h6>
                    <div class="mb-3">
                        <strong>Nom:</strong><br>
                        [Nom du service]
                    </div>
                    <div class="mb-3">
                        <strong>Prix:</strong><br>
                        [Prix du service] FCFA
                    </div>
                    <div class="mb-3">
                        <strong>Catégorie:</strong><br>
                        [Catégorie du service]
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
                    <div class="mb-3">
                        <strong>Nombre de médecins:</strong><br>
                        [Nombre de médecins]
                    </div>
                    <div class="mb-3">
                        <strong>RDV aujourd'hui:</strong><br>
                        [Nombre de RDV aujourd'hui]
                    </div>
                    <div class="mb-3">
                        <strong>Total RDV:</strong><br>
                        [Nombre total de RDV]
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Voir les médecins d'un service
function viewDoctors(serviceId) {
    selectedServiceId = serviceId;
    window.location.href = `{{ route('secretary.doctors') }}?service=${serviceId}`;
}

// Voir les médecins depuis le modal
function viewDoctorsFromModal() {
    if (selectedServiceId) {
        viewDoctors(selectedServiceId);
    }
}

// Voir les rendez-vous d'un service
function viewAppointments(serviceId) {
    window.location.href = `{{ route('secretary.appointments') }}?service=${serviceId}`;
}
</script>
@endpush
