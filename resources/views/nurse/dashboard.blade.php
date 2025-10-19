@extends('layouts.nurse')

@section('title', 'Nurse Dashboard - CareWell')
@section('page-title', 'Nursing Dashboard')
@section('page-subtitle', 'Patient Care and Medical Management')
@section('user-role', 'Nurse')

@section('content')
<div class="container-fluid py-4">
    <!-- Real-time Status Indicator -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="connectionStatus">
                <i class="fas fa-wifi me-2"></i>
                <span id="statusText">Connexion en cours...</span>
                <span class="badge bg-success ms-2" id="lastUpdate">Dernière mise à jour: --:--</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <!-- Real-time Notifications -->
    <div class="row mb-3" id="notificationsContainer" style="display: none;">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-bell me-2"></i>
                <span id="notificationText"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

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
                            <h4 class="mb-1" data-stat="totalPatients">{{ $totalPatients }}</h4>
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
                            <h4 class="mb-1" data-stat="hospitalizedPatients">{{ $hospitalizedPatients }}</h4>
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
                            <h4 class="mb-1" data-stat="todayAppointments">{{ $todayAppointments }}</h4>
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
                            <h4 class="mb-1" data-stat="pendingPrescriptions">{{ $pendingPrescriptions }}</h4>
                            <p class="text-muted mb-0">Pending Prescriptions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Alerts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertes Signes Vitaux
                        <span class="badge bg-danger ms-2" id="vitalAlertsCount">0</span>
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div id="vitalSignsAlerts">
                        <div class="text-center text-muted">
                            <i class="fas fa-heartbeat fa-2x mb-2"></i>
                            <p>Aucune alerte en cours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-pills me-2"></i>Prescriptions Urgentes
                        <span class="badge bg-warning ms-2" id="urgentPrescriptionsCount">0</span>
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div id="urgentPrescriptions">
                        <div class="text-center text-muted">
                            <i class="fas fa-pills fa-2x mb-2"></i>
                            <p>Aucune prescription urgente</p>
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
                        <button class="btn btn-sm btn-outline-primary float-end" onclick="refreshChart()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
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
                        <button class="btn btn-sm btn-outline-primary float-end" onclick="refreshBedStats()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="text-primary" id="totalBeds">{{ $bedOccupancy['total'] }}</h3>
                            <p class="text-muted mb-0">Total Beds</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success" id="availableBeds">{{ $bedOccupancy['available'] }}</h3>
                            <p class="text-muted mb-0">Available</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning" id="occupiedBeds">{{ $bedOccupancy['occupied'] }}</h3>
                            <p class="text-muted mb-0">Occupied</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 id="occupancyProgressBar"
                                 style="width: {{ $bedOccupancy['occupancy_rate'] }}%"
                                 aria-valuenow="{{ $bedOccupancy['occupancy_rate'] }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                <span id="occupancyPercentage">{{ $bedOccupancy['occupancy_rate'] }}%</span>
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
                        <div class="float-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="refreshSchedule()">
                                    <i class="fas fa-sync-alt"></i> Actualiser
                                </button>
                                <button type="button" class="btn btn-outline-success" id="autoRefreshBtn" onclick="toggleAutoRefresh()">
                                    <i class="fas fa-play"></i> Auto-actualisation
                                </button>
                            </div>
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="scheduleContent">
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
                                    <tbody id="scheduleTableBody">
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

