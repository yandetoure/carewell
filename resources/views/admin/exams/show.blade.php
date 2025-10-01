@extends('layouts.admin')

@section('title', 'Détails de l\'Examen - Admin')
@section('page-title', 'Détails de l\'examen')
@section('page-subtitle', 'Informations complètes sur l\'examen médical')
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
            <!-- Exam Icon Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px;">
                        <i class="fas fa-vials fa-5x text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ $exam->name }}</h5>
                    <p class="text-muted">Examen médical</p>
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
                        <div class="col-6">
                            <div class="h4 text-primary mb-1">{{ $exam->results()->count() }}</div>
                            <small class="text-muted">Résultats</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success mb-1">{{ $exam->created_at->diffInDays(now()) }}</div>
                            <small class="text-muted">Jours actif</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="h4 text-warning mb-1">{{ $exam->medicalFileExam()->count() }}</div>
                        <small class="text-muted">Dossiers médicaux</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Exam Details -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations de l'examen
                    </h6>
                    <span class="badge bg-primary">{{ $exam->id }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-tag me-1"></i>Nom :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="fw-bold text-primary">{{ $exam->name }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-money-bill me-1"></i>Prix :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-coins me-1"></i>{{ number_format($exam->price ?? 0, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                    
                    @if($exam->service)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-hospital me-1"></i>Service :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-info">
                                <i class="fas fa-stethoscope me-1"></i>{{ $exam->service->name }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-align-left me-1"></i>Description :</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $exam->description }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-calendar me-1"></i>Créé le :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="text-muted">
                                {{ $exam->created_at->format('d/m/Y à H:i') }}
                                <small>({{ $exam->created_at->diffForHumans() }})</small>
                            </span>
                        </div>
                    </div>
                    
                    @if($exam->updated_at != $exam->created_at)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="fas fa-edit me-1"></i>Modifié le :</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="text-muted">
                                {{ $exam->updated_at->format('d/m/Y à H:i') }}
                                <small>({{ $exam->updated_at->diffForHumans() }})</small>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Exam Timeline -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique de l'examen
                    </h6>
                    <span class="badge bg-secondary">{{ $exam->results()->count() }} résultats</span>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-plus-circle me-1"></i>Examen créé
                                        </h6>
                                        <p class="text-muted mb-0">{{ $exam->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    <small class="text-muted">{{ $exam->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($exam->updated_at != $exam->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-edit me-1"></i>Dernière modification
                                        </h6>
                                        <p class="text-muted mb-0">{{ $exam->updated_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    <small class="text-muted">{{ $exam->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($exam->results()->count() > 0)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-flask me-1"></i>Examen utilisé
                                        </h6>
                                        <p class="text-muted mb-0">{{ $exam->results()->count() }} résultats enregistrés</p>
                                    </div>
                                    <span class="badge bg-success">{{ $exam->results()->count() }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item">
                            <div class="timeline-marker bg-light border"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 text-muted">
                                            <i class="fas fa-inbox me-1"></i>Aucun résultat
                                        </h6>
                                        <p class="text-muted mb-0">Cet examen n'a pas encore de résultats</p>
                                    </div>
                                    <span class="badge bg-light text-muted">0</span>
                                </div>
                            </div>
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
                                <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Modifier l'examen
                                </a>
                                <button class="btn btn-danger" onclick="deleteExam({{ $exam->id }})">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
                                <a href="{{ route('admin.exams') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-end">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Examen #{{ $exam->id }} • 
                                    {{ $exam->created_at->diffInDays(now()) }} jours actif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Cette action est irréversible.
                </div>
                <p>Êtes-vous sûr de vouloir supprimer l'examen <strong>"{{ $exam->name }}"</strong> ?</p>
                <p class="text-muted">Tous les résultats associés à cet examen seront également supprimés.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteExam(examId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #007bff, #28a745);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
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

.bg-primary.bg-opacity-10 {
    background-color: rgba(13, 110, 253, 0.1) !important;
}
</style>
@endsection

