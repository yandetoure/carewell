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
                                                    <button type="button" class="btn btn-outline-primary" title="Voir Dossier" onclick="viewPatientRecord({{ $record->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" title="Modifier Dossier" onclick="editPatientRecord({{ $record->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Ajouter Note" onclick="addPatientNote({{ $record->id }})">
                                                        <i class="fas fa-plus"></i>
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

// Edit patient record
function editPatientRecord(recordId) {
    fetch(`/nurse/patient-records/${recordId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEditRecordModal(data.record);
            } else {
                showAlert('danger', 'Erreur lors du chargement du dossier');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Erreur lors du chargement du dossier');
        });
}

// Add patient note
function addPatientNote(recordId) {
    const note = prompt('Entrez votre note:');
    if (!note || note.trim() === '') {
        return;
    }

    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/nurse/patient-records/${recordId}/add-note`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ note: note.trim() })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Note ajoutée avec succès !');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message || 'Erreur lors de l\'ajout de la note');
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Erreur lors de l\'ajout de la note');
        button.disabled = false;
        button.innerHTML = originalContent;
    });
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
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informations Patient</h6>
                                <p><strong>Nom:</strong> ${record.user.first_name} ${record.user.last_name}</p>
                                <p><strong>ID Patient:</strong> ${record.user.identification_number || 'N/A'}</p>
                                <p><strong>Email:</strong> ${record.user.email || 'N/A'}</p>
                                <p><strong>Dossier créé:</strong> ${new Date(record.created_at).toLocaleDateString()}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Statistiques</h6>
                                <p><strong>Prescriptions:</strong> ${record.prescriptions ? record.prescriptions.length : 0}</p>
                                <p><strong>Examens:</strong> ${record.exams ? record.exams.length : 0}</p>
                                <p><strong>Notes:</strong> ${record.notes ? record.notes.length : 0}</p>
                                <p><strong>Signes vitaux:</strong> ${record.vital_signs ? record.vital_signs.length : 0}</p>
                            </div>
                        </div>
                        ${record.notes && record.notes.length > 0 ? `
                            <div class="mt-3">
                                <h6>Notes Récentes</h6>
                                <div class="list-group">
                                    ${record.notes.slice(0, 5).map(note => `
                                        <div class="list-group-item">
                                            <small class="text-muted">${new Date(note.created_at).toLocaleString()}</small>
                                            <p class="mb-0">${note.note}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" onclick="editPatientRecord(${record.id})">Modifier</button>
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

// Show edit record modal
function showEditRecordModal(record) {
    showAlert('info', 'Fonctionnalité de modification sera implémentée bientôt');
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