.alert-sm {
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.stat-icon {
    transition: transform 0.3s ease;
}

.stat-icon:hover {
    transform: scale(1.1);
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.alert-danger .badge {
    animation: none;
}

.progress-bar {
    transition: width 0.6s ease;
}

.auto-refresh-indicator {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
}

.notification-toast {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 1050;
    min-width: 300px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Global variables
let serviceActivityChart;
let autoRefreshInterval;
let isAutoRefreshEnabled = false;
let connectionStatus = true;

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startRealTimeUpdates();
    updateConnectionStatus();
});

// Initialize charts
function initializeCharts() {
    const serviceActivityCtx = document.getElementById('serviceActivityChart').getContext('2d');
    serviceActivityChart = new Chart(serviceActivityCtx, {
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
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Real-time updates
function startRealTimeUpdates() {
    // Update every 30 seconds
    setInterval(function() {
        if (connectionStatus) {
            updateDashboardStats();
            updateNotifications();
        }
    }, 30000);

    // Initial load
    updateDashboardStats();
    updateNotifications();
}

// Update dashboard statistics
function updateDashboardStats() {
    fetch('{{ route("nurse.dashboard.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Update statistics cards
                updateStatCard('totalPatients', data.totalPatients);
                updateStatCard('hospitalizedPatients', data.hospitalizedPatients);
                updateStatCard('todayAppointments', data.todayAppointments);
                updateStatCard('pendingPrescriptions', data.pendingPrescriptions);

                // Update bed occupancy
                document.getElementById('totalBeds').textContent = data.bedOccupancy.total;
                document.getElementById('availableBeds').textContent = data.bedOccupancy.available;
                document.getElementById('occupiedBeds').textContent = data.bedOccupancy.occupied;
                
                const progressBar = document.getElementById('occupancyProgressBar');
                const percentage = data.bedOccupancy.occupancy_rate;
                progressBar.style.width = percentage + '%';
                progressBar.setAttribute('aria-valuenow', percentage);
                progressBar.querySelector('span').textContent = percentage + '%';

                // Update chart
                updateChart(data.serviceStats);

                // Update alerts
                updateVitalSignsAlerts(data.vitalSignsAlerts);
                updateUrgentPrescriptions(data.urgentPrescriptions);

                // Update last update time
                document.getElementById('lastUpdate').textContent = 'Dernière mise à jour: ' + data.lastUpdated;
                document.getElementById('statusText').textContent = 'Dashboard connecté et mis à jour';
                
                // Update connection status
                const statusAlert = document.getElementById('connectionStatus');
                statusAlert.className = 'alert alert-success alert-dismissible fade show';
                connectionStatus = true;
            }
        })
        .catch(error => {
            console.error('Error updating dashboard stats:', error);
            document.getElementById('statusText').textContent = 'Erreur de connexion - Tentative de reconnexion...';
            const statusAlert = document.getElementById('connectionStatus');
            statusAlert.className = 'alert alert-danger alert-dismissible fade show';
            connectionStatus = false;
        });
}

// Update notifications
function updateNotifications() {
    fetch('{{ route("nurse.dashboard.notifications") }}')
        .then(response => response.json())
        .then(data => {
            if (data && data.notifications && data.notifications.length > 0) {
                showNotifications(data.notifications);
            } else {
                hideNotifications();
            }
        })
        .catch(error => {
            console.error('Error updating notifications:', error);
        });
}

// Update individual stat card
function updateStatCard(elementId, value) {
    const element = document.querySelector(`[data-stat="${elementId}"]`);
    if (element) {
        element.textContent = value;
        // Add animation effect
        element.style.transform = 'scale(1.1)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    }
}

// Update chart data
function updateChart(serviceStats) {
    if (serviceActivityChart && serviceStats) {
        const labels = serviceStats.map(service => service.name);
        const data = serviceStats.map(service => service.appointments_count);
        
        serviceActivityChart.data.labels = labels;
        serviceActivityChart.data.datasets[0].data = data;
        serviceActivityChart.update('active');
    }
}

// Update vital signs alerts
function updateVitalSignsAlerts(alerts) {
    const container = document.getElementById('vitalSignsAlerts');
    const countBadge = document.getElementById('vitalAlertsCount');
    
    countBadge.textContent = alerts.length;
    
    if (alerts.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-heartbeat fa-2x mb-2"></i>
                <p>Aucune alerte en cours</p>
            </div>
        `;
    } else {
        let alertsHtml = '';
        alerts.forEach(alert => {
            const alertClass = getAlertClass(alert.type);
            alertsHtml += `
                <div class="alert ${alertClass} alert-sm mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${alert.patient_name}</strong><br>
                            <small>${alert.value} - ${alert.recorded_at}</small>
                        </div>
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            `;
        });
        container.innerHTML = alertsHtml;
    }
}

// Update urgent prescriptions
function updateUrgentPrescriptions(prescriptions) {
    const container = document.getElementById('urgentPrescriptions');
    const countBadge = document.getElementById('urgentPrescriptionsCount');
    
    countBadge.textContent = prescriptions.length;
    
    if (prescriptions.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-pills fa-2x mb-2"></i>
                <p>Aucune prescription urgente</p>
            </div>
        `;
    } else {
        let prescriptionsHtml = '';
        prescriptions.forEach(prescription => {
            const urgencyClass = getUrgencyClass(prescription.urgency_level);
            prescriptionsHtml += `
                <div class="alert ${urgencyClass} alert-sm mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${prescription.patient_name}</strong><br>
                            <small>${prescription.medication} - ${prescription.dosage}</small><br>
                            <small class="text-muted">Depuis ${prescription.created_at}</small>
                        </div>
                        <i class="fas fa-pills"></i>
                    </div>
                </div>
            `;
        });
        container.innerHTML = prescriptionsHtml;
    }
}

// Get alert class based on type
function getAlertClass(type) {
    switch(type) {
        case 'high_temperature':
        case 'low_oxygen':
        case 'high_blood_pressure':
        case 'low_blood_pressure':
            return 'alert-danger';
        case 'high_heart_rate':
        case 'low_heart_rate':
        case 'low_temperature':
            return 'alert-warning';
        default:
            return 'alert-info';
    }
}

// Get urgency class
function getUrgencyClass(urgency) {
    switch(urgency) {
        case 'critical':
            return 'alert-danger';
        case 'high':
            return 'alert-warning';
        default:
            return 'alert-info';
    }
}

// Show notifications
function showNotifications(notifications) {
    const container = document.getElementById('notificationsContainer');
    const text = document.getElementById('notificationText');
    
    let notificationHtml = '';
    notifications.forEach(notification => {
        notificationHtml += `<span class="badge bg-${notification.color} me-2">${notification.message}</span>`;
    });
    
    text.innerHTML = notificationHtml;
    container.style.display = 'block';
    
    // Auto-hide after 10 seconds
    setTimeout(() => {
        container.style.display = 'none';
    }, 10000);
}

// Hide notifications
function hideNotifications() {
    document.getElementById('notificationsContainer').style.display = 'none';
}

// Update connection status
function updateConnectionStatus() {
    setInterval(() => {
        fetch('{{ route("nurse.dashboard.stats") }}')
            .then(response => {
                if (response.ok) {
                    connectionStatus = true;
                    document.getElementById('statusText').textContent = 'Dashboard connecté';
                    document.getElementById('connectionStatus').className = 'alert alert-success alert-dismissible fade show';
                } else {
                    throw new Error('Connection failed');
                }
            })
            .catch(() => {
                connectionStatus = false;
                document.getElementById('statusText').textContent = 'Connexion perdue - Reconnexion...';
                document.getElementById('connectionStatus').className = 'alert alert-danger alert-dismissible fade show';
            });
    }, 60000); // Check every minute
}

// Manual refresh functions
function refreshChart() {
    updateDashboardStats();
}

function refreshBedStats() {
    updateDashboardStats();
}

function refreshSchedule() {
    updateDashboardStats();
}

// Toggle auto-refresh
function toggleAutoRefresh() {
    const btn = document.getElementById('autoRefreshBtn');
    
    if (isAutoRefreshEnabled) {
        clearInterval(autoRefreshInterval);
        btn.innerHTML = '<i class="fas fa-play"></i> Auto-actualisation';
        btn.className = 'btn btn-outline-success';
        isAutoRefreshEnabled = false;
    } else {
        autoRefreshInterval = setInterval(() => {
            if (connectionStatus) {
                updateDashboardStats();
                updateNotifications();
            }
        }, 15000); // Every 15 seconds
        btn.innerHTML = '<i class="fas fa-pause"></i> Arrêter';
        btn.className = 'btn btn-outline-danger';
        isAutoRefreshEnabled = true;
    }
}

// Add visual feedback for updates
function addUpdateAnimation(element) {
    element.style.transition = 'all 0.3s ease';
    element.style.backgroundColor = '#d4edda';
    setTimeout(() => {
        element.style.backgroundColor = '';
    }, 1000);
}

// Handle page visibility change
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Page is hidden, reduce update frequency
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    } else {
        // Page is visible, resume normal updates
        if (isAutoRefreshEnabled) {
            toggleAutoRefresh();
            toggleAutoRefresh(); // This will restart the interval
        }
    }
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'r':
                e.preventDefault();
                updateDashboardStats();
                break;
            case 'n':
                e.preventDefault();
                updateNotifications();
                break;
            case 'a':
                e.preventDefault();
                toggleAutoRefresh();
                break;
        }
    }
});
</script>
@endpush
