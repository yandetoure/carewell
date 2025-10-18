@extends('layouts.nurse')

@section('title', 'Prescriptions - CareWell')
@section('page-title', 'Prescriptions Management')
@section('page-subtitle', 'Manage Patient Prescriptions and Medications')
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
                            <p class="text-muted mb-0">Total Prescriptions</p>
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
                            <p class="text-muted mb-0">Completed</p>
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
                            <p class="text-muted mb-0">Pending</p>
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
                            <p class="text-muted mb-0">Today's Prescriptions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statusFilter">Status</label>
                                <select class="form-control" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="patientFilter">Patient</label>
                                <select class="form-control" id="patientFilter">
                                    <option value="">All Patients</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateFilter">Date</label>
                                <input type="date" class="form-control" id="dateFilter" value="{{ today()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>Filter
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
                        <i class="fas fa-exclamation-triangle me-2"></i>Pending Prescriptions
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingPrescriptionsList->count() > 0)
                        <div class="row">
                            @foreach($pendingPrescriptionsList as $prescription)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-warning">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $prescription->prescription->name ?? 'Prescription' }}</h6>
                                                <span class="badge bg-warning">Pending</span>
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
                                                <button type="button" class="btn btn-outline-primary btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" title="Start Medication">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" title="Mark Complete">
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
                            <h5 class="text-muted">No Pending Prescriptions</h5>
                            <p class="text-muted">All prescriptions are up to date.</p>
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
                        <i class="fas fa-clipboard-list me-2"></i>All Prescriptions
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
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-pills text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $prescription->prescription->name ?? 'Prescription' }}</div>
                                                        <small class="text-muted">{{ $prescription->prescription->description ?? 'No description' }}</small>
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
                                                @if($prescription->is_done)
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if(!$prescription->is_done)
                                                        <button type="button" class="btn btn-outline-success" title="Mark Complete">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-info" title="Edit">
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
                            <h5 class="text-muted">No Prescriptions Found</h5>
                            <p class="text-muted">No prescriptions have been created yet.</p>
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
                        <i class="fas fa-clock me-2"></i>Today's Medication Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Morning</h6>
                                    <h4 class="text-primary">{{ $morningMedications }}</h4>
                                    <p class="text-muted mb-0">Medications</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Afternoon</h6>
                                    <h4 class="text-success">{{ $afternoonMedications }}</h4>
                                    <p class="text-muted mb-0">Medications</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Evening</h6>
                                    <h4 class="text-warning">{{ $eveningMedications }}</h4>
                                    <p class="text-muted mb-0">Medications</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Night</h6>
                                    <h4 class="text-info">{{ $nightMedications }}</h4>
                                    <p class="text-muted mb-0">Medications</p>
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
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>New Prescription
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar me-2"></i>Medication Schedule
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-info w-100">
                                <i class="fas fa-download me-2"></i>Export Report
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100">
                                <i class="fas fa-sync me-2"></i>Refresh
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
</style>
@endpush
