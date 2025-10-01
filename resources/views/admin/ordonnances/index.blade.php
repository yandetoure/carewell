@extends('layouts.admin')

@section('title', 'Gestion des Ordonnances - Admin')
@section('page-title', 'Gestion des Ordonnances')
@section('page-subtitle', 'Suivi et gestion des ordonnances médicales avec médicaments')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Statistiques des prescriptions -->
        <div class="col-12 mb-4">
            <div class="row g-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-prescription-bottle-alt text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $totalOrdonnances }}</h4>
                                    <p class="text-muted mb-0">Total ordonnances</p>
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
                                    <h4 class="mb-1">{{ $activeOrdonnances }}</h4>
                                    <p class="text-muted mb-0">Actives</p>
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
                                    <h4 class="mb-1">{{ $expiredOrdonnances }}</h4>
                                    <p class="text-muted mb-0">Expirées</p>
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
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $thisMonthOrdonnances }}</h4>
                                    <p class="text-muted mb-0">Ce mois</p>
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
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                                <i class="fas fa-plus me-2"></i>Nouvelle ordonnance
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" onclick="exportPrescriptions()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100" onclick="checkExpired()">
                                <i class="fas fa-exclamation-triangle me-2"></i>Vérifier expirées
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100" onclick="generateReport()">
                                <i class="fas fa-file-alt me-2"></i>Rapport
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Rechercher une prescription..." id="searchPrescription">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterStatus">
                                <option value="">Tous les statuts</option>
                                <option value="active">Active</option>
                                <option value="expiree">Expirée</option>
                                <option value="annulee">Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterDoctor">
                                <option value="">Tous les médecins</option>
                                @foreach(\App\Models\User::role('Doctor')->get() as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="filterDate" placeholder="Date">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des prescriptions -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des prescriptions
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshPrescriptions()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="prescriptionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Ordonnance</th>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Date prescription</th>
                                    <th>Médicaments</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ordonnances as $ordonnance)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $ordonnance->numero_ordonnance }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white" style="font-size: 0.8em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $ordonnance->patient_nom_complet }}</div>
                                                <small class="text-muted">{{ $ordonnance->patient_email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user-md text-white" style="font-size: 0.8em;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $ordonnance->medecin_nom_complet }}</div>
                                                <small class="text-muted">Dr. {{ $ordonnance->medecin->name ?? 'Médecin' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $ordonnance->date_prescription->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $ordonnance->date_prescription->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $ordonnance->medicaments->count() }} médicament(s)</span>
                                        @if($ordonnance->medicaments->count() > 0)
                                            <div class="mt-1">
                                                @foreach($ordonnance->medicaments->take(2) as $medicament)
                                                    <small class="text-muted d-block">{{ $medicament->nom }}</small>
                                                @endforeach
                                                @if($ordonnance->medicaments->count() > 2)
                                                    <small class="text-muted">+{{ $ordonnance->medicaments->count() - 2 }} autres</small>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'expiree' => 'warning',
                                                'annulee' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$ordonnance->statut] ?? 'secondary' }}">
                                            {{ ucfirst($ordonnance->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewPrescription({{ $ordonnance->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="printPrescription({{ $ordonnance->id }})" title="Imprimer">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editPrescription({{ $ordonnance->id }})" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deletePrescription({{ $ordonnance->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-prescription-bottle-alt fa-3x mb-3"></i>
                                        <h5>Aucune ordonnance trouvée</h5>
                                        <p>Commencez par créer votre première ordonnance.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                            <i class="fas fa-plus me-2"></i>Nouvelle ordonnance
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
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
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition par statut
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Prescriptions par médecin
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="doctorChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Prescriptions récentes -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Ordonnances récentes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($ordonnances->take(6) as $ordonnance)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $ordonnance->numero_ordonnance }}</h6>
                                        <span class="badge bg-{{ $ordonnance->statut === 'active' ? 'success' : ($ordonnance->statut === 'expiree' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($ordonnance->statut) }}
                                        </span>
                                    </div>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $ordonnance->patient_nom_complet }}<br>
                                            <i class="fas fa-user-md me-1"></i>Dr. {{ $ordonnance->medecin_nom_complet }}<br>
                                            <i class="fas fa-calendar me-1"></i>{{ $ordonnance->date_prescription->format('d/m/Y H:i') }}<br>
                                            <i class="fas fa-pills me-1"></i>{{ $ordonnance->medicaments->count() }} médicament(s)
                                        </small>
                                    </p>
                                    <div class="btn-group btn-group-sm w-100">
                                        <button class="btn btn-outline-primary" onclick="viewPrescription({{ $ordonnance->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success" onclick="printPrescription({{ $ordonnance->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout d'ordonnance -->
<div class="modal fade" id="addPrescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle ordonnance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ordonnances.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="patient_id" class="form-label">Patient *</label>
                                <select class="form-select" id="patient_id" name="patient_id" required>
                                    <option value="">Sélectionner un patient</option>
                                    @foreach(\App\Models\User::role('Patient')->get() as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medecin_id" class="form-label">Médecin *</label>
                                <select class="form-select" id="medecin_id" name="medecin_id" required>
                                    <option value="">Sélectionner un médecin</option>
                                    @foreach(\App\Models\User::role('Doctor')->get() as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_prescription" class="form-label">Date de prescription *</label>
                                <input type="datetime-local" class="form-control" id="date_prescription" name="date_prescription" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut *</label>
                                <select class="form-select" id="statut" name="statut" required>
                                    <option value="active">Active</option>
                                    <option value="expiree">Expirée</option>
                                    <option value="annulee">Annulée</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="medicaments" class="form-label">Médicaments *</label>
                        <select class="form-select" id="medicaments" name="medicaments[]" multiple required>
                            @foreach(\App\Models\Medicament::all() as $medicament)
                                <option value="{{ $medicament->id }}">{{ $medicament->nom }} - {{ $medicament->categorie }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs médicaments</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Instructions spéciales</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="3" placeholder="Instructions particulières pour le patient..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer l'ordonnance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des statuts
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Actives', 'Expirées', 'Annulées'],
        datasets: [{
            data: [
                {{ \App\Models\Ordonnance::where('statut', 'active')->count() }},
                {{ \App\Models\Ordonnance::where('statut', 'expiree')->count() }},
                {{ \App\Models\Ordonnance::where('statut', 'annulee')->count() }}
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Graphique des médecins
const doctorCtx = document.getElementById('doctorChart').getContext('2d');
new Chart(doctorCtx, {
    type: 'bar',
    data: {
        labels: ['Dr. Médecin 1', 'Dr. Médecin 2', 'Dr. Médecin 3', 'Dr. Médecin 4'],
        datasets: [{
            label: 'Nombre de prescriptions',
            data: [15, 12, 8, 10],
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
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

function viewPrescription(id) {
    window.location.href = `/admin/ordonnances/${id}`;
}

function printPrescription(id) {
    window.open(`/admin/ordonnances/${id}/print`, '_blank');
}

function editPrescription(id) {
    window.location.href = `/admin/ordonnances/${id}/edit`;
}

function deletePrescription(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette ordonnance ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/ordonnances/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function applyFilters() {
    const search = document.getElementById('searchPrescription').value;
    const status = document.getElementById('filterStatus').value;
    const doctor = document.getElementById('filterDoctor').value;
    const date = document.getElementById('filterDate').value;
    
    console.log('Filtres appliqués:', { search, status, doctor, date });
    // Ici vous pouvez implémenter la logique de filtrage
}

function clearFilters() {
    document.getElementById('searchPrescription').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterDoctor').value = '';
    document.getElementById('filterDate').value = '';
}

function refreshPrescriptions() {
    location.reload();
}

function exportPrescriptions() {
    alert('Export des ordonnances en cours...');
}

function checkExpired() {
    alert('Vérification des ordonnances expirées...');
}

function generateReport() {
    alert('Génération du rapport des ordonnances...');
}
</script>
@endsection
