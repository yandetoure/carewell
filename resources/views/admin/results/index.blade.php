@extends('layouts.admin')

@section('title', 'Résultats - Admin')
@section('page-title', 'Gestion des Résultats')
@section('page-subtitle', 'Consultation et gestion des résultats d\'examens')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-file-medical text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalResults }}</h4>
                            <p class="text-muted mb-0">Total résultats</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des résultats -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des résultats
                    </h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResultModal">
                        <i class="fas fa-plus me-2"></i>Ajouter un résultat
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Examen</th>
                                    <th>Date</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $result)
                                <tr>
                                    <td><strong>#{{ $result->id }}</strong></td>
                                    <td>{{ $result->name }}</td>
                                    <td>
                                        @if($result->exam)
                                            <span class="badge bg-primary">{{ $result->exam->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $result->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($result->image)
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewImage('{{ asset('storage/' . $result->image) }}')">
                                                <i class="fas fa-image me-1"></i>Voir
                                            </button>
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="viewResult({{ $result->id }})" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-primary" onclick="downloadResult({{ $result->id }})" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun résultat trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de résultat -->
<div class="modal fade" id="addResultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un nouveau résultat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Utilisez l'API pour créer de nouveaux résultats.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal de visualisation d'image -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image du résultat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="resultImage" src="" alt="Résultat" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewImage(imageUrl) {
    document.getElementById('resultImage').src = imageUrl;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

function viewResult(resultId) {
    alert('Voir le résultat #' + resultId + '\n\nFonctionnalité en cours de développement.');
}

function downloadResult(resultId) {
    alert('Télécharger le résultat #' + resultId + '\n\nFonctionnalité en cours de développement.');
}
</script>

<style>
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection

