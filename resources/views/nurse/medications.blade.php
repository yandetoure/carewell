@extends('layouts.nurse')

@section('title', 'Gestion des Médicaments - CareWell')
@section('page-title', 'Gestion des Médicaments')
@section('page-subtitle', 'Gérer les médicaments des patients hospitalisés')
@section('user-role', 'Nurse')

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
                            <small>Prescriptions en Cours</small>
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
                                            <span class="fw-bold">{{ $prescription->medication_name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $prescription->dosage ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td>
                                            @if($prescription->is_done)
                                                <span class="badge bg-success">Terminé</span>
                                            @else
                                                <span class="badge bg-warning">En cours</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if(!$prescription->is_done)
                                                    <button type="button" class="btn btn-outline-success" title="Marquer comme administré" onclick="markAsAdministered({{ $prescription->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-info" title="Voir détails" onclick="viewPrescriptionDetails({{ $prescription->id }})">
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
                <button type="button" class="btn btn-primary" onclick="addNewPrescription()">Nouvelle Prescription</button>
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

// Fonction pour voir les détails d'une prescription
function viewPrescriptionDetails(prescriptionId) {
    alert('Fonctionnalité de détails de prescription à implémenter');
}

// Fonction pour voir les signes vitaux
function viewVitalSigns(patientId) {
    alert('Fonctionnalité de signes vitaux à implémenter');
}

// Fonctions de génération HTML
function generatePrescriptionsHTML(prescriptions) {
    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Médicament</th><th>Dosage</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead><tbody>';
    
    prescriptions.forEach(prescription => {
        html += `
            <tr>
                <td>${prescription.medication_name || 'N/A'}</td>
                <td>${prescription.dosage || 'N/A'}</td>
                <td>${new Date(prescription.created_at).toLocaleDateString('fr-FR')}</td>
                <td>${prescription.is_done ? '<span class="badge bg-success">Terminé</span>' : '<span class="badge bg-warning">En cours</span>'}</td>
                <td>
                    ${!prescription.is_done ? '<button class="btn btn-sm btn-success" onclick="markAsAdministered(' + prescription.id + ')"><i class="fas fa-check"></i></button>' : ''}
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
        html += `<option value="${prescription.id}">${prescription.medication_name} - ${prescription.dosage}</option>`;
    });
    
    html += '</select></div><div class="mb-3"><label for="administrationNotes">Notes d\'administration:</label><textarea class="form-control" id="administrationNotes" rows="3" placeholder="Ajouter des notes..."></textarea></div>';
    
    return html;
}

// Fonctions des boutons des modals
function addNewPrescription() {
    alert('Fonctionnalité d\'ajout de prescription à implémenter');
}

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
            window.location.reload();
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
