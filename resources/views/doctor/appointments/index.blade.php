@extends('layouts.doctor')

@section('title', 'Mes Rendez-vous - Docteur')
@section('page-title', 'Mes Rendez-vous')
@section('page-subtitle', 'Gestion de vos rendez-vous')
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Alertes pour les rendez-vous urgents -->
    @if($urgentAppointments > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Attention !</strong> Vous avez {{ $urgentAppointments }} rendez-vous urgent(s) nécessitant votre attention immédiate.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayAppointments }}</h4>
                            <p class="text-muted mb-0">Aujourd'hui</p>
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
                            <h4 class="mb-1">{{ $pendingAppointments }}</h4>
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
                            <h4 class="mb-1">{{ $confirmedAppointments }}</h4>
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
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $urgentAppointments }}</h4>
                            <p class="text-muted mb-0">Urgents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste des rendez-vous -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Tous mes rendez-vous
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="statusFilter" onchange="filterAppointments()">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="confirmed">Confirmés</option>
                                <option value="cancelled">Annulés</option>
                                <option value="completed">Terminés</option>
                            </select>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshAppointments()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="appointmentsTable">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr class="appointment-row {{ $appointment->is_urgent ? 'table-warning' : '' }}" 
                                            data-status="{{ $appointment->status }}">
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
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-secondary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $appointment->service->name ?? 'Service non spécifié' }}
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
                                                    @if($appointment->status == 'pending')
                                                        <button type="button" class="btn btn-outline-success" 
                                                                onclick="confirmAppointment({{ $appointment->id }})" 
                                                                title="Confirmer">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    @if($appointment->status == 'confirmed')
                                                        <button type="button" class="btn btn-outline-info" 
                                                                onclick="markAsCompleted({{ $appointment->id }})" 
                                                                title="Marquer comme terminé">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    @endif
                                                    @if($appointment->status != 'cancelled' && $appointment->status != 'completed')
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="cancelAppointment({{ $appointment->id }})" 
                                                                title="Annuler">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous trouvé</h5>
                            <p class="text-muted">Vous n'avez pas encore de rendez-vous programmés.</p>
                            <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Gérer mes patients
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations utiles -->
        <div class="col-md-4">
            <!-- Rendez-vous d'aujourd'hui -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Rendez-vous d'aujourd'hui
                    </h5>
                </div>
                <div class="card-body">
                    @if($todayAppointmentsList->count() > 0)
                        @foreach($todayAppointmentsList as $appointment)
                            <div class="d-flex align-items-center mb-3 p-2 rounded {{ $appointment->is_urgent ? 'bg-warning bg-opacity-10' : 'bg-light' }}">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-primary text-white">
                                        {{ strtoupper(substr($appointment->user->first_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                        <span class="mx-1">•</span>
                                        {{ $appointment->service->name ?? 'Service' }}
                                    </small>
                                    @if($appointment->is_urgent)
                                        <span class="badge bg-danger ms-1">
                                            <i class="fas fa-exclamation-triangle"></i> Urgent
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>Aucun rendez-vous aujourd'hui</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prochains rendez-vous -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Prochains rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->count() > 0)
                        @foreach($upcomingAppointments as $appointment)
                            <div class="d-flex align-items-center mb-3 p-2 rounded bg-light">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-success text-white">
                                        {{ strtoupper(substr($appointment->user->first_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        <span class="mx-1">•</span>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <p>Aucun prochain rendez-vous</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.patients') }}" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Mes patients
                        </a>
                        <a href="{{ route('doctor.patients.new') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Nouveau patient
                        </a>
                        <a href="{{ route('doctor.appointments.today') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-day me-2"></i>Rendez-vous d'aujourd'hui
                        </a>
                        <a href="{{ route('doctor.appointments.week') }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-week me-2"></i>Cette semaine
                        </a>
                    </div>
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

.appointment-row.hidden {
    display: none;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
// Filtrage des rendez-vous
function filterAppointments() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.appointment-row');
    
    rows.forEach(row => {
        if (status === '' || row.dataset.status === status) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

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

// Auto-refresh toutes les 5 minutes
setInterval(function() {
    // Ne pas actualiser si l'utilisateur est en train de remplir un formulaire
    if (!document.activeElement || document.activeElement.tagName !== 'INPUT') {
        location.reload();
    }
}, 300000); // 5 minutes
</script>
@endpush
