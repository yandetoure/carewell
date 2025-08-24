@extends('layouts.secretary')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        Vue d'ensemble
                    </h1>
                    <p class="text-muted mb-0">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Nouveau RDV
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Gérer Patients
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $newPatients ?? 0 }}</h4>
                            <p class="text-muted mb-0">Nouveaux Patients</p>
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
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $callsToday ?? 0 }}</h4>
                            <p class="text-muted mb-0">Appels Aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Today's Schedule -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Planning d'aujourd'hui
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="printSchedule()">
                                <i class="fas fa-print me-1"></i>Imprimer
                            </button>
                            <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary btn-sm">
                                Voir tous
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($todaySchedule) && $todaySchedule->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaySchedule as $appointment)
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
                                            @if($appointment->doctor)
                                                <div class="d-flex align-items-center">
                                                    @if($appointment->doctor->photo)
                                                        <img src="{{ asset('storage/' . $appointment->doctor->photo) }}" 
                                                             alt="Photo médecin" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user-md text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div>
                                                        <small class="text-muted">{{ $appointment->doctor->grade->name ?? 'Médecin' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
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
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" 
                                                   class="btn btn-outline-warning" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-success" 
                                                        onclick="confirmAppointment({{ $appointment->id }})" 
                                                        title="Confirmer">
                                                    <i class="fas fa-check"></i>
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
                            <small>Profitez-en pour organiser la semaine ou contacter les patients</small>
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
                        <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Nouveau RDV
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-success">
                            <i class="fas fa-user-plus me-2"></i>Nouveau patient
                        </a>
                        <a href="{{ route('admin.services') }}" class="btn btn-outline-info">
                            <i class="fas fa-stethoscope me-2"></i>Gérer services
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-warning">
                            <i class="fas fa-phone me-2"></i>Appels entrants
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Calls -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        Appels récents
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentCalls) && $recentCalls->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCalls as $call)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="call-icon me-2">
                                        @if($call->type == 'incoming')
                                            <i class="fas fa-phone text-success"></i>
                                        @else
                                            <i class="fas fa-phone text-primary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small">{{ $call->patient_name ?? 'Patient' }}</div>
                                        <small class="text-muted">{{ $call->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-{{ $call->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($call->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-phone fa-2x mb-2"></i>
                            <p class="mb-0">Aucun appel récent</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Tasks -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Tâches à faire
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" id="task1">
                            <label for="task1" class="form-check-label mb-0">Confirmer les RDV de demain</label>
                        </div>
                        <div class="list-group-item px-0 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" id="task2">
                            <label for="task2" class="form-check-label mb-0">Appeler les patients en attente</label>
                        </div>
                        <div class="list-group-item px-0 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" id="task3">
                            <label for="task3" class="form-check-label mb-0">Mettre à jour les dossiers</label>
                        </div>
                        <div class="list-group-item px-0 d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" id="task4">
                            <label for="task4" class="form-check-label mb-0">Préparer les ordonnances</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Tools -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques de la semaine
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="weeklyStatsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Notifications importantes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> 3 rendez-vous en attente de confirmation
                    </div>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Info :</strong> Nouveau médecin disponible à partir de lundi
                    </div>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Succès :</strong> Tous les dossiers de la semaine sont à jour
                    </div>
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

.call-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary { background-color: var(--primary-color) !important; }
.bg-success { background-color: var(--success-color) !important; }
.bg-warning { background-color: var(--warning-color) !important; }
.bg-info { background-color: var(--info-color) !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart for weekly statistics
const weeklyStatsCtx = document.getElementById('weeklyStatsChart').getContext('2d');
new Chart(weeklyStatsCtx, {
    type: 'bar',
    data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        datasets: [{
            label: 'Rendez-vous',
            data: [15, 22, 18, 25, 20, 8, 0],
            backgroundColor: '#007bff',
            borderColor: '#0056b3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function confirmAppointment(appointmentId) {
    if (confirm('Voulez-vous confirmer ce rendez-vous ?')) {
        // Ici vous pouvez ajouter la logique pour confirmer le rendez-vous
        alert('Rendez-vous confirmé !');
    }
}

function printSchedule() {
    window.print();
}

// Gestion des tâches
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            this.parentElement.style.textDecoration = 'line-through';
            this.parentElement.style.opacity = '0.6';
        } else {
            this.parentElement.style.textDecoration = 'none';
            this.parentElement.style.opacity = '1';
        }
    });
});
</script>
@endsection
