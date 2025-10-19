@extends('layouts.nurse')

@section('title', 'Gestion des Patients - CareWell')
@section('page-title', 'Gestion des Patients')
@section('page-subtitle', 'Gérer les Soins et Dossiers des Patients')
@section('user-role', 'Infirmière')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPatients }}</h4>
                            <p class="text-muted mb-0">Total Patients</p>
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
                            <i class="fas fa-bed text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $hospitalizedCount }}</h4>
                            <p class="text-muted mb-0">Hospitalized</p>
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
                            <h4 class="mb-1">{{ $todayAppointmentsCount }}</h4>
                            <p class="text-muted mb-0">Today's Appointments</p>
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
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingPrescriptions }}</h4>
                            <p class="text-muted mb-0">Pending Prescriptions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Appointments
                    </h5>
                </div>
                <div class="card-body">
                    @if($todayAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    {{ $appointment->user->first_name }} {{ $appointment->user->last_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $appointment->service->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmé</span>
                                                @elseif($appointment->status == 'pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge bg-info">Terminé</span>
                                                @elseif($appointment->status == 'cancelled')
                                                    <span class="badge bg-danger">Annulé</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir les détails" onclick="viewAppointmentDetails({{ $appointment->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Commencer les soins" onclick="startCare({{ $appointment->id }})">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-day fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No appointments scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Patient List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>All Patients
                    </h5>
                </div>
                <div class="card-body">
                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Contact</th>
                                        <th>Dernier Rendez-vous</th>
                                        <th>Statut d'Hospitalisation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                                        <small class="text-muted">{{ $patient->identification_number ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-phone text-success me-2"></i>
                                                    {{ $patient->phone_number ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($patient->appointments->count() > 0)
                                                    @php $lastAppointment = $patient->appointments->sortByDesc('appointment_date')->first(); @endphp
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar text-info me-2"></i>
                                                        {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">No appointments</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    // Logique réelle : un patient est hospitalisé s'il a un lit occupé
                                                    $isHospitalized = $patient->medicalFile && $patient->medicalFile->beds->where('status', 'occupe')->count() > 0;
                                                    $currentBed = $isHospitalized ? $patient->medicalFile->beds->where('status', 'occupe')->first() : null;
                                                @endphp
                                                
                                                @if($isHospitalized && $currentBed)
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fas fa-bed text-warning"></i>
                                                        </div>
                                                        <div>
                                                            <span class="badge status-hospitalized">Hospitalisé</span>
                                                            <div class="small text-muted mt-1">
                                                                Lit {{ $currentBed->bed_number }}
                                                                @if($currentBed->room_number)
                                                                    - Chambre {{ $currentBed->room_number }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fas fa-home text-success"></i>
                                                        </div>
                                                        <div>
                                                            <span class="badge status-ambulatory">Ambulatoire</span>
                                                            <div class="small text-muted mt-1">Patient externe</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir le profil" onclick="viewPatientProfile({{ $patient->id }})">
                                                        <i class="fas fa-user"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" title="Dossier médical" onclick="viewMedicalFile({{ $patient->id }})">
                                                        <i class="fas fa-file-medical"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Signes vitaux" onclick="viewVitalSigns({{ $patient->id }})">
                                                        <i class="fas fa-heartbeat"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning" title="Attribuer un lit" onclick="assignBed({{ $patient->id }})">
                                                        <i class="fas fa-bed"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $patients->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Patients Found</h5>
                            <p class="text-muted">No patients have been registered yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Hospitalized Patients -->
    @if($hospitalizedPatients->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bed me-2"></i>Hospitalized Patients
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($hospitalizedPatients as $patient)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle fa-2x text-warning me-3"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-bed me-1"></i>
                                                    @php $currentBed = $patient->medicalFile->beds->where('status', 'occupe')->first(); @endphp
                                                    Lit {{ $currentBed->bed_number ?? 'N/A' }}
                                                    @if($currentBed && $currentBed->room_number)
                                                        - Chambre {{ $currentBed->room_number }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="viewVitalSigns({{ $patient->id }})">
                                                <i class="fas fa-heartbeat me-1"></i>Signes Vitaux
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="viewMedicalFile({{ $patient->id }})">
                                                <i class="fas fa-pills me-1"></i>Médicaments
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="dischargePatient({{ $patient->id }})">
                                                <i class="fas fa-sign-out-alt me-1"></i>Sortir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal pour les détails du patient -->
<div class="modal fade" id="patientDetailsModal" tabindex="-1" aria-labelledby="patientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="patientDetailsModalLabel">Détails du Patient</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="patientDetailsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="editPatient()">Modifier</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le dossier médical -->
<div class="modal fade" id="medicalFileModal" tabindex="-1" aria-labelledby="medicalFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="medicalFileModalLabel">Dossier Médical</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="medicalFileContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-info" onclick="addMedicalNote()">Ajouter une note</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les signes vitaux -->
<div class="modal fade" id="vitalSignsModal" tabindex="-1" aria-labelledby="vitalSignsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="vitalSignsModalLabel">Signes Vitaux</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="vitalSignsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" onclick="recordVitalSigns()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour attribuer un lit -->
<div class="modal fade" id="assignBedModal" tabindex="-1" aria-labelledby="assignBedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="assignBedModalLabel">Attribuer un Lit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="assignBedContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" onclick="confirmBedAssignment()">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails du rendez-vous -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="appointmentDetailsModalLabel">Détails du Rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentDetailsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="updateAppointmentStatus()">Mettre à jour</button>
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

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.patient-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-header h5 {
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.status-hospitalized {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    color: #000;
    font-weight: 600;
}

.status-ambulatory {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: #fff;
    font-weight: 600;
}

.status-icon {
    font-size: 1.1rem;
    margin-right: 0.5rem;
}

.patient-status-card {
    border-left: 4px solid;
    padding-left: 1rem;
}

.patient-status-card.hospitalized {
    border-left-color: #ffc107;
    background-color: rgba(255, 193, 7, 0.1);
}

.patient-status-card.ambulatory {
    border-left-color: #28a745;
    background-color: rgba(40, 167, 69, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
// Variables globales
let currentPatientId = null;
let currentAppointmentId = null;

// Fonction pour voir le profil du patient
function viewPatientProfile(patientId) {
    currentPatientId = patientId;
    
    // Charger les détails du patient via AJAX
    fetch(`/nurse/patients/${patientId}/details`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('patientDetailsContent').innerHTML = generatePatientDetailsHTML(data.patient);
            const modal = new bootstrap.Modal(document.getElementById('patientDetailsModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement des détails du patient');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des détails du patient');
    });
}

// Fonction pour voir le dossier médical
function viewMedicalFile(patientId) {
    currentPatientId = patientId;
    
    fetch(`/nurse/patients/${patientId}/medical-file`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('medicalFileContent').innerHTML = generateMedicalFileHTML(data.medicalFile);
            const modal = new bootstrap.Modal(document.getElementById('medicalFileModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement du dossier médical');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement du dossier médical');
    });
}

// Fonction pour voir les signes vitaux
function viewVitalSigns(patientId) {
    currentPatientId = patientId;
    
    fetch(`/nurse/patients/${patientId}/vital-signs`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('vitalSignsContent').innerHTML = generateVitalSignsHTML(data.vitalSigns);
            const modal = new bootstrap.Modal(document.getElementById('vitalSignsModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement des signes vitaux');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des signes vitaux');
    });
}

// Fonction pour attribuer un lit
function assignBed(patientId) {
    currentPatientId = patientId;
    
    fetch(`/nurse/beds/available`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('assignBedContent').innerHTML = generateBedAssignmentHTML(data.beds);
            const modal = new bootstrap.Modal(document.getElementById('assignBedModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement des lits disponibles');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des lits disponibles');
    });
}

// Fonction pour voir les détails du rendez-vous
function viewAppointmentDetails(appointmentId) {
    currentAppointmentId = appointmentId;
    
    fetch(`/nurse/appointments/${appointmentId}/details`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('appointmentDetailsContent').innerHTML = generateAppointmentDetailsHTML(data.appointment);
            const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement des détails du rendez-vous');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des détails du rendez-vous');
    });
}

// Fonction pour commencer les soins
function startCare(appointmentId) {
    if (confirm('Voulez-vous commencer les soins pour ce patient ?')) {
        // Rediriger vers la page de soins ou ouvrir un modal
        window.location.href = `/nurse/care/${appointmentId}`;
    }
}

// Fonctions de génération HTML (temporaires - à remplacer par du vrai contenu)
function generatePatientDetailsHTML(patient) {
    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Informations personnelles</h6>
                <p><strong>Nom:</strong> ${patient.first_name} ${patient.last_name}</p>
                <p><strong>Email:</strong> ${patient.email}</p>
                <p><strong>Téléphone:</strong> ${patient.phone_number || 'Non renseigné'}</p>
                <p><strong>Date de naissance:</strong> ${patient.day_of_birth || 'Non renseignée'}</p>
                <p><strong>Adresse:</strong> ${patient.adress || 'Non renseignée'}</p>
            </div>
            <div class="col-md-6">
                <h6>Informations médicales</h6>
                <p><strong>Groupe sanguin:</strong> ${patient.blood_type || 'Non renseigné'}</p>
                <p><strong>Taille:</strong> ${patient.height || 'Non renseignée'}</p>
                <p><strong>Poids:</strong> ${patient.weight || 'Non renseigné'}</p>
                <p><strong>Statut:</strong> ${patient.status || 'Actif'}</p>
            </div>
        </div>
    `;
}

function generateMedicalFileHTML(medicalFile) {
    return `
        <div class="row">
            <div class="col-md-12">
                <h6>Dossier médical #${medicalFile.id}</h6>
                <p><strong>Date de création:</strong> ${medicalFile.created_at}</p>
                <p><strong>Dernière mise à jour:</strong> ${medicalFile.updated_at}</p>
                
                <h6>Maladies diagnostiquées</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Maladie</th>
                                <th>Date de diagnostic</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucune maladie enregistrée</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h6>Prescriptions</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Médicament</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucune prescription enregistrée</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

function generateVitalSignsHTML(vitalSigns) {
    return `
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="bloodPressure">Tension artérielle</label>
                    <input type="text" class="form-control" id="bloodPressure" placeholder="120/80">
                </div>
                <div class="form-group mb-3">
                    <label for="heartRate">Fréquence cardiaque (BPM)</label>
                    <input type="number" class="form-control" id="heartRate" placeholder="72">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="temperature">Température (°C)</label>
                    <input type="number" step="0.1" class="form-control" id="temperature" placeholder="36.5">
                </div>
                <div class="form-group mb-3">
                    <label for="oxygenSaturation">Saturation en oxygène (%)</label>
                    <input type="number" class="form-control" id="oxygenSaturation" placeholder="98">
                </div>
            </div>
        </div>
        
        <h6>Historique des signes vitaux</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date/Heure</th>
                        <th>Tension</th>
                        <th>FC</th>
                        <th>Temp</th>
                        <th>O2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun enregistrement</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

function generateBedAssignmentHTML(beds) {
    let bedsHTML = '';
    if (beds && beds.length > 0) {
        beds.forEach(bed => {
            bedsHTML += `
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="bedSelection" id="bed${bed.id}" value="${bed.id}">
                    <label class="form-check-label" for="bed${bed.id}">
                        Lit ${bed.bed_number} - Chambre ${bed.room_number} - Service ${bed.service_name}
                    </label>
                </div>
            `;
        });
    } else {
        bedsHTML = '<p class="text-muted">Aucun lit disponible</p>';
    }
    
    return `
        <div class="mb-3">
            <label for="admissionReason">Motif d'hospitalisation</label>
            <textarea class="form-control" id="admissionReason" rows="3" placeholder="Décrivez le motif d'hospitalisation..."></textarea>
        </div>
        <div class="mb-3">
            <label for="expectedDuration">Durée prévue (jours)</label>
            <input type="number" class="form-control" id="expectedDuration" placeholder="7">
        </div>
        <h6>Sélectionner un lit</h6>
        ${bedsHTML}
    `;
}

function generateAppointmentDetailsHTML(appointment) {
    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Informations du rendez-vous</h6>
                <p><strong>Date:</strong> ${appointment.appointment_date}</p>
                <p><strong>Heure:</strong> ${appointment.appointment_time}</p>
                <p><strong>Service:</strong> ${appointment.service_name || 'N/A'}</p>
                <p><strong>Médecin:</strong> ${appointment.doctor_name || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <h6>Informations du patient</h6>
                <p><strong>Nom:</strong> ${appointment.patient_name}</p>
                <p><strong>Téléphone:</strong> ${appointment.patient_phone || 'Non renseigné'}</p>
                <p><strong>Statut:</strong> <span class="badge bg-${getStatusColor(appointment.status)}">${getStatusText(appointment.status)}</span></p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Notes</h6>
                <textarea class="form-control" rows="3" placeholder="Ajouter des notes..."></textarea>
            </div>
        </div>
    `;
}

// Fonctions utilitaires
function getStatusColor(status) {
    switch(status) {
        case 'confirmed': return 'success';
        case 'pending': return 'warning';
        case 'completed': return 'info';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'confirmed': return 'Confirmé';
        case 'pending': return 'En attente';
        case 'completed': return 'Terminé';
        case 'cancelled': return 'Annulé';
        default: return status;
    }
}

// Fonctions des boutons des modals
function editPatient() {
    alert('Fonctionnalité de modification du patient à implémenter');
}

function addMedicalNote() {
    alert('Fonctionnalité d\'ajout de note médicale à implémenter');
}

function recordVitalSigns() {
    const bloodPressure = document.getElementById('bloodPressure').value;
    const heartRate = document.getElementById('heartRate').value;
    const temperature = document.getElementById('temperature').value;
    const oxygenSaturation = document.getElementById('oxygenSaturation').value;
    
    if (!bloodPressure || !heartRate || !temperature || !oxygenSaturation) {
        alert('Veuillez remplir tous les champs des signes vitaux');
        return;
    }
    
    // Ici, vous feriez un appel AJAX pour sauvegarder les signes vitaux
    alert('Signes vitaux enregistrés avec succès');
    bootstrap.Modal.getInstance(document.getElementById('vitalSignsModal')).hide();
}

function confirmBedAssignment() {
    const selectedBed = document.querySelector('input[name="bedSelection"]:checked');
    const admissionReason = document.getElementById('admissionReason').value;
    const expectedDuration = document.getElementById('expectedDuration').value;
    
    if (!selectedBed) {
        alert('Veuillez sélectionner un lit');
        return;
    }
    
    if (!admissionReason) {
        alert('Veuillez indiquer le motif d\'hospitalisation');
        return;
    }
    
    // Appel AJAX pour attribuer le lit
    fetch(`/nurse/patients/${currentPatientId}/assign-bed`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            bed_id: selectedBed.value,
            admission_reason: admissionReason,
            expected_duration: expectedDuration || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Lit attribué avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('assignBedModal')).hide();
            // Recharger la page pour voir les changements
            window.location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'assignation du lit');
    });
}

function updateAppointmentStatus() {
    alert('Fonctionnalité de mise à jour du statut du rendez-vous à implémenter');
}

function dischargePatient(patientId) {
    if (confirm('Êtes-vous sûr de vouloir sortir ce patient de l\'hôpital ?')) {
        fetch(`/nurse/patients/${patientId}/discharge`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Patient sorti avec succès !');
                // Recharger la page pour voir les changements
                window.location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la sortie du patient');
        });
    }
}
</script>
@endpush
