@extends('layouts.nurse')

@section('title', 'Nurse Dashboard - CareWell')
@section('page-title', 'Nursing Dashboard')
@section('page-subtitle', 'Patient Care and Medical Management')
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

    <!-- Patient Statistics -->
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
                            <h4 class="mb-1">{{ $hospitalizedPatients }}</h4>
                            <p class="text-muted mb-0">Hospitalized Patients</p>
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
                            <h4 class="mb-1">{{ $todayAppointments }}</h4>
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

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Service Activity Today
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceActivityChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bed me-2"></i>Bed Occupancy
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="text-primary">{{ $bedOccupancy['total'] }}</h3>
                            <p class="text-muted mb-0">Total Beds</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success">{{ $bedOccupancy['available'] }}</h3>
                            <p class="text-muted mb-0">Available</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning">{{ $bedOccupancy['occupied'] }}</h3>
                            <p class="text-muted mb-0">Occupied</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $bedOccupancy['occupancy_rate'] }}%"
                                 aria-valuenow="{{ $bedOccupancy['occupancy_rate'] }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $bedOccupancy['occupancy_rate'] }}%
                            </div>
                        </div>
                        <small class="text-muted">Occupancy Rate</small>
                    </div>
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
                        <i class="fas fa-clock me-2"></i>Today's Schedule
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($activity->appointment_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    {{ $activity->user->first_name }} {{ $activity->user->last_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $activity->service->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($activity->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($activity->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($activity->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">Normal</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Start Care">
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
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-day fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Appointments Today</h5>
                            <p class="text-muted">No appointments scheduled for today.</p>
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
                            <a href="{{ route('nurse.patients') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users me-2"></i>Manage Patients
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('nurse.medications') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-pills me-2"></i>Medication Management
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('nurse.beds') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-bed me-2"></i>Bed Management
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100">
                                <i class="fas fa-heartbeat me-2"></i>Vital Signs
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-phone me-2"></i>Emergency Contacts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ambulance text-danger me-3"></i>
                                <div>
                                    <h6 class="mb-1">Emergency Services</h6>
                                    <p class="mb-0">+221 33 821 21 21</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-md text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">On-Call Doctor</h6>
                                    <p class="mb-0">+221 77 123 45 67</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hospital text-success me-3"></i>
                                <div>
                                    <h6 class="mb-1">Hospital Admin</h6>
                                    <p class="mb-0">+221 33 987 65 43</p>
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Service Activity Chart
const serviceActivityCtx = document.getElementById('serviceActivityChart').getContext('2d');
const serviceActivityChart = new Chart(serviceActivityCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($serviceStats as $service)
                '{{ $service->name }}',
            @endforeach
        ],
        datasets: [{
            label: 'Appointments Today',
            data: [
                @foreach($serviceStats as $service)
                    {{ $service->appointments_count }},
                @endforeach
            ],
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
