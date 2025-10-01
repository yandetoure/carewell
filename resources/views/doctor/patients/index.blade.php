@extends('layouts.doctor')

@section('title', 'Mes Patients - Docteur')
@section('page-title', 'Mes Patients')
@section('page-subtitle', 'Gestion de vos patients')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPatients ?? 0 }}</h4>
                            <p class="text-muted mb-0">Patients totaux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-user-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $activePatients ?? 0 }}</h4>
                            <p class="text-muted mb-0">Patients actifs (3 mois)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $recentPatients ?? 0 }}</h4>
                            <p class="text-muted mb-0">Nouveaux (30 jours)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des patients -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Patients avec rendez-vous dans votre service
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                        <a href="{{ route('doctor.patients.new') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Nouveau patient
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Informations</th>
                                        <th>Dernier RDV</th>
                                        <th>Prochain RDV</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                    @php
                                        $lastAppointment = $patient->appointments->where('status', 'confirmed')->sortByDesc('appointment_date')->first();
                                        $nextAppointment = $patient->appointments->where('status', 'confirmed')->where('appointment_date', '>=', now())->sortBy('appointment_date')->first();
                                        $totalAppointments = $patient->appointments->count();
                                        $confirmedAppointments = $patient->appointments->where('status', 'confirmed')->count();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($patient->photo)
                                                    <img src="{{ asset('storage/' . $patient->photo) }}" 
                                                         alt="Photo patient" 
                                                         class="rounded-circle me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user text-white fa-lg"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                                    <small class="text-muted">{{ $patient->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>{{ $patient->phone_number ?? 'N/A' }}
                                                </small><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-birthday-cake me-1"></i>
                                                    @if($patient->age)
                                                        {{ $patient->age }} ans
                                                    @else
                                                        N/A
                                                    @endif
                                                </small><br>
                                                <small class="text-info">
                                                    <i class="fas fa-calendar-check me-1"></i>{{ $totalAppointments }} RDV total
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($lastAppointment)
                                                <div>
                                                    <strong>{{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}</strong><br>
                                                    <small class="text-muted">{{ $lastAppointment->appointment_time }}</small><br>
                                                    @if($lastAppointment->service)
                                                        <span class="badge bg-primary">{{ $lastAppointment->service->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Aucun RDV confirmé</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($nextAppointment)
                                                <div>
                                                    <strong class="text-success">{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('d/m/Y') }}</strong><br>
                                                    <small class="text-muted">{{ $nextAppointment->appointment_time }}</small><br>
                                                    @if($nextAppointment->service)
                                                        <span class="badge bg-success">{{ $nextAppointment->service->name }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Aucun RDV à venir</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($confirmedAppointments > 0)
                                                @if($nextAppointment && \Carbon\Carbon::parse($nextAppointment->appointment_date)->isFuture())
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-warning">En pause</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $confirmedAppointments }}/{{ $totalAppointments }} confirmés</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('doctor.patients.show', $patient) }}" 
                                                   class="btn btn-info" 
                                                   title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('medical-files.show', $patient->id) }}" 
                                                   class="btn btn-warning" 
                                                   title="Voir le dossier médical">
                                                    <i class="fas fa-file-medical"></i>
                                                </a>
                                                <a href="{{ route('doctor.patients.history', $patient) }}" 
                                                   class="btn btn-secondary" 
                                                   title="Historique des RDV">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                <a href="{{ route('doctor.patients.appointments', $patient) }}" 
                                                   class="btn btn-success" 
                                                   title="Gérer les RDV">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
                                                <button class="btn btn-warning" 
                                                        onclick="sendMessage({{ $patient->id }})" 
                                                        title="Envoyer message">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $patients->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Aucun patient trouvé</h5>
                            <p class="text-muted">Vous n'avez pas encore de patients avec des rendez-vous dans votre service.</p>
                            <a href="{{ route('doctor.patients.new') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Ajouter un nouveau patient
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour l'historique des RDV -->
<div class="modal fade" id="patientHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historique des rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="patientHistoryContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshData() {
    location.reload();
}

function viewPatientHistory(patientId) {
    const modal = new bootstrap.Modal(document.getElementById('patientHistoryModal'));
    modal.show();
    
    // Simuler le chargement des données
    document.getElementById('patientHistoryContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-history fa-2x text-primary mb-3"></i>
            <h6>Historique des rendez-vous</h6>
            <p class="text-muted">Fonctionnalité en cours de développement</p>
            <small class="text-info">Patient ID: ${patientId}</small>
        </div>
    `;
}

function createAppointment(patientId) {
    if (confirm('Créer un nouveau rendez-vous pour ce patient ?')) {
        // Rediriger vers la page de création de RDV
        window.location.href = `/appointments/create?patient_id=${patientId}`;
    }
}

function sendMessage(patientId) {
    if (confirm('Envoyer un message à ce patient ?')) {
        // Rediriger vers la page de messagerie
        window.location.href = `/messages?patient_id=${patientId}`;
    }
}

// Auto-refresh toutes les 2 minutes pour les données critiques
setInterval(function() {
    // Rafraîchir seulement si la page est visible
    if (!document.hidden) {
        location.reload();
    }
}, 120000); // 2 minutes
</script>

<style>
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.7rem;
}

.fa-lg {
    font-size: 1.1em;
}
</style>
@endsection
