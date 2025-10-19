@extends('layouts.nurse')

@section('title', 'Bed Management - CareWell')
@section('page-title', 'Bed Management')
@section('page-subtitle', 'Manage Hospital Bed Allocation')
@section('user-role', 'Nurse')

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

    <!-- Bed Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-bed text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $bedStats['total'] }}</h4>
                            <p class="text-muted mb-0">Total Beds</p>
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
                            <h4 class="mb-1">{{ $bedStats['available'] }}</h4>
                            <p class="text-muted mb-0">Available</p>
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
                            <i class="fas fa-bed text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $bedStats['occupied'] }}</h4>
                            <p class="text-muted mb-0">Occupied</p>
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
                            <i class="fas fa-tools text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $bedStats['maintenance'] + $bedStats['admission_impossible'] }}</h4>
                            <p class="text-muted mb-0">Maintenance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bed Grid -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bed me-2"></i>Bed Status Overview
                        <div class="float-end">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshBedStatus()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="bedGrid">
                        @foreach($beds as $bed)
                            <div class="col-md-3 mb-3">
                                <div class="card bed-card {{ $bed->status === 'occupe' ? 'border-danger' : ($bed->status === 'libre' ? 'border-success' : 'border-warning') }}">
                                    <div class="card-body text-center">
                                        <div class="bed-icon mb-2">
                                            <i class="fas fa-bed fa-2x {{ $bed->status === 'occupe' ? 'text-danger' : ($bed->status === 'libre' ? 'text-success' : 'text-warning') }}"></i>
                                        </div>
                                        <h6 class="card-title">Bed #{{ $bed->bed_number }}</h6>
                                        <p class="card-text small text-muted">
                                            Room: {{ $bed->room_number }}<br>
                                            Service: {{ $bed->service->name ?? 'N/A' }}
                                        </p>
                                        
                                        @if($bed->status === 'occupe' && $bed->medicalFile && $bed->medicalFile->user)
                                            <div class="patient-info">
                                                <strong>{{ $bed->medicalFile->user->first_name }} {{ $bed->medicalFile->user->last_name }}</strong><br>
                                                <small class="text-muted">
                                                    Since: {{ $bed->admission_date ? \Carbon\Carbon::parse($bed->admission_date)->format('d/m/Y') : 'N/A' }}
                                                </small>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-danger" onclick="dischargePatient({{ $bed->id }})">
                                                    <i class="fas fa-sign-out-alt"></i> Discharge
                                                </button>
                                            </div>
                                        @elseif($bed->status === 'libre')
                                            <div class="text-success">
                                                <i class="fas fa-check-circle"></i> Available
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-primary" onclick="assignBed({{ $bed->id }})">
                                                    <i class="fas fa-user-plus"></i> Assign
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-warning">
                                                <i class="fas fa-tools"></i> {{ ucfirst($bed->status) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hospitalized Patients -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Hospitalized Patients
                    </h5>
                </div>
                <div class="card-body">
                    @if($hospitalizedPatients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Bed</th>
                                        <th>Service</th>
                                        <th>Admission Date</th>
                                        <th>Expected Discharge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalizedPatients as $patient)
                                        @foreach($patient->medicalFile->beds as $bed)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user text-primary me-2"></i>
                                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">Bed #{{ $bed->bed_number }}</span>
                                                </td>
                                                <td>{{ $bed->service->name ?? 'N/A' }}</td>
                                                <td>{{ $bed->admission_date ? \Carbon\Carbon::parse($bed->admission_date)->format('d/m/Y') : 'N/A' }}</td>
                                                <td>{{ $bed->expected_discharge_date ? \Carbon\Carbon::parse($bed->expected_discharge_date)->format('d/m/Y') : 'N/A' }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" onclick="viewPatientDetails({{ $patient->id }})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="dischargePatient({{ $bed->id }})">
                                                            <i class="fas fa-sign-out-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Hospitalized Patients</h5>
                            <p class="text-muted">No patients are currently hospitalized.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Bed Activities
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Bed</th>
                                        <th>Patient</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($activity->updated_at)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">Bed #{{ $activity->bed_number }}</span>
                                            </td>
                                            <td>
                                                @if($activity->medicalFile && $activity->medicalFile->user)
                                                    {{ $activity->medicalFile->user->first_name }} {{ $activity->medicalFile->user->last_name }}
                                                @else
                                                    <span class="text-muted">No patient</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($activity->status === 'occupe')
                                                    <span class="badge bg-danger">Occupied</span>
                                                @elseif($activity->status === 'libre')
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-warning">{{ ucfirst($activity->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($activity->status === 'occupe')
                                                    <span class="text-danger">Occupied</span>
                                                @elseif($activity->status === 'libre')
                                                    <span class="text-success">Available</span>
                                                @else
                                                    <span class="text-warning">{{ ucfirst($activity->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Recent Activities</h5>
                            <p class="text-muted">No bed activities in the last 24 hours.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Bed Modal -->
<div class="modal fade" id="assignBedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Bed to Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignBedForm">
                    <input type="hidden" id="bedId" name="bed_id">
                    <div class="mb-3">
                        <label for="patientSelect" class="form-label">Select Patient</label>
                        <select class="form-select" id="patientSelect" name="patient_id" required>
                            <option value="">Choose a patient...</option>
                            <!-- Patients will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="admissionReason" class="form-label">Admission Reason</label>
                        <textarea class="form-control" id="admissionReason" name="admission_reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="expectedDuration" class="form-label">Expected Duration (days)</label>
                        <input type="number" class="form-control" id="expectedDuration" name="expected_duration" min="1" max="365">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAssignBed()">Assign Bed</button>
            </div>
        </div>
    </div>
</div>

<!-- Discharge Patient Modal -->
<div class="modal fade" id="dischargeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Discharge Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="dischargeForm">
                    <input type="hidden" id="dischargeBedId" name="bed_id">
                    <div class="mb-3">
                        <label for="dischargeReason" class="form-label">Discharge Reason</label>
                        <textarea class="form-control" id="dischargeReason" name="discharge_reason" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDischarge()">Discharge Patient</button>
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

.bed-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bed-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bed-icon {
    transition: transform 0.3s ease;
}

.bed-card:hover .bed-icon {
    transform: scale(1.1);
}

.patient-info {
    background-color: rgba(220, 53, 69, 0.1);
    border-radius: 8px;
    padding: 10px;
    margin: 10px 0;
}

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
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
// Global variables
let currentBedId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadPatients();
});

// Load patients for assignment
function loadPatients() {
    // This would typically be an AJAX call to get patients without beds
    fetch('/nurse/patients/without-beds')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('patientSelect');
            select.innerHTML = '<option value="">Choose a patient...</option>';
            
            if (data.patients) {
                data.patients.forEach(patient => {
                    const option = document.createElement('option');
                    option.value = patient.id;
                    option.textContent = patient.first_name + ' ' + patient.last_name;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading patients:', error);
            // Fallback: add some sample patients
            const select = document.getElementById('patientSelect');
            select.innerHTML = `
                <option value="">Choose a patient...</option>
                <option value="1">Sample Patient 1</option>
                <option value="2">Sample Patient 2</option>
            `;
        });
}

// Assign bed to patient
function assignBed(bedId) {
    currentBedId = bedId;
    document.getElementById('bedId').value = bedId;
    const modal = new bootstrap.Modal(document.getElementById('assignBedModal'));
    modal.show();
}

// Confirm bed assignment
function confirmAssignBed() {
    const form = document.getElementById('assignBedForm');
    const formData = new FormData(form);
    
    fetch(`/nurse/beds/${currentBedId}/assign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Bed assigned successfully!');
            location.reload();
        } else {
            showAlert('danger', data.message || 'Error assigning bed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Error assigning bed');
    });
}

// Discharge patient
function dischargePatient(bedId) {
    currentBedId = bedId;
    document.getElementById('dischargeBedId').value = bedId;
    const modal = new bootstrap.Modal(document.getElementById('dischargeModal'));
    modal.show();
}

// Confirm discharge
function confirmDischarge() {
    const form = document.getElementById('dischargeForm');
    const formData = new FormData(form);
    
    fetch(`/nurse/beds/${currentBedId}/discharge`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Patient discharged successfully!');
            location.reload();
        } else {
            showAlert('danger', data.message || 'Error discharging patient');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Error discharging patient');
    });
}

// View patient details
function viewPatientDetails(patientId) {
    // This would typically open a modal with patient details
    window.open(`/nurse/patients/${patientId}`, '_blank');
}

// Refresh bed status
function refreshBedStatus() {
    location.reload();
}

// Show alert
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Auto-refresh every 30 seconds
setInterval(() => {
    refreshBedStatus();
}, 30000);
</script>
@endpush
