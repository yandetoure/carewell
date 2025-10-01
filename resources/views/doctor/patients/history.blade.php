@extends('layouts.doctor')

@section('title', 'Historique Patient - Docteur')
@section('page-title', 'Historique des rendez-vous')
@section('page-subtitle', 'Historique complet des rendez-vous du patient')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- En-tête du patient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="patient-avatar me-3">
                                <div class="avatar bg-primary text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope me-1"></i>{{ $patient->email }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                                </p>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-birthday-cake me-1"></i>
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} ans
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-{{ $patient->gender == 'male' ? 'mars' : 'venus' }} me-1"></i>
                                        {{ $patient->gender == 'male' ? 'Homme' : 'Femme' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.patients.show', $patient) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye me-1"></i>Détails
                            </a>
                            <a href="{{ route('doctor.patients.appointments', $patient) }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-plus me-1"></i>Nouveau RDV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAppointments }}</h4>
                            <p class="text-muted mb-0">Total RDV</p>
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
                            <h4 class="mb-1">{{ $confirmedAppointments }}</h4>
                            <p class="text-muted mb-0">Confirmés</p>
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
                            <i class="fas fa-times-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $cancelledAppointments }}</h4>
                            <p class="text-muted mb-0">Annulés</p>
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
                            <i class="fas fa-percentage text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAppointments > 0 ? round(($confirmedAppointments / $totalAppointments) * 100, 1) : 0 }}%</h4>
                            <p class="text-muted mb-0">Taux de réussite</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des rendez-vous -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>Historique des rendez-vous
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="printHistory()">
                                <i class="fas fa-print me-1"></i>Imprimer
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="exportHistory()">
                                <i class="fas fa-download me-1"></i>Exporter
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr class="{{ $appointment->status == 'cancelled' ? 'table-danger' : ($appointment->status == 'confirmed' ? 'table-success' : 'table-warning') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-secondary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-info me-2"></i>
                                                    {{ $appointment->service->name ?? 'Service non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                                    <i class="fas fa-{{ $appointment->status == 'confirmed' ? 'check' : ($appointment->status == 'pending' ? 'clock' : 'times') }} me-1"></i>
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($appointment->notes)
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                          title="{{ $appointment->notes }}">
                                                        {{ $appointment->notes }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Aucune note</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="viewAppointment({{ $appointment->id }})" 
                                                            title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($appointment->status == 'pending')
                                                        <button type="button" class="btn btn-outline-success" 
                                                                onclick="confirmAppointment({{ $appointment->id }})" 
                                                                title="Confirmer">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="cancelAppointment({{ $appointment->id }})" 
                                                                title="Annuler">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun rendez-vous trouvé</h5>
                            <p class="text-muted">Ce patient n'a pas encore de rendez-vous dans votre service.</p>
                            <a href="{{ route('doctor.patients.appointments', $patient) }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Planifier le premier rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique de l'historique -->
    @if($appointments->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Évolution des rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="appointmentsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal pour voir les détails d'un rendez-vous -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.patient-avatar .avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

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

@media print {
    .btn, .card-header .d-flex {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des rendez-vous
@if($appointments->count() > 0)
const ctx = document.getElementById('appointmentsChart').getContext('2d');
const appointmentsData = @json($appointments->pluck('appointment_date')->map(function($date) {
    return \Carbon\Carbon::parse($date)->format('Y-m');
})->countBy()->sortKeys());

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: Object.keys(appointmentsData),
        datasets: [{
            label: 'Nombre de rendez-vous',
            data: Object.values(appointmentsData),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
@endif

// Fonctions pour les actions
function viewAppointment(appointmentId) {
    // Ici vous pouvez ajouter une requête AJAX pour récupérer les détails
    document.getElementById('appointmentDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    modal.show();
}

function confirmAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir confirmer ce rendez-vous ?')) {
        // Ici vous pouvez ajouter une requête AJAX pour confirmer
        console.log('Confirmer RDV:', appointmentId);
    }
}

function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // Ici vous pouvez ajouter une requête AJAX pour annuler
        console.log('Annuler RDV:', appointmentId);
    }
}

function printHistory() {
    window.print();
}

function exportHistory() {
    // Ici vous pouvez ajouter la logique d'export
    alert('Fonctionnalité d\'export à implémenter');
}
</script>
@endpush
