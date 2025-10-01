@extends('layouts.admin')

@section('title', 'Gestion des Soins Médicaux - Admin')
@section('page-title', 'Gestion des Soins Médicaux')
@section('page-subtitle', 'Gestion des prescriptions et soins disponibles')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Statistiques des prescriptions -->
        <div class="col-12 mb-4">
            <div class="row g-4">
                <div class="col-xl-4 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-file-medical text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $totalPrescriptions }}</h4>
                                    <p class="text-muted mb-0">Total des soins</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-stethoscope text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $totalServices }}</h4>
                                    <p class="text-muted mb-0">Services actifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-layer-group text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ $prescriptionsByService->count() }}</h4>
                                    <p class="text-muted mb-0">Catégories</p>
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
                                <i class="fas fa-plus me-2"></i>Nouveau soin
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" onclick="exportPrescriptions()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100" onclick="generateReport()">
                                <i class="fas fa-file-alt me-2"></i>Rapport
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100" onclick="location.reload()">
                                <i class="fas fa-sync-alt me-2"></i>Actualiser
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
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Rechercher un soin..." id="searchPrescription">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterService">
                                <option value="">Tous les services</option>
                                @foreach(\App\Models\Service::all() as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Réinitialiser
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
                        Liste des soins médicaux
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="prescriptionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom du soin</th>
                                    <th>Service</th>
                                    <th>Quantité</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $prescription->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $prescription->service->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $prescription->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($prescription->price, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewPrescription({{ $prescription->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editPrescription({{ $prescription->id }})" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deletePrescription({{ $prescription->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-file-medical fa-3x mb-3"></i>
                                        <h5>Aucun soin médical trouvé</h5>
                                        <p>Commencez par créer votre premier soin.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                                            <i class="fas fa-plus me-2"></i>Nouveau soin
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($prescriptions->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $prescriptions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Soins par service -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition par service
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($prescriptionsByService as $serviceName => $servicePresciptions)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">{{ $serviceName ?? 'Non défini' }}</h6>
                                    <p class="card-text">
                                        <span class="h4 text-dark">{{ $servicePresciptions->count() }}</span>
                                        <small class="text-muted"> soin(s)</small>
                                    </p>
                                    <small class="text-muted">
                                        Prix total: {{ number_format($servicePresciptions->sum('price'), 0, ',', ' ') }} FCFA
                                    </small>
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

<!-- Modal d'ajout de prescription -->
<div class="modal fade" id="addPrescriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau soin médical</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.prescriptions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du soin *</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Ex: Nébulisation, Perfusion intraveineuse...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Service *</label>
                        <select class="form-select" id="service_id" name="service_id" required>
                            <option value="">Sélectionner un service</option>
                            @foreach(\App\Models\Service::all() as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantité *</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required min="1" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Prix (FCFA) *</label>
                                <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01" placeholder="5000">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le soin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewPrescription(id) {
    window.location.href = `/admin/prescriptions/${id}`;
}

function editPrescription(id) {
    window.location.href = `/admin/prescriptions/${id}/edit`;
}

function deletePrescription(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce soin ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/prescriptions/${id}`;
        
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
    const search = document.getElementById('searchPrescription').value.toLowerCase();
    const service = document.getElementById('filterService').value;
    const rows = document.querySelectorAll('#prescriptionsTable tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[1]?.textContent.toLowerCase() || '';
        const serviceCell = row.cells[2]?.textContent || '';
        
        const matchesSearch = !search || name.includes(search);
        const matchesService = !service || serviceCell.includes(service);
        
        row.style.display = matchesSearch && matchesService ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('searchPrescription').value = '';
    document.getElementById('filterService').value = '';
    const rows = document.querySelectorAll('#prescriptionsTable tbody tr');
    rows.forEach(row => row.style.display = '');
}

function exportPrescriptions() {
    alert('Export des soins médicaux en cours...');
}

function generateReport() {
    alert('Génération du rapport des soins médicaux...');
}

// Recherche en temps réel
document.getElementById('searchPrescription')?.addEventListener('input', applyFilters);
</script>
@endsection

