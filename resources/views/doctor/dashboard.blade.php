@extends('layouts.doctor')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-md text-primary me-2"></i>
                        Vue d'ensemble
                    </h1>
                    <p class="text-muted mb-0">Bienvenue, Dr. {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Nouveau RDV
                    </a>
                    <a href="{{ route('doctor.patients') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Mes Patients
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    @if(($urgentAppointments ?? 0) > 0 || ($overdueAppointments ?? 0) > 0)
    <div class="row mb-4">
        <div class="col-12">
            @if(($urgentAppointments ?? 0) > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Attention !</strong> Vous avez {{ $urgentAppointments }} rendez-vous urgent(s) dans les 24 prochaines heures.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(($overdueAppointments ?? 0) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-clock me-2"></i>
                <strong>Urgent !</strong> Vous avez {{ $overdueAppointments }} rendez-vous en retard nécessitant une action immédiate.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $todayAppointments ?? 0 }}</h4>
                            <p class="text-muted mb-0">RDV Aujourd'hui</p>
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>{{ $confirmedAppointments ?? 0 }} confirmés
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPatients ?? 0 }}</h4>
                            <p class="text-muted mb-0">Patients Totaux</p>
                            <small class="text-info">
                                <i class="fas fa-calendar-week me-1"></i>{{ $weekAppointments ?? 0 }} cette semaine
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingAppointments ?? 0 }}</h4>
                            <p class="text-muted mb-0">RDV en Attente</p>
                            <small class="text-warning">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $urgentAppointments ?? 0 }} urgent(s)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format($monthlyRevenue ?? 0, 0, ',', ' ') }} FCFA</h4>
                            <p class="text-muted mb-0">Revenus du mois</p>
                            <small class="text-success">
                                <i class="fas fa-calendar-alt me-1"></i>{{ $monthAppointments ?? 0 }} RDV
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques supplémentaires -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-secondary">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalPrescriptions ?? 0 }}</h4>
                            <p class="text-muted mb-0">Prescriptions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-dark">
                            <i class="fas fa-flask text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalExams ?? 0 }}</h4>
                            <p class="text-muted mb-0">Examens disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ ($urgentAppointments ?? 0) + ($overdueAppointments ?? 0) }}</h4>
                            <p class="text-muted mb-0">Actions requises</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Today's Appointments -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Rendez-vous d'aujourd'hui
                        </h5>
                        <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-primary btn-sm">
                            Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($todayAppointmentsList) && $todayAppointmentsList->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayAppointmentsList as $appointment)
                                    <tr>
                                        <td>
                                            <strong>{{ $appointment->appointment_time }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($appointment->user && $appointment->user->photo)
                                                    <img src="{{ asset('storage/' . $appointment->user->photo) }}" 
                                                         alt="Photo patient" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->user->first_name ?? 'N/A' }} {{ $appointment->user->last_name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $appointment->user->phone_number ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($appointment->service)
                                                <span class="badge bg-primary">{{ $appointment->service->name }}</span>
                                            @else
                                                <span class="text-muted">Non spécifié</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('appointments.show', $appointment->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($appointment->status == 'pending')
                                                    <button class="btn btn-outline-success" 
                                                            onclick="confirmAppointment({{ $appointment->id }})" 
                                                            title="Confirmer">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($appointment->status == 'confirmed')
                                                    <button class="btn btn-outline-success" 
                                                            onclick="startConsultation({{ $appointment->id }})" 
                                                            title="Commencer consultation">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-warning" 
                                                        onclick="cancelAppointment({{ $appointment->id }})" 
                                                        title="Annuler">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p class="mb-0">Aucun rendez-vous aujourd'hui</p>
                            <small>Profitez-en pour consulter vos dossiers ou planifier de nouveaux rendez-vous</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.patients.new') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Nouveau patient
                        </a>
                        <a href="{{ route('doctor.prescriptions') }}" class="btn btn-outline-success">
                            <i class="fas fa-pills me-2"></i>Nouvelle prescription
                        </a>
                        <a href="{{ route('doctor.exams') }}" class="btn btn-outline-info">
                            <i class="fas fa-stethoscope me-2"></i>Prescrire examen
                        </a>
                        <a href="{{ route('availability.create') }}" class="btn btn-outline-warning">
                            <i class="fas fa-clock me-2"></i>Gérer disponibilités
                        </a>
                        <a href="{{ route('doctor.consultations') }}" class="btn btn-outline-dark">
                            <i class="fas fa-stethoscope me-2"></i>Mes consultations
                        </a>
                    </div>
                </div>
            </div>

            <!-- Prochains rendez-vous -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Prochains rendez-vous
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingAppointments as $appointment)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $appointment->user->first_name ?? 'N/A' }} {{ $appointment->user->last_name ?? 'N/A' }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                            <i class="fas fa-clock ms-2 me-1"></i>
                                            {{ $appointment->appointment_time }}
                                        </small>
                                        @if($appointment->service)
                                            <div class="mt-1">
                                                <span class="badge bg-primary">{{ $appointment->service->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ms-2">
                                        <a href="{{ route('appointments.show', $appointment->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-primary btn-sm">
                                Voir tous les rendez-vous
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p class="mb-0">Aucun rendez-vous à venir</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Patients -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Patients récents
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentPatients) && $recentPatients->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentPatients as $patient)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    @if($patient->photo)
                                        <img src="{{ asset('storage/' . $patient->photo) }}" 
                                             alt="Photo patient" 
                                             class="rounded-circle me-2" 
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                        <small class="text-muted">{{ $patient->email }}</small>
                                    </div>
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p class="mb-0">Aucun patient récent</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Rendez-vous cette semaine
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="appointmentsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition par service
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="servicesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.bg-primary { background-color: var(--primary-color) !important; }
.bg-success { background-color: var(--success-color) !important; }
.bg-warning { background-color: var(--warning-color) !important; }
.bg-info { background-color: var(--info-color) !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart for weekly appointments with real data
const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
const chartData = @json($chartData ?? []);
const chartLabels = chartData.map(item => item.label);
const chartCounts = chartData.map(item => item.count);

new Chart(appointmentsCtx, {
    type: 'line',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Rendez-vous',
            data: chartCounts,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    title: function(context) {
                        const index = context[0].dataIndex;
                        const date = chartData[index].date;
                        return new Date(date).toLocaleDateString('fr-FR');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Chart for services distribution with real data
const servicesCtx = document.getElementById('servicesChart').getContext('2d');
const servicesData = @json($servicesDistribution ?? []);
const serviceLabels = servicesData.map(item => item.name);
const serviceCounts = servicesData.map(item => item.count);
const serviceColors = [
    '#007bff', '#28a745', '#ffc107', '#dc3545', 
    '#6f42c1', '#20c997', '#fd7e14', '#e83e8c'
];

new Chart(servicesCtx, {
    type: 'doughnut',
    data: {
        labels: serviceLabels.length > 0 ? serviceLabels : ['Aucun service'],
        datasets: [{
            data: serviceCounts.length > 0 ? serviceCounts : [1],
            backgroundColor: serviceColors.slice(0, serviceLabels.length || 1)
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        return `${label}: ${value} RDV (${percentage}%)`;
                    }
                }
            }
        }
    }
});

function startConsultation(appointmentId) {
    if (confirm('Voulez-vous commencer la consultation pour ce patient ?')) {
        // Rediriger vers la page de consultation
        window.location.href = `/doctor/consultation/${appointmentId}`;
    }
}

function confirmAppointment(appointmentId) {
    if (confirm('Confirmer ce rendez-vous ?')) {
        fetch(`/appointments/${appointmentId}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la confirmation du rendez-vous');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la confirmation du rendez-vous');
        });
    }
}

function cancelAppointment(appointmentId) {
    if (confirm('Annuler ce rendez-vous ?')) {
        fetch(`/appointments/${appointmentId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation du rendez-vous');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'annulation du rendez-vous');
        });
    }
}

// Auto-refresh des données toutes les 5 minutes
setInterval(function() {
    // Rafraîchir seulement les données critiques
    location.reload();
}, 300000); // 5 minutes
</script>
@endsection
