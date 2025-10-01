@extends('layouts.doctor')

@section('title', 'Rendez-vous d\'aujourd\'hui - Docteur')
@section('page-title', 'Rendez-vous d\'aujourd\'hui')
@section('page-subtitle', 'Vos rendez-vous du ' . \Carbon\Carbon::parse($today)->format('d/m/Y'))
@section('user-role', 'Médecin')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques du jour -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $appointments->count() }}</h4>
                            <p class="text-muted mb-0">Total aujourd'hui</p>
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
                            <h4 class="mb-1">{{ $completedCount }}</h4>
                            <p class="text-muted mb-0">Terminés</p>
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
                            <h4 class="mb-1">{{ $confirmedCount }}</h4>
                            <p class="text-muted mb-0">Confirmés</p>
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
                            <i class="fas fa-hourglass-half text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingCount }}</h4>
                            <p class="text-muted mb-0">En attente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rendez-vous -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-day me-2"></i>Rendez-vous du {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshPage()">
                                <i class="fas fa-sync-alt"></i> Actualiser
                            </button>
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-calendar-alt"></i> Tous les RDV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="row">
                            @foreach($appointments as $appointment)
                                <div class="col-md-6 mb-4">
                                    <div class="card appointment-card {{ $appointment->is_urgent ? 'border-warning' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <div class="avatar bg-primary text-white">
                                                            {{ strtoupper(substr($appointment->user->first_name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</h6>
                                                        <small class="text-muted">{{ $appointment->user->phone }}</small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                    @if($appointment->is_urgent)
                                                        <span class="badge bg-danger ms-1">
                                                            <i class="fas fa-exclamation-triangle"></i> Urgent
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted">Heure</small>
                                                    <p class="mb-0 fw-bold">
                                                        <i class="fas fa-clock text-primary me-1"></i>
                                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                    </p>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Service</small>
                                                    <p class="mb-0">
                                                        <i class="fas fa-stethoscope text-info me-1"></i>
                                                        {{ $appointment->service->name ?? 'Service non spécifié' }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if($appointment->notes)
                                                <div class="mb-3">
                                                    <small class="text-muted">Notes</small>
                                                    <p class="mb-0">{{ $appointment->notes }}</p>
                                                </div>
                                            @endif

                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-info btn-sm" 
                                                        onclick="viewAppointment({{ $appointment->id }})" 
                                                        title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('doctor.patients.show', $appointment->user) }}" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   title="Voir le patient">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                                @if($appointment->status == 'pending')
                                                    <button type="button" class="btn btn-outline-success btn-sm" 
                                                            onclick="confirmAppointment({{ $appointment->id }})" 
                                                            title="Confirmer">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($appointment->status == 'confirmed')
                                                    <button type="button" class="btn btn-outline-info btn-sm" 
                                                            onclick="markAsCompleted({{ $appointment->id }})" 
                                                            title="Marquer comme terminé">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                @endif
                                                @if($appointment->status != 'cancelled' && $appointment->status != 'completed')
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="cancelAppointment({{ $appointment->id }})" 
                                                            title="Annuler">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous aujourd'hui</h5>
                            <p class="text-muted">Vous n'avez pas de rendez-vous programmés pour aujourd'hui.</p>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Gérer mes patients
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'un rendez-vous -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
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

.appointment-card {
    transition: transform 0.2s ease-in-out;
}

.appointment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.border-warning {
    border-left: 4px solid #ffc107 !important;
}
</style>
@endpush

@push('scripts')
<script>
// Actualiser la page
function refreshPage() {
    window.location.reload();
}

// Fonctions pour les actions
function viewAppointment(appointmentId) {
    document.getElementById('appointmentDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    modal.show();
    
    // Ici vous pouvez ajouter une requête AJAX pour récupérer les détails
    setTimeout(() => {
        document.getElementById('appointmentDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informations du patient</h6>
                    <p><strong>Nom:</strong> Patient Name</p>
                    <p><strong>Téléphone:</strong> +123456789</p>
                    <p><strong>Email:</strong> patient@example.com</p>
                </div>
                <div class="col-md-6">
                    <h6>Détails du rendez-vous</h6>
                    <p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
                    <p><strong>Heure:</strong> 10:30</p>
                    <p><strong>Service:</strong> Consultation</p>
                </div>
            </div>
        `;
    }, 1000);
}

function confirmAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir confirmer ce rendez-vous ?')) {
        updateAppointmentStatus(appointmentId, 'confirmed');
    }
}

function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        updateAppointmentStatus(appointmentId, 'cancelled');
    }
}

function markAsCompleted(appointmentId) {
    if (confirm('Marquer ce rendez-vous comme terminé ?')) {
        updateAppointmentStatus(appointmentId, 'completed');
    }
}

function updateAppointmentStatus(appointmentId, status) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/appointments/${appointmentId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Auto-refresh toutes les 2 minutes pour les rendez-vous d'aujourd'hui
setInterval(function() {
    if (!document.activeElement || document.activeElement.tagName !== 'INPUT') {
        location.reload();
    }
}, 120000); // 2 minutes
</script>
@endpush
