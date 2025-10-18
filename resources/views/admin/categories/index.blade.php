@extends('layouts.admin')

@section('title', 'Gestion des Catégories - Admin')
@section('page-title', 'Catégories de Services')
@section('page-subtitle', 'Gérer les catégories de services médicaux')
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
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-tags text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ count($categories) }}</h4>
                            <p class="text-muted mb-0">total catégories</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ collect($categories)->sum('count') }}</h4>
                            <p class="text-muted mb-0">services actifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-chart-pie text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format(collect($categories)->avg('count'), 1) }}</h4>
                            <p class="text-muted mb-0">moyenne par catégorie</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des catégories -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Catégories disponibles
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-2"></i>nouvelle catégorie
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($categories as $key => $category)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 category-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="category-icon me-3">
                                            <i class="{{ $category['icon'] }} fa-2x text-{{ $category['color'] }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-2">{{ $category['name'] }}</h6>
                                            <p class="card-text text-muted small mb-3">{{ $category['description'] }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-{{ $category['color'] }}">
                                                    {{ $category['count'] }} service{{ $category['count'] > 1 ? 's' : '' }}
                                                </span>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" title="voir les services">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary" title="modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($category['count'] == 0)
                                                    <button class="btn btn-outline-danger" title="supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
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

    <!-- Graphique de répartition -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Répartition des services par catégorie
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoriesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>gestion des catégories
                        </h6>
                        <ul class="mb-0 small">
                            <li>les catégories organisent les services médicaux</li>
                            <li>chaque service doit être assigné à une catégorie</li>
                            <li>vous pouvez modifier les descriptions et icônes</li>
                            <li>une catégorie ne peut être supprimée si elle contient des services</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de catégorie -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">nom de la catégorie *</label>
                        <input type="text" class="form-control" id="categoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">description</label>
                        <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoryIcon" class="form-label">icône</label>
                        <select class="form-select" id="categoryIcon">
                            <option value="fas fa-heartbeat">heartbeat</option>
                            <option value="fas fa-shield-alt">shield</option>
                            <option value="fas fa-apple-alt">apple</option>
                            <option value="fas fa-dumbbell">dumbbell</option>
                            <option value="fas fa-brain">brain</option>
                            <option value="fas fa-heart">heart</option>
                            <option value="fas fa-child">child</option>
                            <option value="fas fa-female">female</option>
                            <option value="fas fa-bone">bone</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoryColor" class="form-label">couleur</label>
                        <select class="form-select" id="categoryColor">
                            <option value="primary">bleu</option>
                            <option value="success">vert</option>
                            <option value="warning">orange</option>
                            <option value="info">cyan</option>
                            <option value="danger">rouge</option>
                            <option value="secondary">gris</option>
                            <option value="dark">noir</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">annuler</button>
                    <button type="submit" class="btn btn-primary">créer la catégorie</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique
    const categoriesData = @json($categories);
    
    // Préparer les données pour Chart.js
    const labels = Object.values(categoriesData).map(cat => cat.name);
    const data = Object.values(categoriesData).map(cat => cat.count);
    const colors = Object.values(categoriesData).map(cat => {
        const colorMap = {
            'primary': '#0d6efd',
            'success': '#198754',
            'warning': '#ffc107',
            'info': '#0dcaf0',
            'danger': '#dc3545',
            'secondary': '#6c757d',
            'dark': '#212529',
            'pink': '#e91e63'
        };
        return colorMap[cat.color] || '#6c757d';
    });

    // Créer le graphique en secteurs
    const ctx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' services (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.category-card {
    transition: transform 0.2s ease-in-out;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.category-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
}

.stat-card {
    transition: transform 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-2px);
}
</style>
@endpush
