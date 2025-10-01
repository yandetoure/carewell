@extends('layouts.admin')

@section('title', 'Détails Lit - Admin')
@section('page-title', 'Détails du Lit')
@section('page-subtitle', 'Informations complètes sur le lit d\'hospitalisation')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Informations générales -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bed-icon mb-3">
                        @if($bed['status'] === 'occupied')
                            <i class="fas fa-bed text-danger fa-4x"></i>
                        @else
                            <i class="fas fa-bed text-success fa-4x"></i>
                        @endif
                    </div>
                    
                    <h4 class="mb-1">Lit {{ $bed['number'] }}</h4>
                    <p class="text-muted mb-3">
                        @if($bed['status'] === 'occupied')
                            <span class="badge bg-danger">Occupé</span>
                        @else
                            <span class="badge bg-success">Disponible</span>
                        @endif
                    </p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.beds.edit', $bedModel->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <button class="btn btn-danger" onclick="deleteBed({{ $bedModel->id }})">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations du lit -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du lit
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-primary me-3"></i>
                                <div>
                                    <strong>Numéro du lit</strong><br>
                                    <span class="text-muted">{{ $bed['number'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-door-open text-success me-3"></i>
                                <div>
                                    <strong>Chambre</strong><br>
                                    <span class="text-muted">{{ $bed['room'] ?? 'Chambre ' . ceil($bed['number'] / 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-stethoscope text-info me-3"></i>
                                <div>
                                    <strong>Service</strong><br>
                                    <span class="text-muted">{{ $bed['service'] ?? 'Service général' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-star text-warning me-3"></i>
                                <div>
                                    <strong>Type de lit</strong><br>
                                    <span class="text-muted">{{ $bed['type'] ?? 'Standard' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($bed['status'] === 'occupied')
                            <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#dischargePatientModal">
                                <i class="fas fa-sign-out-alt me-2"></i>Libérer le lit
                            </button>
                            <button class="btn btn-outline-info" onclick="transferPatient({{ $bedModel->id }})">
                                <i class="fas fa-exchange-alt me-2"></i>Transférer patient
                            </button>
                        @else
                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignPatientModal">
                                <i class="fas fa-user-plus me-2"></i>Assigner patient
                            </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="maintenanceBed({{ $bedModel->id }})">
                            <i class="fas fa-wrench me-2"></i>Maintenance
                        </button>
                        <button class="btn btn-outline-secondary" onclick="printBedLabel({{ $bedModel->id }})">
                            <i class="fas fa-print me-2"></i>Imprimer étiquette
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails et statistiques -->
        <div class="col-lg-8 mb-4">
            <!-- Patient actuel (si occupé) -->
            @if($bed['status'] === 'occupied' && $bed['patient'])
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2"></i>
                        Patient actuel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user text-white fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $bed['patient'] }}</h5>
                                    <p class="text-muted mb-1">Patient ID: {{ $bed['number'] }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Admis le {{ now()->subDays(rand(1, 10))->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group-vertical">
                                <button class="btn btn-outline-primary btn-sm" onclick="viewPatient({{ $bed['number'] }})">
                                    <i class="fas fa-eye me-1"></i>Voir patient
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="editPatient({{ $bed['number'] }})">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistiques d'occupation -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ rand(5, 25) }}</h4>
                                    <p class="text-muted mb-0">Jours d'occupation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ rand(10, 50) }}</h4>
                                    <p class="text-muted mb-0">Patients total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ rand(1, 5) }}</h4>
                                    <p class="text-muted mb-0">Maintenances</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-percentage text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ rand(60, 95) }}%</h4>
                                    <p class="text-muted mb-0">Taux occupation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des patients -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique des patients
                    </h5>
                                    <button class="btn btn-outline-primary btn-sm" onclick="exportHistory({{ $bedModel->id }})">
                        <i class="fas fa-download me-1"></i>Exporter
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Action</th>
                                    <th>Durée</th>
                                    <th>Médecin</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 10; $i++)
                                <tr>
                                    <td>
                                        <div>{{ now()->subDays($i)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ now()->subDays($i)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white" style="font-size: 0.8em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">Patient {{ $i }}</div>
                                                <small class="text-muted">ID: {{ rand(1000, 9999) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $actions = ['Admission', 'Sortie', 'Transfert'];
                                            $action = $actions[array_rand($actions)];
                                            $actionColors = ['Admission' => 'success', 'Sortie' => 'warning', 'Transfert' => 'info'];
                                        @endphp
                                        <span class="badge bg-{{ $actionColors[$action] }}">{{ $action }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ rand(1, 15) }} jours</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">Dr. Médecin {{ $i }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewPatientHistory({{ $i }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="viewMedicalRecord({{ $i }})" title="Dossier">
                                                <i class="fas fa-folder-medical"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance et entretien -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-wrench me-2"></i>
                        Historique de maintenance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @for($i = 1; $i <= 6; $i++)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Maintenance {{ $i }}</h6>
                                        <p class="text-muted mb-1">
                                            @php
                                                $maintenanceTypes = ['Nettoyage', 'Réparation', 'Inspection', 'Remplacement'];
                                                $type = $maintenanceTypes[array_rand($maintenanceTypes)];
                                            @endphp
                                            {{ $type }} du lit {{ $bed['number'] }}
                                        </p>
                                        <small class="text-muted">{{ now()->subDays($i * 2)->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-warning">{{ $type }}</span>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'assignation de patient -->
<div class="modal fade" id="assignPatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Assigner un patient au lit {{ $bed['number'] }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.beds.admit', $bedModel->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="medical_file_id" class="form-label">Sélectionner un patient *</label>
                        <input type="text" class="form-control mb-3" id="searchPatient" placeholder="Rechercher un patient..." onkeyup="filterPatients()">
                        
                        <div class="list-group" id="patientList" style="max-height: 400px; overflow-y: auto;">
                            @forelse($availablePatients as $patient)
                                <label class="list-group-item list-group-item-action patient-item" style="cursor: pointer;">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="medical_file_id" 
                                               value="{{ $patient['id'] }}" id="patient_{{ $patient['id'] }}" required>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 patient-name">{{ $patient['name'] }}</h6>
                                                    <small class="text-muted patient-info">
                                                        <i class="fas fa-envelope me-1"></i>{{ $patient['email'] }}<br>
                                                        <i class="fas fa-phone me-1"></i>{{ $patient['phone'] }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-primary">ID: {{ $patient['id'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun patient disponible pour le moment. Tous les patients ont déjà un lit assigné.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="admission_date" class="form-label">Date d'admission</label>
                                <input type="date" class="form-control" id="admission_date" name="admission_date" 
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expected_discharge_date" class="form-label">Date de sortie prévue</label>
                                <input type="date" class="form-control" id="expected_discharge_date" 
                                       name="expected_discharge_date" min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="admission_notes" class="form-label">Notes d'admission</label>
                        <textarea class="form-control" id="admission_notes" name="notes" rows="3" 
                                  placeholder="Raison de l'admission, diagnostic, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Assigner le patient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de sortie de patient -->
<div class="modal fade" id="dischargePatientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sign-out-alt me-2"></i>Sortir le patient
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.beds.discharge', $bedModel->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vous êtes sur le point de libérer le lit <strong>{{ $bed['number'] }}</strong>.
                        @if($bed['patient'])
                            Le patient <strong>{{ $bed['patient'] }}</strong> sera déchargé.
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="discharge_date" class="form-label">Date de sortie *</label>
                        <input type="date" class="form-control" id="discharge_date" name="discharge_date" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="discharge_reason" class="form-label">Raison de sortie *</label>
                        <select class="form-select" id="discharge_reason" name="discharge_reason" required>
                            <option value="">Sélectionner une raison</option>
                            <option value="gueri">Guéri</option>
                            <option value="transfert">Transfert</option>
                            <option value="deces">Décès</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="discharge_notes" class="form-label">Notes de sortie</label>
                        <textarea class="form-control" id="discharge_notes" name="notes" rows="3" 
                                  placeholder="Observations, recommandations, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sign-out-alt me-2"></i>Libérer le lit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Fonction de recherche de patients
function filterPatients() {
    const searchTerm = document.getElementById('searchPatient').value.toLowerCase();
    const patientItems = document.querySelectorAll('.patient-item');
    
    patientItems.forEach(item => {
        const name = item.querySelector('.patient-name').textContent.toLowerCase();
        const info = item.querySelector('.patient-info').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || info.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function deleteBed(bedId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce lit ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/beds/${bedId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Les fonctions assignPatient et dischargePatient sont maintenant gérées par les modals

function transferPatient(bedId) {
    const newBedId = prompt('Transférer le patient vers le lit (ID):');
    if (newBedId) {
        alert('Fonctionnalité de transfert à implémenter');
    }
}

function maintenanceBed(bedId) {
    if (confirm('Mettre le lit en maintenance ?')) {
        const notes = prompt('Notes de maintenance (optionnel):');
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/beds/${bedId}/maintenance`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        if (notes) {
            const notesField = document.createElement('input');
            notesField.type = 'hidden';
            notesField.name = 'notes';
            notesField.value = notes;
            form.appendChild(notesField);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}

function printBedLabel(bedId) {
    window.open(`/admin/beds/${bedId}/print-label`, '_blank');
}

function viewPatient(patientId) {
    window.location.href = `/admin/patients/${patientId}`;
}

function editPatient(patientId) {
    window.location.href = `/admin/patients/${patientId}/edit`;
}

function viewPatientHistory(patientId) {
    window.location.href = `/admin/patients/${patientId}/history`;
}

function viewMedicalRecord(patientId) {
    window.location.href = `/admin/patients/${patientId}/medical-file`;
}

function exportHistory(bedNumber) {
    alert('Export de l\'historique du lit en cours...');
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #ffc107;
}

.bed-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.patient-item {
    transition: background-color 0.2s;
}

.patient-item:hover {
    background-color: #f8f9fa;
}

.patient-item input[type="radio"]:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.patient-item:has(input[type="radio"]:checked) {
    background-color: #e7f1ff;
    border-color: #0d6efd;
}
</style>
@endsection
