@extends('layouts.nurse')

@section('title', 'Vital Signs - CareWell')
@section('page-title', 'Vital Signs Monitoring')
@section('page-subtitle', 'Monitor and Record Patient Vital Signs')
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
                            <i class="fas fa-heartbeat text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayReadings }}</h4>
                            <p class="text-muted mb-0">Today's Readings</p>
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
                            <h4 class="mb-1">{{ $normalReadings }}</h4>
                            <p class="text-muted mb-0">Normal Readings</p>
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
                            <h4 class="mb-1">{{ $abnormalReadings }}</h4>
                            <p class="text-muted mb-0">Abnormal Readings</p>
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
                            <h4 class="mb-1">{{ $pendingReadings }}</h4>
                            <p class="text-muted mb-0">Pending Readings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Vital Signs Entry -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Quick Vital Signs Entry
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="patientSelect">Select Patient</label>
                                    <select class="form-control" id="patientSelect">
                                        <option value="">Choose Patient...</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="bloodPressure">Blood Pressure</label>
                                    <input type="text" class="form-control" id="bloodPressure" placeholder="120/80">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="heartRate">Heart Rate (BPM)</label>
                                    <input type="number" class="form-control" id="heartRate" placeholder="72">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="temperature">Temperature (°C)</label>
                                    <input type="number" step="0.1" class="form-control" id="temperature" placeholder="36.5">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="oxygenSaturation">O2 Saturation (%)</label>
                                    <input type="number" class="form-control" id="oxygenSaturation" placeholder="98">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-1"></i>Record
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Vital Signs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Vital Signs Readings
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentReadings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Blood Pressure</th>
                                        <th>Heart Rate</th>
                                        <th>Temperature</th>
                                        <th>O2 Saturation</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReadings as $reading)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar me-3">
                                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $reading->patient_name }}</div>
                                                        <small class="text-muted">{{ $reading->patient_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-heartbeat text-danger me-2"></i>
                                                    {{ $reading->blood_pressure ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-heart text-primary me-2"></i>
                                                    {{ $reading->heart_rate ?? 'N/A' }} BPM
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-thermometer-half text-warning me-2"></i>
                                                    {{ $reading->temperature ?? 'N/A' }}°C
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-lungs text-info me-2"></i>
                                                    {{ $reading->oxygen_saturation ?? 'N/A' }}%
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-secondary me-2"></i>
                                                    {{ $reading->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($reading->status == 'normal')
                                                    <span class="badge bg-success">Normal</span>
                                                @elseif($reading->status == 'abnormal')
                                                    <span class="badge bg-danger">Abnormal</span>
                                                @else
                                                    <span class="badge bg-warning">Pending Review</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Chart">
                                                        <i class="fas fa-chart-line"></i>
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
                            {{ $recentReadings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-heartbeat fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Vital Signs Recorded</h5>
                            <p class="text-muted">No vital signs readings have been recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs Charts -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Blood Pressure Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="bloodPressureChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area me-2"></i>Temperature Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="temperatureChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Alerts -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Critical Vital Signs Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-heartbeat me-2"></i>High Blood Pressure</h6>
                                <p class="mb-0">Patient: John Doe - BP: 180/110</p>
                                <small>Time: 14:30</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-thermometer-half me-2"></i>High Temperature</h6>
                                <p class="mb-0">Patient: Jane Smith - Temp: 39.2°C</p>
                                <small>Time: 14:25</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-lungs me-2"></i>Low Oxygen Saturation</h6>
                                <p class="mb-0">Patient: Bob Johnson - O2: 85%</p>
                                <small>Time: 14:20</small>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Blood Pressure Chart
const bloodPressureCtx = document.getElementById('bloodPressureChart').getContext('2d');
const bloodPressureChart = new Chart(bloodPressureCtx, {
    type: 'line',
    data: {
        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
        datasets: [{
            label: 'Systolic',
            data: [120, 125, 130, 135, 128, 122],
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.4
        }, {
            label: 'Diastolic',
            data: [80, 82, 85, 88, 84, 81],
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.4
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

// Temperature Chart
const temperatureCtx = document.getElementById('temperatureChart').getContext('2d');
const temperatureChart = new Chart(temperatureCtx, {
    type: 'line',
    data: {
        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
        datasets: [{
            label: 'Temperature (°C)',
            data: [36.5, 36.7, 36.8, 37.0, 36.9, 36.6],
            borderColor: 'rgba(255, 193, 7, 1)',
            backgroundColor: 'rgba(255, 193, 7, 0.2)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                min: 35,
                max: 40
            }
        }
    }
});
</script>
@endpush
