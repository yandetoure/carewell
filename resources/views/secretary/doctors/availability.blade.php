@extends('layouts.secretary')

@section('title', 'Disponibilités des Médecins - Secrétariat')
@section('page-title', 'Disponibilités des Médecins')
@section('page-subtitle', 'Gérer les disponibilités des médecins du service')
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

    <!-- Statistiques des disponibilités -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $availabilities->count() }}</h4>
                            <p class="text-muted mb-0">Total disponibilités</p>
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
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $availabilities->where('available_date', '>=', now()->toDateString())->count() }}</h4>
                            <p class="text-muted mb-0">Disponibilités futures</p>
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
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $availabilities->where('available_date', now()->toDateString())->count() }}</h4>
                            <p class="text-muted mb-0">Disponibles aujourd'hui</p>
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
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $doctors->count() }}</h4>
                            <p class="text-muted mb-0">Médecins du service</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        Filtres
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('secretary.doctors.availability') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">Médecin</label>
                                    <select class="form-select" id="doctor_id" name="doctor_id">
                                        <option value="">Tous les médecins</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date_from" class="form-label">Date de début</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_from" 
                                           name="date_from" 
                                           value="{{ request('date_from', now()->toDateString()) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date_to" class="form-label">Date de fin</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_to" 
                                           name="date_to" 
                                           value="{{ request('date_to', now()->addDays(7)->toDateString()) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Tous</option>
                                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Réservé</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('secretary.doctors.availability') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Effacer
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Disponibilités des Médecins
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.doctors') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user-md me-2"></i>Médecins
                        </a>
                        <a href="{{ route('secretary.doctors.schedule') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-week me-2"></i>Planning
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($availabilities->count() > 0)
                        @php
                            $availabilitiesByDate = $availabilities->groupBy('available_date');
                        @endphp
                        
                        @foreach($availabilitiesByDate->sortKeys() as $date => $dayAvailabilities)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="mb-0 me-3">
                                        <i class="fas fa-calendar-day text-primary me-2"></i>
                                        {{ \Carbon\Carbon::parse($date)->format('l d/m/Y') }}
                                    </h6>
                                    <span class="badge bg-primary">{{ $dayAvailabilities->count() }} créneaux</span>
                                    @if($date === now()->toDateString())
                                        <span class="badge bg-success ms-2">Aujourd'hui</span>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    @foreach($dayAvailabilities->sortBy('start_time') as $availability)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card h-100 availability-card" data-doctor-id="{{ $availability->doctor_id }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0">
                                                            <i class="fas fa-user-md text-success me-1"></i>
                                                            Dr. {{ $availability->doctor->first_name ?? 'N/A' }} {{ $availability->doctor->last_name ?? 'N/A' }}
                                                        </h6>
                                                        <span class="badge bg-info">
                                                            {{ $availability->duration ?? 30 }}min
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="mb-2">
                                                        <i class="fas fa-clock text-primary me-1"></i>
                                                        <strong>{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}</strong>
                                                    </div>
                                                    
                                                    <div class="mb-2">
                                                        <i class="fas fa-stethoscope text-info me-1"></i>
                                                        <small>{{ $availability->service->name ?? 'Service non spécifié' }}</small>
                                                    </div>
                                                    
                                                    @if($availability->notes)
                                                        <div class="mb-3">
                                                            <i class="fas fa-sticky-note text-warning me-1"></i>
                                                            <small>{{ $availability->notes }}</small>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            Créé le {{ \Carbon\Carbon::parse($availability->created_at)->format('d/m/Y') }}
                                                        </small>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary" 
                                                                    onclick="viewAvailability({{ $availability->id }})" 
                                                                    title="Voir les détails">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-success" 
                                                                    onclick="bookAppointment({{ $availability->id }})" 
                                                                    title="Prendre RDV">
                                                                <i class="fas fa-calendar-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune disponibilité trouvée</h5>
                            <p class="text-muted">Aucune disponibilité ne correspond à vos critères de recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'une disponibilité -->
<div class="modal fade" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Détails de la disponibilité
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="availabilityDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="bookAppointmentFromModal()">
                    <i class="fas fa-calendar-plus me-1"></i>Prendre RDV
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

.availability-card {
    transition: transform 0.2s ease-in-out;
}

.availability-card:hover {
    transform: translateY(-2px);
}

.availability-card.hidden {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
let selectedAvailabilityId = null;

// Voir les détails d'une disponibilité
function viewAvailability(availabilityId) {
    selectedAvailabilityId = availabilityId;
    
    document.getElementById('availabilityDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('availabilityModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('availabilityDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user-md me-2"></i>Informations du médecin</h6>
                    <div class="mb-3">
                        <strong>Nom:</strong><br>
                        Dr. [Nom du médecin]
                    </div>
                    <div class="mb-3">
                        <strong>Service:</strong><br>
                        [Nom du service]
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-calendar me-2"></i>Détails de la disponibilité</h6>
                    <div class="mb-3">
                        <strong>Date:</strong><br>
                        [Date]
                    </div>
                    <div class="mb-3">
                        <strong>Heure:</strong><br>
                        [Heure de début] - [Heure de fin]
                    </div>
                    <div class="mb-3">
                        <strong>Durée:</strong><br>
                        [Durée] minutes
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Prendre un rendez-vous
function bookAppointment(availabilityId) {
    selectedAvailabilityId = availabilityId;
    window.location.href = `{{ route('secretary.appointments.create') }}?availability=${availabilityId}`;
}

// Prendre un rendez-vous depuis le modal
function bookAppointmentFromModal() {
    if (selectedAvailabilityId) {
        bookAppointment(selectedAvailabilityId);
    }
}

// Auto-submit form on change
document.getElementById('doctor_id').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
@endpush
