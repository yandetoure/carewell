@extends('layouts.nurse')

@section('title', 'Signes Vitaux - CareWell')
@section('page-title', 'Surveillance des Signes Vitaux')
@section('page-subtitle', 'Surveiller et Enregistrer les Signes Vitaux des Patients')
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
                            <i class="fas fa-heartbeat text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayReadings }}</h4>
                            <p class="text-muted mb-0">Lectures d'Aujourd'hui</p>
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
                            <p class="text-muted mb-0">Lectures Normales</p>
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
                            <p class="text-muted mb-0">Lectures Anormales</p>
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
                            <p class="text-muted mb-0">Lectures en Attente</p>
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
                        <i class="fas fa-plus-circle me-2"></i>Saisie Rapide des Signes Vitaux
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="patientSelect">Sélectionner Patient</label>
                                    <select class="form-control" id="patientSelect">
                                        <option value="">Choisir Patient...</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="bloodPressure">Tension Artérielle</label>
                                    <input type="text" class="form-control" id="bloodPressure" placeholder="120/80">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="heartRate">Fréquence Cardiaque (BPM)</label>
                                    <input type="number" class="form-control" id="heartRate" placeholder="72">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="temperature">Température (°C)</label>
                                    <input type="number" step="0.1" class="form-control" id="temperature" placeholder="36.5">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="oxygenSaturation">Saturation O2 (%)</label>
                                    <input type="number" class="form-control" id="oxygenSaturation" placeholder="98">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-1"></i>Enregistrer
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
                        <i class="fas fa-history me-2"></i>Signes Vitaux Récents
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentReadings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Tension Artérielle</th>
                                        <th>Fréquence Cardiaque</th>
                                        <th>Température</th>
                                        <th>Saturation O2</th>
                                        <th>Heure</th>
                                        <th>Statut</th>
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
                                                        <div class="fw-bold">{{ $reading->medicalFile->user->first_name }} {{ $reading->medicalFile->user->last_name }}</div>
                                                        <small class="text-muted">{{ $reading->medicalFile->user->identification_number ?? 'Non disponible' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-heartbeat text-danger me-2"></i>
                                                    {{ $reading->blood_pressure_systolic }}/{{ $reading->blood_pressure_diastolic }} mmHg
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-heart text-primary me-2"></i>
                                                    {{ $reading->heart_rate ?? 'Non disponible' }} BPM
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-thermometer-half text-warning me-2"></i>
                                                    {{ $reading->temperature ?? 'Non disponible' }}°C
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-lungs text-info me-2"></i>
                                                    {{ $reading->oxygen_saturation ?? 'Non disponible' }}%
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-secondary me-2"></i>
                                                    {{ $reading->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $isAbnormal = ($reading->blood_pressure_systolic > 140 || $reading->blood_pressure_diastolic > 90) ||
                                                                 ($reading->heart_rate < 60 || $reading->heart_rate > 100) ||
                                                                 ($reading->temperature < 36.0 || $reading->temperature > 37.5) ||
                                                                 ($reading->oxygen_saturation < 95);
                                                @endphp
                                                @if($isAbnormal)
                                                    <span class="badge bg-danger">Anormal</span>
                                                @else
                                                    <span class="badge bg-success">Normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" title="Voir Détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" title="Graphique">
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
                        @if($recentReadings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-4">
                            {{ $recentReadings->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-heartbeat fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun Signe Vital Enregistré</h5>
                            <p class="text-muted">Aucun signe vital n'a encore été enregistré.</p>
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
                        <i class="fas fa-chart-line me-2"></i>Tendances de Tension Artérielle
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
                        <i class="fas fa-chart-area me-2"></i>Tendances de Température
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
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertes Critiques de Signes Vitaux
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-heartbeat me-2"></i>Tension Artérielle Élevée</h6>
                                <p class="mb-0">Patient: John Doe - TA: 180/110</p>
                                <small>Heure: 14:30</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-thermometer-half me-2"></i>Température Élevée</h6>
                                <p class="mb-0">Patient: Jane Smith - Temp: 39.2°C</p>
                                <small>Heure: 14:25</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-lungs me-2"></i>Saturation O2 Faible</h6>
                                <p class="mb-0">Patient: Bob Johnson - O2: 85%</p>
                                <small>Heure: 14:20</small>
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
