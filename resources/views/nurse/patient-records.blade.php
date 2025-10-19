@extends('layouts.nurse')

@section('title', 'Dossiers des Patients - CareWell')
@section('page-title', 'Dossiers des Patients')
@section('page-subtitle', 'Gérer les Dossiers Médicaux des Patients')
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
                            <i class="fas fa-file-medical text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalRecords }}</h4>
                            <p class="text-muted mb-0">Dossiers Total</p>
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
                            <h4 class="mb-1">{{ $todayRecords }}</h4>
                            <p class="text-muted mb-0">Mis à Jour Aujourd'hui</p>
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
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingUpdates }}</h4>
                            <p class="text-muted mb-0">Mises à Jour en Attente</p>
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
                            <h4 class="mb-1">{{ $recentRecords }}</h4>
                            <p class="text-muted mb-0">Dossiers Récents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="searchInput">Recherche Rapide</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Nom patient, ID, numéro dossier..." value="{{ $search ?? '' }}">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statusFilter">Statut</label>
                                <select class="form-control" id="statusFilter">
                                    <option value="">Tous</option>
                                    <option value="hospitalized" {{ ($status ?? '') === 'hospitalized' ? 'selected' : '' }}>Hospitalisé</option>
                                    <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Actif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateRangeFilter">Période</label>
                                <select class="form-control" id="dateRangeFilter">
                                    <option value="">Toutes</option>
                                    <option value="today" {{ ($dateRange ?? '') === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                    <option value="week" {{ ($dateRange ?? '') === 'week' ? 'selected' : '' }}>Cette Semaine</option>
                                    <option value="month" {{ ($dateRange ?? '') === 'month' ? 'selected' : '' }}>Ce Mois</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Effacer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Records List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-medical me-2"></i>Dossiers Médicaux des Patients
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>ID Dossier Médical</th>
                                        <th>Dernière Mise à Jour</th>
                                        <th>Statut</th>
                                        <th>Nombre d'Enregistrements</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicalRecords as $record)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $record->user->first_name }} {{ $record->user->last_name }}</div>
                                                        <small class="text-muted">{{ $record->user->identification_number ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-alt text-info me-2"></i>
                                                    {{ $record->id }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-success me-2"></i>
                                                    {{ $record->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($record->user->medicalFile && $record->user->medicalFile->beds->where('status', 'occupe')->count() > 0)
                                                    <span class="badge bg-warning">Hospitalized</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-list text-primary me-2"></i>
                                                    {{ $record->prescriptions->count() + $record->exams->count() }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir Détails du Dossier" onclick="viewPatientRecord({{ $record->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Ajouter Signes Vitaux" onclick="addVitalSigns({{ $record->id }})">
                                                        <i class="fas fa-heartbeat"></i>
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
                            {{ $medicalRecords->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun Dossier Médical Trouvé</h5>
                            <p class="text-muted">Aucun dossier médical n'a encore été créé.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>Create New Record
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-export me-2"></i>Export Records
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-info w-100">
                                <i class="fas fa-search me-2"></i>Advanced Search
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100">
                                <i class="fas fa-sync me-2"></i>Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
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

.loading {
    opacity: 0.6;
    pointer-events: none;
}

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Styles pour les onglets du dossier médical */
    .nav-tabs .nav-link {
        color: #6c757d !important;
        background-color: transparent !important;
        border-color: transparent !important;
        border-bottom: 1px solid transparent !important;
    }

    .nav-tabs .nav-link:hover {
        color: #495057 !important;
        background-color: #f8f9fa !important;
        border-color: #dee2e6 #dee2e6 #dee2e6 !important;
    }

    .nav-tabs .nav-link.active {
        color: #495057 !important;
        background-color: #fff !important;
        border-color: #dee2e6 #dee2e6 #fff !important;
        border-bottom: 1px solid #fff !important;
    }

    .nav-tabs .nav-link.active:hover {
        color: #495057 !important;
        background-color: #fff !important;
        border-color: #dee2e6 #dee2e6 #fff !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Global variables
let searchTimeout;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    setupAutoSearch();
});

// Setup auto-search functionality
function setupAutoSearch() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateRangeFilter = document.getElementById('dateRangeFilter');

    // Auto-search on input change (with debounce)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 300);
    });

    // Auto-search on filter change
    statusFilter.addEventListener('change', performSearch);
    dateRangeFilter.addEventListener('change', performSearch);
}

// Perform search
function performSearch() {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const dateRange = document.getElementById('dateRangeFilter').value;

    // Update URL with search parameters
    const url = new URL(window.location);
    url.searchParams.set('search', search);
    url.searchParams.set('status', status);
    url.searchParams.set('dateRange', dateRange);

    // Reload page with new parameters
    window.location.href = url.toString();
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateRangeFilter').value = '';
    
    // Reload page without parameters
    window.location.href = window.location.pathname;
}

// View patient record details
function viewPatientRecord(recordId) {
    fetch(`/nurse/patient-records/${recordId}/view`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPatientRecordModal(data.record);
            } else {
                showAlert('danger', 'Erreur lors du chargement du dossier');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Erreur lors du chargement du dossier');
        });
}

// Add vital signs
function addVitalSigns(recordId) {
    showVitalSignsModal(recordId);
}

// Show patient record modal
function showPatientRecordModal(record) {
    const modalHtml = `
        <div class="modal fade" id="patientRecordModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dossier Médical - ${record.user.first_name} ${record.user.last_name}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Informations Patient -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informations Patient</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Nom:</strong> ${record.user.first_name} ${record.user.last_name}</p>
                                        <p><strong>ID Patient:</strong> ${record.user.identification_number || 'N/A'}</p>
                                        <p><strong>Email:</strong> ${record.user.email || 'N/A'}</p>
                                        <p><strong>Téléphone:</strong> ${record.user.phone || 'N/A'}</p>
                                        <p><strong>Date de naissance:</strong> ${record.user.date_of_birth ? new Date(record.user.date_of_birth).toLocaleDateString() : 'N/A'}</p>
                                        <p><strong>Dossier créé:</strong> ${new Date(record.created_at).toLocaleDateString()}</p>
                                        ${record.stats.is_hospitalized ? `
                                            <p><strong>Statut:</strong> <span class="badge bg-danger">Hospitalisé</span></p>
                                            ${record.stats.current_bed ? `<p><strong>Lit actuel:</strong> ${record.stats.current_bed.bed_number}</p>` : ''}
                                        ` : '<p><strong>Statut:</strong> <span class="badge bg-success">Actif</span></p>'}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Prescriptions:</strong> ${record.stats.total_prescriptions}</p>
                                                <p><strong>En attente:</strong> ${record.stats.pending_prescriptions}</p>
                                                <p><strong>Terminées:</strong> ${record.stats.completed_prescriptions}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>Examens:</strong> ${record.stats.total_exams}</p>
                                                <p><strong>Notes:</strong> ${record.stats.total_notes}</p>
                                                <p><strong>Signes vitaux:</strong> ${record.stats.total_vital_signs}</p>
                                            </div>
                                        </div>
                                        <p><strong>Maladies:</strong> ${record.stats.total_diseases}</p>
                                        <p><strong>Antécédents:</strong> ${record.stats.total_histories}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Onglets pour les différentes sections -->
                        <ul class="nav nav-tabs" id="recordTabs" role="tablist" style="border-bottom: 1px solid #dee2e6;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab" style="color: #495057; background-color: #fff; border-color: #dee2e6 #dee2e6 #fff;">
                                    <i class="fas fa-pills me-1"></i>Prescriptions (${record.stats.total_prescriptions})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="diseases-tab" data-bs-toggle="tab" data-bs-target="#diseases" type="button" role="tab" style="color: #6c757d; background-color: transparent; border-color: transparent;">
                                    <i class="fas fa-disease me-1"></i>Maladies (${record.stats.total_diseases})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="histories-tab" data-bs-toggle="tab" data-bs-target="#histories" type="button" role="tab" style="color: #6c757d; background-color: transparent; border-color: transparent;">
                                    <i class="fas fa-history me-1"></i>Antécédents (${record.stats.total_histories})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="exams-tab" data-bs-toggle="tab" data-bs-target="#exams" type="button" role="tab" style="color: #6c757d; background-color: transparent; border-color: transparent;">
                                    <i class="fas fa-stethoscope me-1"></i>Examens (${record.stats.total_exams})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals" type="button" role="tab" style="color: #6c757d; background-color: transparent; border-color: transparent;">
                                    <i class="fas fa-heartbeat me-1"></i>Signes Vitaux (${record.stats.total_vital_signs})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab" style="color: #6c757d; background-color: transparent; border-color: transparent;">
                                    <i class="fas fa-sticky-note me-1"></i>Notes (${record.stats.total_notes})
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="recordTabContent">
                            <!-- Prescriptions -->
                            <div class="tab-pane fade show active" id="prescriptions" role="tabpanel">
                                <div class="mt-3">
                                    ${record.prescriptions && record.prescriptions.length > 0 ? `
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Médicament</th>
                                                        <th>Dosage</th>
                                                        <th>Fréquence</th>
                                                        <th>Durée</th>
                                                        <th>Statut</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${record.prescriptions.map(prescription => `
                                                        <tr>
                                                            <td>${prescription.prescription ? prescription.prescription.name : 'N/A'}</td>
                                                            <td>${prescription.dosage || 'N/A'}</td>
                                                            <td>${prescription.frequency || 'N/A'}</td>
                                                            <td>${prescription.duration || 'N/A'}</td>
                                                            <td>
                                                                ${prescription.is_done ? 
                                                                    '<span class="badge bg-success">Terminé</span>' : 
                                                                    '<span class="badge bg-warning">En Attente</span>'
                                                                }
                                                            </td>
                                                            <td>${new Date(prescription.created_at).toLocaleDateString()}</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    ` : '<p class="text-muted">Aucune prescription trouvée.</p>'}
                                </div>
                            </div>

                            <!-- Maladies -->
                            <div class="tab-pane fade" id="diseases" role="tabpanel">
                                <div class="mt-3">
                                    ${record.medicaldisease && record.medicaldisease.length > 0 ? `
                                        <div class="list-group">
                                            ${record.medicaldisease.map(disease => `
                                                <div class="list-group-item">
                                                    <h6 class="mb-1">${disease.disease ? disease.disease.name : 'Maladie'}</h6>
                                                    <p class="mb-1">${disease.disease ? disease.disease.description : 'Description non disponible'}</p>
                                                    <small class="text-muted">Diagnostiqué le: ${new Date(disease.created_at).toLocaleDateString()}</small>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : '<p class="text-muted">Aucune maladie diagnostiquée.</p>'}
                                </div>
                            </div>

                            <!-- Antécédents -->
                            <div class="tab-pane fade" id="histories" role="tabpanel">
                                <div class="mt-3">
                                    ${record.medicalHistories && record.medicalHistories.length > 0 ? `
                                        <div class="list-group">
                                            ${record.medicalHistories.map(history => `
                                                <div class="list-group-item">
                                                    <h6 class="mb-1">${history.title || 'Antécédent'}</h6>
                                                    <p class="mb-1">${history.description || 'Description non disponible'}</p>
                                                    <small class="text-muted">Date: ${new Date(history.created_at).toLocaleDateString()}</small>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : '<p class="text-muted">Aucun antécédent médical.</p>'}
                                </div>
                            </div>

                            <!-- Examens -->
                            <div class="tab-pane fade" id="exams" role="tabpanel">
                                <div class="mt-3">
                                    ${record.exams && record.exams.length > 0 ? `
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Examen</th>
                                                        <th>Résultat</th>
                                                        <th>Date</th>
                                                        <th>Médecin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${record.exams.map(exam => `
                                                        <tr>
                                                            <td>${exam.exam ? exam.exam.name : 'Examen'}</td>
                                                            <td>${exam.result || 'N/A'}</td>
                                                            <td>${new Date(exam.created_at).toLocaleDateString()}</td>
                                                            <td>${exam.doctor ? exam.doctor.first_name + ' ' + exam.doctor.last_name : 'N/A'}</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    ` : '<p class="text-muted">Aucun examen trouvé.</p>'}
                                </div>
                            </div>

                            <!-- Signes Vitaux -->
                            <div class="tab-pane fade" id="vitals" role="tabpanel">
                                <div class="mt-3">
                                    ${record.vitalSigns && record.vitalSigns.length > 0 ? `
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Tension</th>
                                                        <th>FC</th>
                                                        <th>Temp</th>
                                                        <th>O2</th>
                                                        <th>FR</th>
                                                        <th>Poids</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${record.vitalSigns.slice(0, 10).map(vital => `
                                                        <tr>
                                                            <td>${new Date(vital.recorded_at).toLocaleDateString()}</td>
                                                            <td>${vital.blood_pressure_systolic}/${vital.blood_pressure_diastolic}</td>
                                                            <td>${vital.heart_rate}</td>
                                                            <td>${vital.temperature}°C</td>
                                                            <td>${vital.oxygen_saturation}%</td>
                                                            <td>${vital.respiratory_rate}</td>
                                                            <td>${vital.weight || 'N/A'} kg</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    ` : '<p class="text-muted">Aucun signe vital enregistré.</p>'}
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="tab-pane fade" id="notes" role="tabpanel">
                                <div class="mt-3">
                                    ${record.note && record.note.length > 0 ? `
                                        <div class="list-group">
                                            ${record.note.slice(0, 10).map(note => `
                                                <div class="list-group-item">
                                                    <p class="mb-1">${note.note}</p>
                                                    <small class="text-muted">${new Date(note.created_at).toLocaleString()}</small>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : '<p class="text-muted">Aucune note trouvée.</p>'}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('patientRecordModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('patientRecordModal'));
    modal.show();

    // Remove modal from DOM when hidden
    document.getElementById('patientRecordModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Show vital signs modal
function showVitalSignsModal(recordId) {
    const modalHtml = `
        <div class="modal fade" id="vitalSignsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter Signes Vitaux</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="vitalSignsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="blood_pressure_systolic" class="form-label">Tension Artérielle Systolique (mmHg)</label>
                                        <input type="number" class="form-control" id="blood_pressure_systolic" name="blood_pressure_systolic" min="50" max="300" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="blood_pressure_diastolic" class="form-label">Tension Artérielle Diastolique (mmHg)</label>
                                        <input type="number" class="form-control" id="blood_pressure_diastolic" name="blood_pressure_diastolic" min="30" max="200" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="heart_rate" class="form-label">Fréquence Cardiaque (bpm)</label>
                                        <input type="number" class="form-control" id="heart_rate" name="heart_rate" min="30" max="200" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="temperature" class="form-label">Température (°C)</label>
                                        <input type="number" class="form-control" id="temperature" name="temperature" min="30" max="45" step="0.1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="oxygen_saturation" class="form-label">Saturation en Oxygène (%)</label>
                                        <input type="number" class="form-control" id="oxygen_saturation" name="oxygen_saturation" min="50" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="respiratory_rate" class="form-label">Fréquence Respiratoire (resp/min)</label>
                                        <input type="number" class="form-control" id="respiratory_rate" name="respiratory_rate" min="5" max="60" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Poids (kg)</label>
                                        <input type="number" class="form-control" id="weight" name="weight" min="10" max="500" step="0.1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="height" class="form-label">Taille (cm)</label>
                                        <input type="number" class="form-control" id="height" name="height" min="50" max="250" step="0.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="500"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-success" onclick="submitVitalSigns(${recordId})">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('vitalSignsModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('vitalSignsModal'));
    modal.show();

    // Remove modal from DOM when hidden
    document.getElementById('vitalSignsModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Submit vital signs
function submitVitalSigns(recordId) {
    const form = document.getElementById('vitalSignsForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    const button = document.querySelector('#vitalSignsModal .btn-success');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

    fetch(`/nurse/patient-records/${recordId}/add-vital-signs`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Signes vitaux enregistrés avec succès !');
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('vitalSignsModal'));
            modal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message || 'Erreur lors de l\'enregistrement des signes vitaux');
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Erreur lors de l\'enregistrement des signes vitaux');
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

// Show alert
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
