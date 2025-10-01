@extends('layouts.admin')

@section('title', 'Gestion des Lits - Admin')
@section('page-title', 'Gestion des Lits')
@section('page-subtitle', 'Suivi et gestion des lits d\'hospitalisation')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreur!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Statistiques des lits -->
        <div class="col-12 mb-4">
            <div class="row g-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-bed text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $totalBeds }}</h4>
                                    <p class="text-muted mb-0">Total des lits</p>
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
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $availableBeds }}</h4>
                                    <p class="text-muted mb-0">Lits disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-danger">
                                    <i class="fas fa-user-injured text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $occupiedBeds }}</h4>
                                    <p class="text-muted mb-0">Lits occupés</p>
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
                                    <i class="fas fa-percentage text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0 }}%</h4>
                                    <p class="text-muted mb-0">Taux d'occupation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addBedModal">
                                <i class="fas fa-plus me-2"></i>Ajouter un lit
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" onclick="exportBeds()">
                                <i class="fas fa-download me-2"></i>Exporter état
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100" onclick="maintenanceMode()">
                                <i class="fas fa-wrench me-2"></i>Mode maintenance
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100" onclick="generateReport()">
                                <i class="fas fa-file-alt me-2"></i>Rapport occupation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vue d'ensemble des lits -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bed me-2"></i>
                        État des lits
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshBeds()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="toggleView()">
                            <i class="fas fa-th me-1"></i>Vue grille
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Vue grille des lits -->
                    <div class="row g-3" id="bedsGrid">
                        @foreach($beds as $bed)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <div class="card bed-card {{ $bed['status'] === 'occupied' ? 'border-danger' : 'border-success' }}" 
                                 onclick="viewBedDetails({{ $bed['id'] }})">
                                <div class="card-body text-center p-3">
                                    <div class="bed-icon mb-2">
                                        @if($bed['status'] === 'occupied')
                                            <i class="fas fa-bed text-danger fa-2x"></i>
                                        @else
                                            <i class="fas fa-bed text-success fa-2x"></i>
                                        @endif
                                    </div>
                                    <h6 class="mb-1">Lit {{ $bed['number'] }}</h6>
                                    <span class="badge bg-{{ $bed['status'] === 'occupied' ? 'danger' : 'success' }}">
                                        {{ $bed['status'] === 'occupied' ? 'Occupé' : 'Disponible' }}
                                    </span>
                                    @if($bed['patient'])
                                        <div class="mt-2">
                                            <small class="text-muted">{{ $bed['patient'] }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Vue tableau des lits -->
                    <div class="table-responsive d-none" id="bedsTable">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Numéro</th>
                                    <th>Statut</th>
                                    <th>Patient</th>
                                    <th>Date d'admission</th>
                                    <th>Service</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($beds as $bed)
                                <tr>
                                    <td>
                                        <span class="fw-bold">Lit {{ $bed['number'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $bed['status'] === 'occupied' ? 'danger' : 'success' }}">
                                            {{ $bed['status'] === 'occupied' ? 'Occupé' : 'Disponible' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bed['patient'])
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white" style="font-size: 0.8em;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $bed['patient'] }}</div>
                                                    <small class="text-muted">Patient ID: {{ $bed['number'] }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Aucun patient</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bed['status'] === 'occupied')
                                            {{ now()->subDays(rand(1, 10))->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bed['status'] === 'occupied')
                                            <span class="badge bg-info">Service {{ rand(1, 5) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewBedDetails({{ $bed['id'] }})" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($bed['status'] === 'occupied')
                                                <button class="btn btn-outline-warning" onclick="dischargePatient({{ $bed['id'] }})" title="Libérer le lit">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-outline-success" onclick="assignPatient({{ $bed['id'] }})" title="Assigner patient">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-info" onclick="maintenanceBed({{ $bed['id'] }})" title="Maintenance">
                                                <i class="fas fa-wrench"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques détaillées -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Occupation par service
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Évolution de l'occupation
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="occupationChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Historique des mouvements -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique des mouvements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date/Heure</th>
                                    <th>Lit</th>
                                    <th>Action</th>
                                    <th>Patient</th>
                                    <th>Utilisateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 15; $i++)
                                <tr>
                                    <td>
                                        <div>{{ now()->subHours($i)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ now()->subHours($i)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">Lit {{ rand(1, 50) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $actions = ['Admission', 'Sortie', 'Transfert', 'Maintenance'];
                                            $action = $actions[array_rand($actions)];
                                            $actionColors = ['Admission' => 'success', 'Sortie' => 'warning', 'Transfert' => 'info', 'Maintenance' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $actionColors[$action] }}">{{ $action }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 24px; height: 24px;">
                                                <i class="fas fa-user text-white" style="font-size: 0.7em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">Patient {{ $i }}</div>
                                                <small class="text-muted">ID: {{ rand(1000, 9999) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">Dr. Médecin {{ $i }}</span>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de lit -->
<div class="modal fade" id="addBedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un nouveau lit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.beds.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bed_number" class="form-label">Numéro du lit *</label>
                        <input type="text" class="form-control" id="bed_number" name="bed_number" 
                               placeholder="Ex: L001" required>
                        <small class="text-muted">Doit être unique</small>
                    </div>
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Numéro de chambre *</label>
                        <input type="text" class="form-control" id="room_number" name="room_number" 
                               placeholder="Ex: R001" required>
                    </div>
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Service</label>
                        <select class="form-select" id="service_id" name="service_id">
                            <option value="">Sélectionner un service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bed_type" class="form-label">Type de lit *</label>
                        <select class="form-select" id="bed_type" name="bed_type" required>
                            <option value="standard">Standard</option>
                            <option value="premium">Premium</option>
                            <option value="vip">VIP</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter le lit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique par service
const serviceCtx = document.getElementById('serviceChart').getContext('2d');
new Chart(serviceCtx, {
    type: 'bar',
    data: {
        labels: ['Médecine', 'Chirurgie', 'Pédiatrie', 'Maternité', 'Urgences'],
        datasets: [{
            label: 'Lits occupés',
            data: [12, 8, 6, 4, 10],
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        }, {
            label: 'Lits disponibles',
            data: [8, 12, 14, 16, 10],
            backgroundColor: 'rgba(75, 192, 192, 0.8)'
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

// Graphique d'évolution
const occupationCtx = document.getElementById('occupationChart').getContext('2d');
new Chart(occupationCtx, {
    type: 'line',
    data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        datasets: [{
            label: 'Taux d\'occupation (%)',
            data: [65, 70, 75, 80, 85, 60, 55],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

function viewBedDetails(bedId) {
    window.location.href = `/admin/beds/${bedId}`;
}

function assignPatient(bedNumber) {
    const patientId = prompt(`Assigner un patient au lit ${bedNumber}:\nEntrez l'ID du patient:`);
    if (patientId) {
        alert(`Patient ${patientId} assigné au lit ${bedNumber}`);
        location.reload();
    }
}

function dischargePatient(bedNumber) {
    if (confirm(`Libérer le lit ${bedNumber} ?`)) {
        alert(`Lit ${bedNumber} libéré avec succès`);
        location.reload();
    }
}

function maintenanceBed(bedNumber) {
    if (confirm(`Mettre le lit ${bedNumber} en maintenance ?`)) {
        alert(`Lit ${bedNumber} mis en maintenance`);
        location.reload();
    }
}

function toggleView() {
    const grid = document.getElementById('bedsGrid');
    const table = document.getElementById('bedsTable');
    
    if (grid.classList.contains('d-none')) {
        grid.classList.remove('d-none');
        table.classList.add('d-none');
    } else {
        grid.classList.add('d-none');
        table.classList.remove('d-none');
    }
}

function refreshBeds() {
    location.reload();
}

function exportBeds() {
    alert('Export de l\'état des lits en cours...');
}

function maintenanceMode() {
    alert('Mode maintenance activé');
}

function generateReport() {
    alert('Génération du rapport d\'occupation...');
}
</script>

<style>
.bed-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.bed-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bed-icon {
    transition: transform 0.2s;
}

.bed-card:hover .bed-icon {
    transform: scale(1.1);
}
</style>
@endsection
