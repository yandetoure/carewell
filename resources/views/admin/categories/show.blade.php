@extends('layouts.admin')

@section('title', 'Détails de la Catégorie - Admin')
@section('page-title', 'Détails de la catégorie')
@section('page-subtitle', 'Informations complètes sur la catégorie')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <!-- Category Icon -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="category-icon-large mb-3">
                        <i class="{{ $category->icon }} fa-4x text-{{ $category->color }}"></i>
                    </div>
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="text-muted">{{ $category->slug }}</p>
                    <span class="badge bg-{{ $category->color }} {{ $category->is_active ? '' : 'bg-secondary' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="h4 text-primary mb-1">{{ $category->services->count() }}</div>
                            <small class="text-muted">Services associés</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success mb-1">{{ $category->created_at->diffInDays(now()) }}</div>
                            <small class="text-muted">Jours actif</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-info mb-1">{{ $category->sort_order }}</div>
                            <small class="text-muted">Ordre</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Category Details -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations de la catégorie
                    </h6>
                    <span class="badge bg-primary">{{ $category->id }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-tag me-1"></i>Nom :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="fw-bold text-primary">{{ $category->name }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-link me-1"></i>Slug :</strong>
                        </div>
                        <div class="col-sm-9">
                            <code class="bg-light p-2 rounded">{{ $category->slug }}</code>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-palette me-1"></i>Couleur :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $category->color }} px-3 py-2">
                                {{ $category->color }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-icons me-1"></i>Icône :</strong>
                        </div>
                        <div class="col-sm-9">
                            <code class="bg-light p-2 rounded">
                                <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                            </code>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-sort-numeric-down me-1"></i>Ordre d'affichage :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-info">{{ $category->sort_order }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-toggle-{{ $category->is_active ? 'on' : 'off' }} me-1"></i>Statut :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-align-left me-1"></i>Description :</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $category->description ?? 'Aucune description' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-calendar me-1"></i>Créé le :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="text-muted">
                                {{ $category->created_at->format('d/m/Y à H:i') }}
                                <small>({{ $category->created_at->diffForHumans() }})</small>
                            </span>
                        </div>
                    </div>
                    
                    @if($category->updated_at != $category->created_at)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-edit me-1"></i>Modifié le :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="text-muted">
                                {{ $category->updated_at->format('d/m/Y à H:i') }}
                                <small>({{ $category->updated_at->diffForHumans() }})</small>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Services in this category -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Services de cette catégorie
                    </h6>
                    <span class="badge bg-secondary">{{ $category->services->count() }} service{{ $category->services->count() > 1 ? 's' : '' }}</span>
                </div>
                <div class="card-body">
                    @if($category->services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prix</th>
                                        <th>Créé le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->services as $service)
                                    <tr>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ number_format($service->price, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $service->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun service n'est associé à cette catégorie pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Actions disponibles
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Modifier la catégorie
                            </a>
                            <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Catégorie #{{ $category->id }} • 
                                {{ $category->created_at->diffInDays(now()) }} jours actif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.category-icon-large {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
    margin: 0 auto;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection


