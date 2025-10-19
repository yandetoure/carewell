@extends('layouts.nurse')

@section('title', 'Gestion des Médicaments - CareWell')
@section('page-title', 'Gestion des Médicaments')
@section('page-subtitle', 'Gérer les médicaments des patients hospitalisés')
@section('user-role', 'Infirmière')

@section('content')
<div class="container-fluid py-4">
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-white bg-opacity-25 me-3">
                            <i class="fas fa-bed text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $totalHospitalized }}</h4>
                            <small>Patients Hospitalisés</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-white bg-opacity-25 me-3">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $pendingPrescriptions }}</h4>
                            <small>En Attente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-white bg-opacity-25 me-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $completedPrescriptions }}</h4>
                            <small>Prescriptions Terminées</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-white bg-opacity-25 me-3">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $prescriptions->count() }}</h4>
                            <small>Total Prescriptions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patients Hospitalisés -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bed me-2"></i>Patients Hospitalisés
                    </h5>
                </div>
                <div class="card-body">
                    @if($hospitalizedPatients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Lit</th>
                                        <th>Chambre</th>
                                        <th>Prescriptions</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalizedPatients as $patient)
                                        @php
                                            $currentBed = $patient->medicalFile->beds->where('status', 'occupe')->first();
                                            $patientPrescriptions = $patient->medicalFile->medicalprescription ?? collect();
                                            $pendingCount = $patientPrescriptions->where('is_done', false)->count();
                                        @endphp
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
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-bed me-1"></i>
                                                    {{ $currentBed->bed_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $currentBed->room_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-2">{{ $patientPrescriptions->count() }}</span>
                                                    @if($pendingCount > 0)
                                                        <span class="badge bg-warning">{{ $pendingCount }} en cours</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($pendingCount > 0)
                                                    <span class="badge bg-warning">Médicaments en cours</span>
                                                @else
                                                    <span class="badge bg-success">À jour</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir les prescriptions" onclick="viewPrescriptions({{ $patient->id }})">
                                                        <i class="fas fa-pills"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Administrer médicament" onclick="administerMedication({{ $patient->id }})">
                                                        <i class="fas fa-syringe"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" title="Signes vitaux" onclick="viewVitalSigns({{ $patient->id }})">
                                                        <i class="fas fa-heartbeat"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun Patient Hospitalisé</h5>
                            <p class="text-muted">Il n'y a actuellement aucun patient hospitalisé.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions Récentes -->
    @if($prescriptions->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Prescriptions Récentes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Médicament</th>
                                    <th>Dosage</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescriptions->take(10) as $prescription)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle fa-lg text-primary me-2"></i>
                                                <div>
                                                    <div class="fw-bold">{{ $prescription->medicalFile->user->first_name }} {{ $prescription->medicalFile->user->last_name }}</div>
                                                    <small class="text-muted">Lit {{ $prescription->medicalFile->beds->first()->bed_number ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $prescription->prescription->name ?? 'Prescription #' . $prescription->id }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $prescription->dosage ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td>
                                            @if($prescription->status === 'administered')
                                                <span class="badge bg-success">Administré</span>
                                            @else
                                                <span class="badge bg-warning">En Attente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($prescription->status === 'pending')
                                                    <button type="button" class="btn btn-outline-success" title="Marquer comme administré" onclick="markAsAdministered({{ $prescription->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-primary" title="Voir détails" onclick="viewPrescriptionDetails({{ $prescription->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal pour voir les prescriptions d'un patient -->
<div class="modal fade" id="prescriptionsModal" tabindex="-1" aria-labelledby="prescriptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="prescriptionsModalLabel">Prescriptions du Patient</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="prescriptionsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
        </div>
    </div>
</div>

<!-- Modal pour administrer un médicament -->
<div class="modal fade" id="administerModal" tabindex="-1" aria-labelledby="administerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="administerModalLabel">Administrer Médicament</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="administerContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="confirmAdministration()">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les signes vitaux -->
<div class="modal fade" id="vitalSignsModal" tabindex="-1" aria-labelledby="vitalSignsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
</style>
@endpush

@push('scripts')
<script>
// Variables globales
let currentPatientId = null;
let currentPrescriptionId = null;

// Fonction pour voir les prescriptions d'un patient
function viewPrescriptions(patientId) {
    currentPatientId = patientId;
    
    fetch(`/nurse/patients/${patientId}/prescriptions`, {
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
            document.getElementById('prescriptionsContent').innerHTML = generatePrescriptionsHTML(data.prescriptions);
            const modal = new bootstrap.Modal(document.getElementById('prescriptionsModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement des prescriptions');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des prescriptions');
    });
}

// Fonction pour administrer un médicament
function administerMedication(patientId) {
    currentPatientId = patientId;
    
    fetch(`/nurse/patients/${patientId}/pending-prescriptions`, {
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
            document.getElementById('administerContent').innerHTML = generateAdministrationHTML(data.prescriptions);
            const modal = new bootstrap.Modal(document.getElementById('administerModal'));
            modal.show();
        } else {
            alert('Erreur lors du chargement des prescriptions en attente');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des prescriptions en attente');
    });
}

// Fonction pour marquer une prescription comme en cours
function markAsInProgress(prescriptionId) {
    if (confirm('Commencer le traitement de ce médicament ?')) {
        fetch(`/nurse/prescriptions/${prescriptionId}/mark-in-progress`, {
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
                alert('Traitement commencé !');
                window.location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la mise à jour');
        });
    }
}

// Fonction pour marquer une prescription comme administrée
function markAsAdministered(prescriptionId) {
    if (confirm('Confirmer l\'administration de ce médicament ?')) {
        fetch(`/nurse/prescriptions/${prescriptionId}/mark-administered`, {
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
                alert('Médicament marqué comme administré !');
                // Recharger les prescriptions du patient dans le modal
                if (currentPatientId) {
                    viewPrescriptions(currentPatientId);
                } else {
                    window.location.reload();
                }
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la mise à jour');
        });
    }
}

// Fonction pour voir les détails d'une prescription
function viewPrescriptionDetails(prescriptionId) {
    alert('Fonctionnalité de détails de prescription à implémenter');
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
            document.getElementById('vitalSignsContent').innerHTML = generateVitalSignsHTML(data.vitalSigns, data.patient);
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

function generateVitalSignsHTML(vitalSigns, patient) {
    let html = `
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6 class="mb-1"><i class="fas fa-user-md me-2"></i>Patient: ${patient.first_name} ${patient.last_name}</h6>
                    <small>Enregistrer de nouveaux signes vitaux</small>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nouveaux Signes Vitaux</h6>
            </div>
            <div class="card-body">
                <form id="vitalSignsForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="bloodPressureSystolic" class="form-label">
                                    <i class="fas fa-heartbeat text-danger me-1"></i>Pression Systolique (mmHg)
                                </label>
                                <input type="number" class="form-control" id="bloodPressureSystolic" min="50" max="300" step="1" placeholder="120">
                            </div>
                            <div class="mb-3">
                                <label for="bloodPressureDiastolic" class="form-label">
                                    <i class="fas fa-heartbeat text-danger me-1"></i>Pression Diastolique (mmHg)
                                </label>
                                <input type="number" class="form-control" id="bloodPressureDiastolic" min="30" max="200" step="1" placeholder="80">
                            </div>
                            <div class="mb-3">
                                <label for="heartRate" class="form-label">
                                    <i class="fas fa-heart text-danger me-1"></i>Fréquence Cardiaque (BPM)
                                </label>
                                <input type="number" class="form-control" id="heartRate" min="30" max="200" step="1" placeholder="72">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="temperature" class="form-label">
                                    <i class="fas fa-thermometer-half text-warning me-1"></i>Température (°C)
                                </label>
                                <input type="number" class="form-control" id="temperature" min="30" max="45" step="0.1" placeholder="36.5">
                            </div>
                            <div class="mb-3">
                                <label for="oxygenSaturation" class="form-label">
                                    <i class="fas fa-lungs text-info me-1"></i>Saturation en Oxygène (%)
                                </label>
                                <input type="number" class="form-control" id="oxygenSaturation" min="70" max="100" step="1" placeholder="98">
                            </div>
                            <div class="mb-3">
                                <label for="respiratoryRate" class="form-label">
                                    <i class="fas fa-lungs text-info me-1"></i>Fréquence Respiratoire
                                </label>
                                <input type="number" class="form-control" id="respiratoryRate" min="8" max="40" step="1" placeholder="16">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="weight" class="form-label">
                                    <i class="fas fa-weight text-secondary me-1"></i>Poids (kg)
                                </label>
                                <input type="number" class="form-control" id="weight" min="1" max="500" step="0.1" placeholder="70">
                            </div>
                            <div class="mb-3">
                                <label for="height" class="form-label">
                                    <i class="fas fa-ruler-vertical text-secondary me-1"></i>Taille (cm)
                                </label>
                                <input type="number" class="form-control" id="height" min="30" max="250" step="0.1" placeholder="175">
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note text-dark me-1"></i>Notes
                                </label>
                                <textarea class="form-control" id="notes" rows="3" placeholder="Observations additionnelles..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    if (vitalSigns && vitalSigns.length > 0) {
        html += `
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Historique des Signes Vitaux</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-calendar me-1"></i>Date/Heure</th>
                                    <th><i class="fas fa-heartbeat me-1"></i>Tension</th>
                                    <th><i class="fas fa-heart me-1"></i>FC</th>
                                    <th><i class="fas fa-thermometer-half me-1"></i>Temp</th>
                                    <th><i class="fas fa-lungs me-1"></i>O2</th>
                                    <th><i class="fas fa-lungs me-1"></i>RR</th>
                                    <th><i class="fas fa-weight me-1"></i>Poids</th>
                                    <th><i class="fas fa-ruler-vertical me-1"></i>Taille</th>
                                    <th><i class="fas fa-user-nurse me-1"></i>Infirmière</th>
                                </tr>
                            </thead>
                            <tbody>
        `;
        
        vitalSigns.forEach(vitalSign => {
            html += `
                <tr>
                    <td><small>${new Date(vitalSign.recorded_at).toLocaleString('fr-FR')}</small></td>
                    <td>
                        ${vitalSign.blood_pressure_systolic && vitalSign.blood_pressure_diastolic 
                            ? '<span class="badge bg-primary">' + vitalSign.blood_pressure_systolic + '/' + vitalSign.blood_pressure_diastolic + '</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${vitalSign.heart_rate 
                            ? '<span class="badge bg-danger">' + vitalSign.heart_rate + ' BPM</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${vitalSign.temperature 
                            ? '<span class="badge bg-warning">' + vitalSign.temperature + '°C</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${vitalSign.oxygen_saturation 
                            ? '<span class="badge bg-info">' + vitalSign.oxygen_saturation + '%</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${vitalSign.respiratory_rate 
                            ? '<span class="badge bg-secondary">' + vitalSign.respiratory_rate + '</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${vitalSign.weight 
                            ? '<span class="badge bg-dark">' + vitalSign.weight + 'kg</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td>
                        ${vitalSign.height 
                            ? '<span class="badge bg-dark">' + vitalSign.height + 'cm</span>' 
                            : '<span class="text-muted">-</span>'}
                    </td>
                    <td><small>${vitalSign.nurse ? vitalSign.nurse.first_name + ' ' + vitalSign.nurse.last_name : '-'}</small></td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        
        if (vitalSigns.length > 0) {
            html += `
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Total: ${vitalSigns.length} enregistrement(s) trouvé(s)
                    </small>
                </div>
            `;
        }
        
        html += '</div></div>';
    } else {
        html += `
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Aucun enregistrement de signes vitaux</h6>
                    <p class="text-muted mb-0">Les premiers signes vitaux apparaîtront ici après enregistrement.</p>
                </div>
            </div>
        `;
    }
    
    return html;
}

// Fonctions de génération HTML
function generatePrescriptionsHTML(prescriptions) {
    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Médicament</th><th>Dosage</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead><tbody>';
    
    prescriptions.forEach(prescription => {
        html += `
            <tr>
                <td>${prescription.prescription?.name || 'Prescription #' + prescription.id}</td>
                <td>${prescription.dosage || 'N/A'}</td>
                <td>${new Date(prescription.created_at).toLocaleDateString('fr-FR')}</td>
                <td>${getStatusBadge(prescription.is_done ? 'administered' : 'pending')}</td>
                <td>
                    ${getActionButtons(prescription.is_done ? 'administered' : 'pending', prescription.id)}
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    return html;
}

function generateAdministrationHTML(prescriptions) {
    let html = '<div class="mb-3"><label for="selectedPrescription">Sélectionner le médicament à administrer:</label><select class="form-select" id="selectedPrescription">';
    
    prescriptions.forEach(prescription => {
        html += `<option value="${prescription.id}">${prescription.prescription?.name || 'Prescription #' + prescription.id} - ${prescription.dosage || 'N/A'}</option>`;
    });
    
    html += '</select></div><div class="mb-3"><label for="administrationNotes">Notes d\'administration:</label><textarea class="form-control" id="administrationNotes" rows="3" placeholder="Ajouter des notes..."></textarea></div>';
    
    return html;
}

// Fonctions utilitaires pour les statuts et boutons
function getStatusBadge(status) {
    switch(status) {
        case 'administered':
            return '<span class="badge bg-success">Administré</span>';
        case 'pending':
        default:
            return '<span class="badge bg-warning">En Attente</span>';
    }
}

function getActionButtons(status, prescriptionId) {
    let buttons = '';
    
    if (status === 'pending') {
        buttons += '<button class="btn btn-sm btn-success" onclick="markAsAdministered(' + prescriptionId + ')" title="Marquer comme administré"><i class="fas fa-check"></i></button>';
    }
    
    buttons += '<button class="btn btn-sm btn-primary" onclick="viewPrescriptionDetails(' + prescriptionId + ')" title="Voir détails"><i class="fas fa-eye"></i></button>';
    
    return buttons;
}

function recordVitalSigns() {
    const formData = {
        blood_pressure_systolic: document.getElementById('bloodPressureSystolic').value,
        blood_pressure_diastolic: document.getElementById('bloodPressureDiastolic').value,
        heart_rate: document.getElementById('heartRate').value,
        temperature: document.getElementById('temperature').value,
        oxygen_saturation: document.getElementById('oxygenSaturation').value,
        respiratory_rate: document.getElementById('respiratoryRate').value,
        weight: document.getElementById('weight').value,
        height: document.getElementById('height').value,
        notes: document.getElementById('notes').value
    };
    
    // Vérifier qu'au moins un champ est rempli
    const hasData = Object.values(formData).some(value => value && value.trim() !== '');
    
    if (!hasData) {
        alert('Veuillez remplir au moins un champ des signes vitaux');
        return;
    }
    
    fetch(`/nurse/patients/${currentPatientId}/vital-signs`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Signes vitaux enregistrés avec succès !');
            // Recharger les signes vitaux
            viewVitalSigns(currentPatientId);
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'enregistrement des signes vitaux');
    });
}

// Fonctions des boutons des modals

function confirmAdministration() {
    const selectedPrescription = document.getElementById('selectedPrescription').value;
    const notes = document.getElementById('administrationNotes').value;
    
    if (!selectedPrescription) {
        alert('Veuillez sélectionner un médicament');
        return;
    }
    
    fetch(`/nurse/prescriptions/${selectedPrescription}/administer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
            if (data.success) {
                alert('Médicament administré avec succès !');
                bootstrap.Modal.getInstance(document.getElementById('administerModal')).hide();
                // Recharger les prescriptions du patient dans le modal
                if (currentPatientId) {
                    viewPrescriptions(currentPatientId);
                } else {
                    window.location.reload();
                }
            } else {
                alert('Erreur: ' + data.message);
            }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'administration');
    });
}
</script>
@endpush
