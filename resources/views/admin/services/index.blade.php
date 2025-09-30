@extends('layouts.admin')

@section('title', 'Gestion des Services - Admin')
@section('page-title', 'Gestion des Services')
@section('page-subtitle', 'Gérer les services médicaux disponibles')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Services médicaux
                    </h5>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouveau Service
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filtres et recherche -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchService" placeholder="Rechercher un service...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterPrice">
                                <option value="">Tous les prix</option>
                                <option value="0-50000">0 - 50 000 FCFA</option>
                                <option value="50000-100000">50 000 - 100 000 FCFA</option>
                                <option value="100000-200000">100 000 - 200 000 FCFA</option>
                                <option value="200000+">200 000+ FCFA</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="sortBy">
                                <option value="name">Trier par nom</option>
                                <option value="price_low">Prix croissant</option>
                                <option value="price_high">Prix décroissant</option>
                                <option value="date">Date de création</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                <i class="fas fa-undo me-1"></i>Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ $services->total() }}</h4>
                                    <small>Total des services</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ number_format($services->avg('price'), 0, ',', ' ') }} FCFA</h4>
                                    <small>Prix moyen</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ number_format($services->min('price'), 0, ',', ' ') }} FCFA</h4>
                                    <small>Prix minimum</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-1">{{ number_format($services->max('price'), 0, ',', ' ') }} FCFA</h4>
                                    <small>Prix maximum</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="servicesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom du service</th>
                                    <th>Description</th>
                                    <th>Prix</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr>
                                    <td>
                                        @if($service->photo)
                                            <img src="{{ asset('storage/' . $service->photo) }}" 
                                                 alt="{{ $service->name }}" 
                                                 class="rounded" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-stethoscope text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $service->name }}</div>
                                        <small class="text-muted">{{ $service->category ?? 'Service médical' }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $service->description }}">
                                            {{ Str::limit($service->description, 80) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">{{ number_format($service->price, 0, ',', ' ') }} FCFA</div>
                                        <small class="text-muted">Prix fixe</small>
                                    </td>
                                    <td>
                                        <div>{{ $service->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $service->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="viewService({{ $service->id }})" 
                                                    title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.services.edit', $service) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info" 
                                                    onclick="duplicateService({{ $service->id }})" 
                                                    title="Dupliquer">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteService({{ $service->id }})" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-stethoscope fa-3x mb-3"></i>
                                        <h5>Aucun service trouvé</h5>
                                        <p>Commencez par créer votre premier service médical.</p>
                                        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer un service
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($services->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $services->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Service Modal -->
<div class="modal fade" id="viewServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="serviceDetails">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Service Modal -->
<div class="modal fade" id="duplicateServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dupliquer le service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="duplicateServiceForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="duplicate_name" class="form-label">Nom du nouveau service *</label>
                        <input type="text" class="form-control" id="duplicate_name" name="name" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Le nouveau service sera créé avec les mêmes détails que l'original.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Dupliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Recherche et filtres
document.getElementById('searchService').addEventListener('input', filterServices);
document.getElementById('filterPrice').addEventListener('change', filterServices);
document.getElementById('sortBy').addEventListener('change', filterServices);

function filterServices() {
    const searchTerm = document.getElementById('searchService').value.toLowerCase();
    const priceFilter = document.getElementById('filterPrice').value;
    const sortBy = document.getElementById('sortBy').value;
    
    const rows = document.querySelectorAll('#servicesTable tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const description = row.cells[2].textContent.toLowerCase();
        const priceText = row.cells[3].textContent;
        
        const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
        const matchesPrice = !priceFilter || checkPriceRange(priceText, priceFilter);
        
        row.style.display = matchesSearch && matchesPrice ? '' : 'none';
    });
}

function checkPriceRange(priceText, range) {
    const price = parseFloat(priceText.replace(/[^\d.,]/g, '').replace(/\s/g, '').replace(',', '.'));
    
    switch(range) {
        case '0-50000': return price >= 0 && price <= 50000;
        case '50000-100000': return price > 50000 && price <= 100000;
        case '100000-200000': return price > 100000 && price <= 200000;
        case '200000+': return price > 200000;
        default: return true;
    }
}

function resetFilters() {
    document.getElementById('searchService').value = '';
    document.getElementById('filterPrice').value = '';
    document.getElementById('sortBy').value = 'name';
    filterServices();
}

function viewService(serviceId) {
    const modal = new bootstrap.Modal(document.getElementById('viewServiceModal'));
    const detailsContainer = document.getElementById('serviceDetails');
    
    // Show loading
    detailsContainer.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Load service details via AJAX
    fetch(`/admin/services/${serviceId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        return response.text();
    })
    .then(html => {
        detailsContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Erreur:', error);
        detailsContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreur lors du chargement des détails du service</strong><br>
                <small>${error.message}</small>
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-danger" onclick="location.reload()">
                        <i class="fas fa-refresh me-1"></i>Recharger la page
                    </button>
                </div>
            </div>
        `;
    });
}

function duplicateService(serviceId) {
    const modal = new bootstrap.Modal(document.getElementById('duplicateServiceModal'));
    const form = document.getElementById('duplicateServiceForm');
    form.action = `/admin/services/${serviceId}/duplicate`;
    modal.show();
}

function deleteService(serviceId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce service ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/services/${serviceId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh every 30 seconds
setInterval(() => {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
</script>

<style>
.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.bg-primary, .bg-success, .bg-info, .bg-warning {
    background-color: var(--bs-primary) !important;
}

.bg-success {
    background-color: var(--bs-success) !important;
}

.bg-info {
    background-color: var(--bs-info) !important;
}

.bg-warning {
    background-color: var(--bs-warning) !important;
}
</style>
@endsection
