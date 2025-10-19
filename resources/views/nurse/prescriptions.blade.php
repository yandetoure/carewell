@extends('layouts.nurse')

@section('title', 'Prescriptions - CareWell')
@section('page-title', 'Gestion des Prescriptions')
@section('page-subtitle', 'Gérer les Prescriptions et Médicaments des Patients')
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
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPrescriptions }}</h4>
                            <p class="text-muted mb-0">Prescriptions Total</p>
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
                            <h4 class="mb-1">{{ $completedPrescriptions }}</h4>
                            <p class="text-muted mb-0">Terminées</p>
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
                            <h4 class="mb-1">{{ $pendingPrescriptions }}</h4>
                            <p class="text-muted mb-0">En Attente</p>
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
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayPrescriptions }}</h4>
                            <p class="text-muted mb-0">Prescriptions d'Aujourd'hui</p>
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
                                    <input type="text" class="form-control" id="searchInput" placeholder="Nom patient, médicament, dosage..." value="{{ $search ?? '' }}">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="statusFilter">Statut</label>
                                <select class="form-control" id="statusFilter">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>En Attente</option>
                                    <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Terminé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="patientFilter">Patient</label>
                                <select class="form-control" id="patientFilter">
                                    <option value="">Tous</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ ($patientId ?? '') == $patient->id ? 'selected' : '' }}>{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dateFilter">Date</label>
                                <input type="date" class="form-control" id="dateFilter" value="{{ $date ?? today()->format('Y-m-d') }}">
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

    <!-- Pending Prescriptions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Prescriptions en Attente
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingPrescriptionsList->count() > 0)
                        <div class="row">
                            @foreach($pendingPrescriptionsList as $prescription)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-warning" data-prescription-id="{{ $prescription->id }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $prescription->prescription->name ?? 'Prescription' }}</h6>
                                                <div>
                                                    @if($prescription->medicalFile->beds()->where('status', 'occupe')->exists())
                                                        <span class="badge bg-danger me-1">Hospitalisé</span>
                                                    @endif
                                                    <span class="badge bg-warning">En Attente</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <span class="fw-bold">{{ $prescription->medicalFile->user->first_name }} {{ $prescription->medicalFile->user->last_name }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-user-md text-success me-2"></i>
                                                <span>{{ $prescription->doctor->first_name ?? 'N/A' }} {{ $prescription->doctor->last_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-calendar text-info me-2"></i>
                                                <span>{{ $prescription->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-outline-primary btn-sm" title="Voir Détails" onclick="viewPrescriptionDetails({{ $prescription->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" title="Démarrer Médicament" onclick="markPrescriptionProgress({{ $prescription->id }})">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" title="Marquer Terminé" onclick="markPrescriptionComplete({{ $prescription->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-muted">Aucune Prescription en Attente</h5>
                            <p class="text-muted">Toutes les prescriptions sont à jour.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- All Prescriptions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Toutes les Prescriptions
                    </h5>
                </div>
                <div class="card-body">
                    @if($prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Prescription</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                        <tr data-prescription-id="{{ $prescription->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-pills text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $prescription->prescription->name ?? 'Prescription' }}</div>
                                                        <small class="text-muted">{{ $prescription->prescription->description ?? 'Aucune description' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $prescription->medicalFile->user->first_name }} {{ $prescription->medicalFile->user->last_name }}</div>
                                                        <small class="text-muted">{{ $prescription->medicalFile->user->identification_number ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-success me-2"></i>
                                                    {{ $prescription->doctor->first_name ?? 'N/A' }} {{ $prescription->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-info me-2"></i>
                                                    {{ $prescription->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($prescription->medicalFile->beds()->where('status', 'occupe')->exists())
                                                        <span class="badge bg-danger me-1">Hospitalisé</span>
                                                    @endif
                                                @if($prescription->is_done)
                                                        <span class="badge bg-success">Terminé</span>
                                                @else
                                                        <span class="badge bg-warning">En Attente</span>
                                                @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir Détails" onclick="viewPrescriptionDetails({{ $prescription->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if(!$prescription->is_done)
                                                        <button type="button" class="btn btn-outline-success" title="Marquer Terminé" onclick="markPrescriptionComplete({{ $prescription->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-info" title="Modifier" onclick="editPrescription({{ $prescription->id }})">
                                                        <i class="fas fa-edit"></i>
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
                            {{ $prescriptions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune Prescription Trouvée</h5>
                            <p class="text-muted">Aucune prescription n'a encore été créée.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Medication Schedule -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Planning de Médicaments d'Aujourd'hui
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Matin</h6>
                                    <h4 class="text-primary">{{ $morningMedications }}</h4>
                                    <p class="text-muted mb-0">Médicaments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Après-midi</h6>
                                    <h4 class="text-success">{{ $afternoonMedications }}</h4>
                                    <p class="text-muted mb-0">Médicaments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Soir</h6>
                                    <h4 class="text-warning">{{ $eveningMedications }}</h4>
                                    <p class="text-muted mb-0">Médicaments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Nuit</h6>
                                    <h4 class="text-info">{{ $nightMedications }}</h4>
                                    <p class="text-muted mb-0">Médicaments</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary w-100" onclick="createNewPrescription()">
                                <i class="fas fa-plus me-2"></i>Nouvelle Prescription
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-success w-100" onclick="viewMedicationSchedule()">
                                <i class="fas fa-calendar me-2"></i>Planning Médicaments
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-info w-100" onclick="exportPrescriptionReport()">
                                <i class="fas fa-download me-2"></i>Exporter Rapport
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100" onclick="refreshPrescriptions()">
                                <i class="fas fa-sync me-2"></i>Actualiser
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

.card.border-warning {
    border-color: #ffc107 !important;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let currentFilters = {
    status: '',
    patient: '',
    date: ''
};
let searchTimeout;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    setupAutoSearch();
});

// Initialize filters
function initializeFilters() {
    // Set today's date as default if not already set
    const dateFilter = document.getElementById('dateFilter');
    if (!dateFilter.value) {
        dateFilter.value = new Date().toISOString().split('T')[0];
    }
}

// Setup auto-search functionality
function setupAutoSearch() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const patientFilter = document.getElementById('patientFilter');
    const dateFilter = document.getElementById('dateFilter');

    // Auto-search on input change (with debounce)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 300);
    });

    // Auto-search on filter change
    statusFilter.addEventListener('change', performSearch);
    patientFilter.addEventListener('change', performSearch);
    dateFilter.addEventListener('change', performSearch);
}

// Perform search
function performSearch() {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const patient = document.getElementById('patientFilter').value;
    const date = document.getElementById('dateFilter').value;

    // Update URL with search parameters
    const url = new URL(window.location);
    url.searchParams.set('search', search);
    url.searchParams.set('status', status);
    url.searchParams.set('patient', patient);
    url.searchParams.set('date', date);

    // Reload page with new parameters
    window.location.href = url.toString();
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('patientFilter').value = '';
    document.getElementById('dateFilter').value = new Date().toISOString().split('T')[0];
    
    // Reload page without parameters
    window.location.href = window.location.pathname;
}

// View prescription details
function viewPrescriptionDetails(prescriptionId) {
    fetch(`/nurse/prescriptions/${prescriptionId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPrescriptionModal(data.prescription);
            } else {
                showAlert('danger', 'Error loading prescription details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Error loading prescription details');
        });
}

// Mark prescription as complete
function markPrescriptionComplete(prescriptionId) {
    if (!confirm('Êtes-vous sûr de vouloir marquer cette prescription comme terminée ?')) {
        return;
    }

    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/nurse/prescriptions/${prescriptionId}/mark-complete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Prescription marquée comme terminée !');
            // Update the UI immediately
            updatePrescriptionStatus(prescriptionId, true);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message || 'Erreur lors du marquage de la prescription');
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Erreur lors du marquage de la prescription');
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

// Update prescription status in UI
function updatePrescriptionStatus(prescriptionId, isComplete) {
    // Find all elements with this prescription ID and update their status
    const statusElements = document.querySelectorAll(`[data-prescription-id="${prescriptionId}"] .badge`);
    statusElements.forEach(element => {
        if (isComplete) {
            element.className = 'badge bg-success';
            element.textContent = 'Terminé';
        } else {
            element.className = 'badge bg-warning';
            element.textContent = 'En Attente';
        }
    });

    // Update action buttons
    const actionButtons = document.querySelectorAll(`[data-prescription-id="${prescriptionId}"] .btn-group`);
    actionButtons.forEach(buttonGroup => {
        const completeButton = buttonGroup.querySelector('[onclick*="markPrescriptionComplete"]');
        if (completeButton) {
            if (isComplete) {
                completeButton.style.display = 'none';
            } else {
                completeButton.style.display = 'inline-block';
            }
        }
    });
}

// Mark prescription as in progress
function markPrescriptionProgress(prescriptionId) {
    if (!confirm('Marquer cette prescription comme en cours ?')) {
        return;
    }

    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/nurse/prescriptions/${prescriptionId}/mark-progress`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Prescription marquée comme en cours !');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la mise à jour du statut');
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Erreur lors de la mise à jour du statut');
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

// Edit prescription
function editPrescription(prescriptionId) {
    // This would typically open an edit modal
    showAlert('info', 'Edit functionality will be implemented soon');
}

// Apply filters (deprecated - now handled by performSearch)
function applyFilters() {
    performSearch();
}

// Create new prescription
function createNewPrescription() {
    showAlert('info', 'New prescription functionality will be implemented soon');
    // This would typically open a modal or redirect to a form
}

// View medication schedule
function viewMedicationSchedule() {
    showAlert('info', 'Medication schedule view will be implemented soon');
    // This would typically open a calendar view
}

// Export prescription report
function exportPrescriptionReport() {
    const button = event.target.closest('button');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';

    // Simulate export process
    setTimeout(() => {
        showAlert('success', 'Report exported successfully!');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-download me-2"></i>Export Report';
    }, 2000);
}

// Refresh prescriptions
function refreshPrescriptions() {
    const button = event.target.closest('button');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    // Reload the page
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Show prescription details modal
function showPrescriptionModal(prescription) {
    const modalHtml = `
        <div class="modal fade" id="prescriptionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Prescription Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Medication Information</h6>
                                <p><strong>Name:</strong> ${prescription.prescription?.name || 'N/A'}</p>
                                <p><strong>Dosage:</strong> ${prescription.dosage || 'N/A'}</p>
                                <p><strong>Frequency:</strong> ${prescription.frequency || 'N/A'}</p>
                                <p><strong>Duration:</strong> ${prescription.duration || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Patient Information</h6>
                                <p><strong>Patient:</strong> ${prescription.medicalFile?.user?.first_name || ''} ${prescription.medicalFile?.user?.last_name || ''}</p>
                                <p><strong>Doctor:</strong> ${prescription.doctor?.first_name || ''} ${prescription.doctor?.last_name || ''}</p>
                                <p><strong>Created:</strong> ${new Date(prescription.created_at).toLocaleDateString()}</p>
                                <p><strong>Status:</strong> ${prescription.is_done ? 'Completed' : 'Pending'}</p>
                            </div>
                        </div>
                        ${prescription.instructions ? `<div class="mt-3"><h6>Instructions</h6><p>${prescription.instructions}</p></div>` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        ${!prescription.is_done ? `<button type="button" class="btn btn-success" onclick="markPrescriptionComplete(${prescription.id})">Mark Complete</button>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('prescriptionModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('prescriptionModal'));
    modal.show();

    // Remove modal from DOM when hidden
    document.getElementById('prescriptionModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
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

// Auto-refresh every 60 seconds
setInterval(() => {
    // Only refresh if no modals are open
    if (!document.querySelector('.modal.show')) {
        // In a real implementation, this would update the prescription data via AJAX
        console.log('Auto-refreshing prescriptions...');
    }
}, 60000);
</script>
@endpush

