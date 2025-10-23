@extends('layouts.secretary')

@section('title', 'Rendez-vous de la semaine - Secrétariat')
@section('page-title', 'Rendez-vous de la semaine')
@section('page-subtitle', 'Planning des rendez-vous de la semaine')
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

    <!-- Statistiques de la semaine -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-week text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAppointments }}</h4>
                            <p class="text-muted mb-0">Total semaine</p>
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
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingCount }}</h4>
                            <p class="text-muted mb-0">En attente</p>
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
                        <div class="stat-icon bg-info">
                            <i class="fas fa-check-double text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $completedCount }}</h4>
                            <p class="text-muted mb-0">Terminés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Planning par jour -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-week me-2"></i>Planning de la semaine du {{ \Carbon\Carbon::parse($startOfWeek)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endOfWeek)->format('d/m/Y') }}
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshAppointments()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <a href="{{ route('secretary.appointments.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Nouveau RDV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointmentsByDay->count() > 0)
                        @foreach($appointmentsByDay as $date => $dayAppointments)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="mb-0 me-3">
                                        <i class="fas fa-calendar-day text-primary me-2"></i>
                                        {{ \Carbon\Carbon::parse($date)->format('l d/m/Y') }}
                                    </h6>
                                    <span class="badge bg-primary">{{ $dayAppointments->count() }} rendez-vous</span>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Heure</th>
                                                <th>Patient</th>
                                                <th>Service</th>
                                                <th>Médecin</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dayAppointments as $appointment)
                                                <tr class="{{ $appointment->is_urgent ? 'table-warning' : '' }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-clock text-primary me-2"></i>
                                                            <strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="patient-avatar me-2">
                                                                <div class="avatar bg-primary text-white">
                                                                    {{ strtoupper(substr($appointment->user->first_name, 0, 1)) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <strong>{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</strong>
                                                                @if($appointment->is_urgent)
                                                                    <span class="badge bg-danger ms-1">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                    </span>
                                                                @endif
                                                                <br>
                                                                <small class="text-muted">{{ $appointment->user->phone }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-stethoscope text-info me-2"></i>
                                                            {{ $appointment->service->name ?? 'Service non spécifié' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user-md text-success me-2"></i>
                                                            {{ $appointment->doctor ? $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'Non assigné' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                                            <i class="fas fa-{{ $appointment->status == 'confirmed' ? 'check' : ($appointment->status == 'pending' ? 'clock' : ($appointment->status == 'completed' ? 'check-double' : 'times')) }} me-1"></i>
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-info" 
                                                                    onclick="viewAppointment({{ $appointment->id }})" 
                                                                    title="Voir les détails">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @if($appointment->service_id == $secretary->service_id)
                                                                @if($appointment->status == 'pending')
                                                                    <button type="button" class="btn btn-outline-success" 
                                                                            onclick="confirmAppointment({{ $appointment->id }})" 
                                                                            title="Confirmer">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-danger" 
                                                                            onclick="cancelAppointment({{ $appointment->id }})" 
                                                                            title="Annuler">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                @elseif($appointment->status == 'confirmed')
                                                                    <button type="button" class="btn btn-outline-info" 
                                                                            onclick="markAsCompleted({{ $appointment->id }})" 
                                                                            title="Marquer comme terminé">
                                                                        <i class="fas fa-check-double"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-danger" 
                                                                            onclick="cancelAppointment({{ $appointment->id }})" 
                                                                            title="Annuler">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <span class="text-muted small">Autre service</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous cette semaine</h5>
                            <p class="text-muted">Aucun rendez-vous n'est programmé pour cette semaine dans votre service.</p>
                            <a href="{{ route('secretary.appointments.create') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Créer un nouveau rendez-vous
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Détails du rendez-vous
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
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

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
// Actualiser la page
function refreshAppointments() {
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
    
    // Récupérer les détails du rendez-vous via AJAX
    fetch(`/appointments/${appointmentId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug log
            if (data.status && data.data) {
                const appointment = data.data;
        document.getElementById('appointmentDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Informations du patient</h6>
                            <div class="mb-3">
                                <strong>Nom complet:</strong><br>
                                ${appointment.user.first_name} ${appointment.user.last_name}
                            </div>
                            <div class="mb-3">
                                <strong>Téléphone:</strong><br>
                                ${appointment.user.phone_number || 'Non renseigné'}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                ${appointment.user.email}
                            </div>
                            <div class="mb-3">
                                <strong>Date de naissance:</strong><br>
                                ${appointment.user.day_of_birth ? new Date(appointment.user.day_of_birth).toLocaleDateString('fr-FR') : 'Non renseignée'}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar me-2"></i>Détails du rendez-vous</h6>
                            <div class="mb-3">
                                <strong>Date:</strong><br>
                                ${new Date(appointment.appointment_date).toLocaleDateString('fr-FR')}
                            </div>
                            <div class="mb-3">
                                <strong>Heure:</strong><br>
                                ${appointment.appointment_time}
                            </div>
                            <div class="mb-3">
                                <strong>Service:</strong><br>
                                ${appointment.service ? appointment.service.name : 'Service non spécifié'}
                            </div>
                            <div class="mb-3">
                                <strong>Statut:</strong><br>
                                <span class="badge bg-${appointment.status == 'confirmed' ? 'success' : (appointment.status == 'pending' ? 'warning' : (appointment.status == 'completed' ? 'info' : 'danger'))}">
                                    ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                                </span>
                            </div>
                            ${appointment.reason ? `
                            <div class="mb-3">
                                <strong>Motif:</strong><br>
                                ${appointment.reason}
                            </div>
                            ` : ''}
                            ${appointment.symptoms ? `
                            <div class="mb-3">
                                <strong>Symptômes:</strong><br>
                                ${appointment.symptoms}
                            </div>
                            ` : ''}
                            ${appointment.notes ? `
                            <div class="mb-3">
                                <strong>Notes:</strong><br>
                                ${appointment.notes}
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6><i class="fas fa-info-circle me-2"></i>Informations supplémentaires</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Créé le:</strong> ${new Date(appointment.created_at).toLocaleDateString('fr-FR')} à ${new Date(appointment.created_at).toLocaleTimeString('fr-FR')}
                </div>
                <div class="col-md-6">
                                    <strong>Modifié le:</strong> ${new Date(appointment.updated_at).toLocaleDateString('fr-FR')} à ${new Date(appointment.updated_at).toLocaleTimeString('fr-FR')}
                                </div>
                            </div>
                </div>
            </div>
        `;
            } else {
                document.getElementById('appointmentDetails').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur lors du chargement des détails: ${data.message || 'Erreur inconnue'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('appointmentDetails').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors du chargement des détails du rendez-vous: ${error.message}
                </div>
            `;
        });
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
    
    fetch(`/appointments/${appointmentId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PATCH'
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

// Auto-refresh toutes les 5 minutes
setInterval(function() {
    // Ne pas actualiser si l'utilisateur est en train de remplir un formulaire
    if (!document.activeElement || document.activeElement.tagName !== 'INPUT') {
        location.reload();
    }
}, 300000); // 5 minutes
</script>
@endpush
