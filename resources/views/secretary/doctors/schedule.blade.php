@extends('layouts.secretary')

@section('title', 'Planning des Médecins - Secrétariat')
@section('page-title', 'Planning des Médecins')
@section('page-subtitle', 'Disponibilités et planning des médecins du service')
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

    <!-- Statistiques des médecins -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
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
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $availabilities->count() }}</h4>
                            <p class="text-muted mb-0">Disponibilités</p>
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
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $availabilities->where('available_date', now()->toDateString())->count() }}</h4>
                            <p class="text-muted mb-0">Disponibles aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste des médecins -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>Médecins du Service
                    </h5>
                </div>
                <div class="card-body">
                    @if($doctors->count() > 0)
                        @foreach($doctors as $doctor)
                            <div class="doctor-card mb-3 p-3 border rounded" onclick="showDoctorSchedule({{ $doctor->id }})" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="doctor-avatar me-3">
                                        <div class="avatar bg-primary text-white">
                                            {{ strtoupper(substr($doctor->first_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h6>
                                        @if($doctor->grade)
                                            <small class="text-muted">{{ $doctor->grade->name }}</small>
                                        @endif
                                        <br>
                                        <small class="text-info">{{ $doctor->email }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success" id="availability-count-{{ $doctor->id }}">
                                            {{ $availabilities->where('doctor_id', $doctor->id)->count() }} dispo
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Aucun médecin trouvé</h6>
                            <p class="text-muted">Aucun médecin n'est assigné à votre service.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Planning des disponibilités -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Planning des Disponibilités
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="doctorFilter" onchange="filterByDoctor()">
                                <option value="">Tous les médecins</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshSchedule()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
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
                            <p class="text-muted">Aucune disponibilité n'est programmée pour les médecins de votre service.</p>
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
.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: bold;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.doctor-card:hover {
    background-color: rgba(0, 123, 255, 0.05);
    border-color: #007bff !important;
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

// Filtrer par médecin
function filterByDoctor() {
    const doctorId = document.getElementById('doctorFilter').value;
    const cards = document.querySelectorAll('.availability-card');
    
    cards.forEach(card => {
        if (doctorId === '' || card.dataset.doctorId === doctorId) {
            card.parentElement.style.display = 'block';
        } else {
            card.parentElement.style.display = 'none';
        }
    });
}

// Afficher le planning d'un médecin spécifique
function showDoctorSchedule(doctorId) {
    document.getElementById('doctorFilter').value = doctorId;
    filterByDoctor();
}

// Actualiser le planning
function refreshSchedule() {
    window.location.reload();
}

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

// Auto-refresh toutes les 5 minutes
setInterval(function() {
    // Ne pas actualiser si l'utilisateur est en train de remplir un formulaire
    if (!document.activeElement || document.activeElement.tagName !== 'INPUT') {
        location.reload();
    }
}, 300000); // 5 minutes
</script>
@endpush
