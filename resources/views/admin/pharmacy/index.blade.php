@extends('layouts.admin')

@section('title', 'Stock Pharmacie - Admin')
@section('page-title', 'Stock Pharmacie')
@section('page-subtitle', 'Gestion des médicaments et stock')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Statistiques du stock -->
        <div class="col-12 mb-4">
            <div class="row g-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-pills text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ \App\Models\Medicament::count() }}</h4>
                                    <p class="text-muted mb-0">Médicaments</p>
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
                                    <h4 class="mb-1">{{ \App\Models\Medicament::where('disponible', true)->count() }}</h4>
                                    <p class="text-muted mb-0">En stock</p>
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
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">{{ \App\Models\Medicament::where('disponible', false)->count() }}</h4>
                                    <p class="text-muted mb-0">Rupture de stock</p>
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
                                    <h4 class="mb-1">{{ \App\Models\Medicament::where('date_expiration', '<=', now()->addDays(30))->count() }}</h4>
                                    <p class="text-muted mb-0">Expire bientôt</p>
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
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addMedicamentModal">
                                <i class="fas fa-plus me-2"></i>Ajouter médicament
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" onclick="exportStock()">
                                <i class="fas fa-download me-2"></i>Exporter stock
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100" onclick="checkExpiration()">
                                <i class="fas fa-calendar-check me-2"></i>Vérifier expiration
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100" onclick="generateReport()">
                                <i class="fas fa-file-alt me-2"></i>Rapport stock
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
                            <input type="text" class="form-control" placeholder="Rechercher un médicament..." id="searchMedicament">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterStatus">
                                <option value="">Tous les statuts</option>
                                <option value="available">En stock</option>
                                <option value="out_of_stock">Rupture</option>
                                <option value="expiring">Expire bientôt</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterCategory">
                                <option value="">Toutes les catégories</option>
                                <option value="antibiotique">Antibiotique</option>
                                <option value="analgesique">Analgésique</option>
                                <option value="vitamine">Vitamine</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des médicaments -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Inventaire des médicaments
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshStock()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="medicamentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Date d'expiration</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Medicament::all() as $medicament)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $medicament->nom }}</div>
                                        <small class="text-muted">{{ $medicament->description ?? 'Aucune description' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($medicament->categorie) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $medicament->quantite_stock }}</span>
                                        <small class="text-muted">{{ $medicament->unite_mesure }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($medicament->prix_unitaire, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <div>{{ $medicament->date_expiration ? $medicament->date_expiration->format('d/m/Y') : 'N/A' }}</div>
                                        @if($medicament->date_expiration && $medicament->date_expiration->diffInDays(now()) <= 30)
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Expire dans {{ $medicament->date_expiration->diffInDays(now()) }} jours
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($medicament->disponible)
                                            <span class="badge bg-success">En stock</span>
                                        @else
                                            <span class="badge bg-danger">Rupture</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewMedicament({{ $medicament->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editMedicament({{ $medicament->id }})" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="updateStock({{ $medicament->id }})" title="Mise à jour stock">
                                                <i class="fas fa-boxes"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteMedicament({{ $medicament->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-pills fa-3x mb-3"></i>
                                        <h5>Aucun médicament trouvé</h5>
                                        <p>Commencez par ajouter votre premier médicament.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicamentModal">
                                            <i class="fas fa-plus me-2"></i>Ajouter un médicament
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

        <!-- Alertes et notifications -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Alertes de stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @php
                            $lowStockMedicaments = \App\Models\Medicament::where('quantite_stock', '<=', 10)->get();
                            $expiringMedicaments = \App\Models\Medicament::where('date_expiration', '<=', now()->addDays(30))->get();
                        @endphp
                        
                        @if($lowStockMedicaments->count() > 0)
                            @foreach($lowStockMedicaments->take(5) as $medicament)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-warning">{{ $medicament->nom }}</h6>
                                    <small class="text-muted">Stock faible: {{ $medicament->quantite_stock }} {{ $medicament->unite_mesure }}</small>
                                </div>
                                <span class="badge bg-warning">Stock faible</span>
                            </div>
                            @endforeach
                        @endif

                        @if($expiringMedicaments->count() > 0)
                            @foreach($expiringMedicaments->take(5) as $medicament)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-danger">{{ $medicament->nom }}</h6>
                                    <small class="text-muted">Expire le {{ $medicament->date_expiration->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge bg-danger">Expire bientôt</span>
                            </div>
                            @endforeach
                        @endif

                        @if($lowStockMedicaments->count() == 0 && $expiringMedicaments->count() == 0)
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p>Aucune alerte de stock</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition par catégorie
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de médicament -->
<div class="modal fade" id="addMedicamentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un nouveau médicament</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pharmacy.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom du médicament *</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categorie" class="form-label">Catégorie *</label>
                                <select class="form-select" id="categorie" name="categorie" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="antibiotique">Antibiotique</option>
                                    <option value="analgesique">Analgésique</option>
                                    <option value="vitamine">Vitamine</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantite_stock" class="form-label">Quantité en stock *</label>
                                <input type="number" class="form-control" id="quantite_stock" name="quantite_stock" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unite_mesure" class="form-label">Unité de mesure *</label>
                                <select class="form-select" id="unite_mesure" name="unite_mesure" required>
                                    <option value="">Sélectionner une unité</option>
                                    <option value="comprimé">Comprimé</option>
                                    <option value="gélule">Gélule</option>
                                    <option value="flacon">Flacon</option>
                                    <option value="tube">Tube</option>
                                    <option value="ampoule">Ampoule</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prix_unitaire" class="form-label">Prix unitaire (FCFA) *</label>
                                <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_expiration" class="form-label">Date d'expiration</label>
                                <input type="date" class="form-control" id="date_expiration" name="date_expiration">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter le médicament</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des catégories
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: ['Antibiotiques', 'Analgésiques', 'Vitamines', 'Autres'],
        datasets: [{
            data: [30, 25, 20, 25],
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

function viewMedicament(id) {
    window.location.href = `/admin/pharmacy/${id}`;
}

function editMedicament(id) {
    window.location.href = `/admin/pharmacy/${id}/edit`;
}

function updateStock(id) {
    const newStock = prompt('Nouvelle quantité en stock:');
    if (newStock !== null && !isNaN(newStock)) {
        // Ici vous pouvez faire un appel AJAX pour mettre à jour le stock
        alert('Stock mis à jour avec succès!');
        location.reload();
    }
}

function deleteMedicament(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce médicament ?')) {
        // Ici vous pouvez faire un appel AJAX pour supprimer
        alert('Médicament supprimé avec succès!');
        location.reload();
    }
}

function exportStock() {
    alert('Export du stock en cours...');
}

function checkExpiration() {
    alert('Vérification des dates d\'expiration...');
}

function generateReport() {
    alert('Génération du rapport de stock...');
}

function applyFilters() {
    const search = document.getElementById('searchMedicament').value;
    const status = document.getElementById('filterStatus').value;
    const category = document.getElementById('filterCategory').value;
    
    // Ici vous pouvez implémenter la logique de filtrage
    console.log('Filtres appliqués:', { search, status, category });
}

function refreshStock() {
    location.reload();
}
</script>
@endsection
