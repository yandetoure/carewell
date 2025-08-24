@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        Vue d'ensemble
                    </h1>
                    <p class="text-muted mb-0">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
                    </a>
                    <a href="{{ route('admin.services') }}" class="btn btn-outline-primary">
                        <i class="fas fa-cog me-2"></i>Gérer Services
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
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalUsers ?? 0 }}</h4>
                            <p class="text-muted mb-0">Utilisateurs Totaux</p>
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
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAppointments ?? 0 }}</h4>
                            <p class="text-muted mb-0">Rendez-vous</p>
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
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalServices ?? 0 }}</h4>
                            <p class="text-muted mb-0">Services</p>
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
                            <i class="fas fa-newspaper text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalArticles ?? 0 }}</h4>
                            <p class="text-muted mb-0">Articles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- System Overview -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Vue d'ensemble du système
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="exportData()">
                                <i class="fas fa-download me-1"></i>Exporter
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshStats()">
                                <i class="fas fa-sync-alt me-1"></i>Actualiser
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Utilisateurs par rôle</h6>
                                    <canvas id="usersChart" height="150"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Rendez-vous par statut</h6>
                                    <canvas id="appointmentsChart" height="150"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h4 text-primary mb-1">{{ $activeUsers ?? 0 }}</div>
                                <small class="text-muted">Utilisateurs actifs</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h4 text-success mb-1">{{ $confirmedAppointments ?? 0 }}</div>
                                <small class="text-muted">RDV confirmés</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h4 text-warning mb-1">{{ $pendingAppointments ?? 0 }}</div>
                                <small class="text-muted">RDV en attente</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Status -->
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
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Gérer utilisateurs
                        </a>
                        <a href="{{ route('admin.services') }}" class="btn btn-outline-success">
                            <i class="fas fa-stethoscope me-2"></i>Gérer services
                        </a>
                        <a href="{{ route('admin.articles') }}" class="btn btn-outline-info">
                            <i class="fas fa-newspaper me-2"></i>Gérer articles
                        </a>
                        <a href="{{ route('admin.appointments') }}" class="btn btn-outline-warning">
                            <i class="fas fa-calendar me-2"></i>Voir RDV
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-server me-2"></i>
                        État du système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Base de données</span>
                            <span class="badge bg-success">En ligne</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Stockage</span>
                            <span class="badge bg-warning">75% utilisé</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Cache</span>
                            <span class="badge bg-success">Actif</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>Emails</span>
                            <span class="badge bg-success">Fonctionnel</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Activité récente
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentActivity) && $recentActivity->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity as $activity)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="activity-icon me-2">
                                        @if($activity->type == 'user')
                                            <i class="fas fa-user text-primary"></i>
                                        @elseif($activity->type == 'service')
                                            <i class="fas fa-stethoscope text-success"></i>
                                        @elseif($activity->type == 'appointment')
                                            <i class="fas fa-calendar text-warning"></i>
                                        @else
                                            <i class="fas fa-circle text-secondary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small">{{ $activity->description }}</div>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p class="mb-0">Aucune activité récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Metrics -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>
                        Croissance des utilisateurs
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="growthChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition des services
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="servicesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Alertes système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Stockage :</strong> L'espace disque atteint 75% de capacité
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Maintenance :</strong> Sauvegarde automatique programmée pour 02h00
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Sécurité :</strong> Tous les certificats SSL sont à jour
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-primary" role="alert">
                                <i class="fas fa-bell me-2"></i>
                                <strong>Mise à jour :</strong> Nouvelle version disponible (v2.1.0)
                            </div>
                        </div>
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

.activity-icon {
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
// Users by role chart
const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
    type: 'doughnut',
    data: {
        labels: ['Patients', 'Médecins', 'Secrétaires', 'Admins'],
        datasets: [{
            data: [65, 20, 10, 5],
            backgroundColor: [
                '#007bff',
                '#28a745',
                '#ffc107',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});

// Appointments by status chart
const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
new Chart(appointmentsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Confirmés', 'En attente', 'Annulés'],
        datasets: [{
            data: [70, 25, 5],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});

// Growth chart
const growthCtx = document.getElementById('growthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
            label: 'Utilisateurs',
            data: [120, 150, 180, 220, 280, 350],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4
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

// Services chart
const servicesCtx = document.getElementById('servicesChart').getContext('2d');
new Chart(servicesCtx, {
    type: 'bar',
    data: {
        labels: ['Consultation', 'Examen', 'Chirurgie', 'Suivi', 'Urgence'],
        datasets: [{
            label: 'Nombre de RDV',
            data: [45, 30, 15, 25, 10],
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

function exportData() {
    alert('Fonctionnalité d\'export en cours de développement');
}

function refreshStats() {
    location.reload();
}
</script>
@endsection
