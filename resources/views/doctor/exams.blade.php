@extends('layouts.doctor')

@section('title', 'Examens du Service - Docteur')
@section('page-title', 'Examens du Service')
@section('page-subtitle', 'Gestion des examens du service')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques des examens -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-flask text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $exams->count() }}</h4>
                            <p class="text-muted mb-0">Total examens</p>
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
                            <h4 class="mb-1">{{ $exams->where('is_done', true)->count() }}</h4>
                            <p class="text-muted mb-0">Terminés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $exams->where('is_done', false)->count() }}</h4>
                            <p class="text-muted mb-0">En cours</p>
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
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $exams->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                            <p class="text-muted mb-0">Cette semaine</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-flask me-2"></i>Examens du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.results') }}" class="btn btn-outline-primary">
                                <i class="fas fa-clipboard-list me-2"></i>Résultats
                            </a>
                            <a href="{{ route('doctor.prescriptions') }}" class="btn btn-outline-success">
                                <i class="fas fa-pills me-2"></i>Prescriptions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des examens -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($exams->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Type d'examen</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exams as $exam)
                                        <tr class="{{ $exam->is_done ? 'table-success' : 'table-warning' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($exam->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">{{ $exam->medicalFile->user->first_name ?? 'N/A' }} {{ $exam->medicalFile->user->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $exam->medicalFile->user->phone_number ?? 'Tél. non renseigné' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $exam->doctor->first_name ?? 'N/A' }} {{ $exam->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-flask text-warning me-2"></i>
                                                    {{ $exam->exam->name ?? 'Examen non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $exam->is_done ? 'success' : 'warning' }}">
                                                    {{ $exam->is_done ? 'Terminé' : 'En cours' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="showExamDetails({{ $exam->id }}, '{{ $exam->exam->name }}', '{{ $exam->exam->description }}', '{{ $exam->medicalFile->user->first_name }} {{ $exam->medicalFile->user->last_name }}', '{{ $exam->doctor->first_name }} {{ $exam->doctor->last_name }}', '{{ $exam->type }}', '{{ $exam->instructions }}', {{ $exam->is_done ? 'true' : 'false' }}, '{{ \Carbon\Carbon::parse($exam->created_at)->format('d/m/Y H:i') }}')" 
                                                            title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('doctor.patients.show', $exam->medicalFile->user) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir le patient">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                    @if($exam->exam->service_id == $doctor->service_id)
                                                        @if(!$exam->is_done)
                                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                                    onclick="markExamAsDone({{ $exam->id }})" 
                                                                    title="Marquer comme terminé">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                                    onclick="markExamAsInProgress({{ $exam->id }})" 
                                                                    title="Marquer comme en cours">
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">Autre service</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-flask fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun examen</h5>
                            <p class="text-muted">Aucun examen n'a été trouvé pour ce service.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé et conseils -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Résumé des examens
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-flask text-primary me-2"></i><strong>Total examens:</strong> {{ $exams->count() }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Examens terminés:</strong> {{ $exams->where('is_done', true)->count() }}</li>
                                <li><i class="fas fa-clock text-warning me-2"></i><strong>En cours:</strong> {{ $exams->where('is_done', false)->count() }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $exams->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Planifiez les examens selon les priorités</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Documentez tous les résultats</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Respectez les délais d'examen</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Communiquez les résultats rapidement</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails de l'examen -->
<div class="modal fade" id="examDetailsModal" tabindex="-1" aria-labelledby="examDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="examDetailsModalLabel">
                    <i class="fas fa-flask me-2"></i>Détails de l'examen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="examDetailsContent">
                    <!-- Le contenu sera inséré ici par JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-info" id="viewResultBtn" style="display: none;" onclick="viewExamResult()">
                    <i class="fas fa-eye me-2"></i>Voir le résultat complet
                </button>
                <button type="button" class="btn btn-primary" id="addResultBtn" style="display: none;" onclick="showAddResultForm()">
                    <i class="fas fa-plus me-2"></i>Ajouter le résultat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter le résultat -->
<div class="modal fade" id="addResultModal" tabindex="-1" aria-labelledby="addResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResultModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Ajouter le résultat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addResultForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="result" class="form-label">Résultat de l'examen</label>
                        <textarea class="form-control" id="result" name="result" rows="4" placeholder="Décrivez le résultat de l'examen..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="photos" class="form-label">Photos (optionnel)</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                        <div class="form-text">Vous pouvez sélectionner plusieurs photos (JPG, PNG, GIF)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pdfs" class="form-label">Documents PDF (optionnel)</label>
                        <input type="file" class="form-control" id="pdfs" name="pdfs[]" multiple accept=".pdf">
                        <div class="form-text">Vous pouvez sélectionner plusieurs fichiers PDF</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut du résultat</label>
                        <select class="form-select" id="status" name="status">
                            <option value="normal">Normal</option>
                            <option value="abnormal">Anormal</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notes ou recommandations..."></textarea>
                    </div>
                    
                    <!-- Aperçu des fichiers sélectionnés -->
                    <div id="filePreview" class="mb-3" style="display: none;">
                        <h6>Fichiers sélectionnés :</h6>
                        <div id="fileList"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="saveResult()">
                    <i class="fas fa-save me-2"></i>Enregistrer le résultat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les résultats -->
<div class="modal fade" id="viewResultModal" tabindex="-1" aria-labelledby="viewResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewResultModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Résultats de l'examen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="resultContent">
                    <!-- Le contenu sera inséré ici par JavaScript -->
                </div>
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

.table-success {
    background-color: rgba(40, 167, 69, 0.1);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.card-header h5 {
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Styles pour l'aperçu des fichiers */
#filePreview {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
}

#fileList .d-flex {
    background-color: white;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 0.5rem;
}

/* Styles pour les inputs de fichiers */
.form-control[type="file"] {
    padding: 0.375rem 0.75rem;
}

.form-control[type="file"]::-webkit-file-upload-button {
    background-color: #0d6efd;
    color: white;
    border: none;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
    margin-right: 0.5rem;
    cursor: pointer;
}

.form-control[type="file"]::-webkit-file-upload-button:hover {
    background-color: #0b5ed7;
}

/* Styles pour l'affichage des résultats */
.result-image {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
}

.result-card {
    transition: transform 0.2s ease-in-out;
}

.result-card:hover {
    transform: translateY(-2px);
}

.file-icon {
    font-size: 2rem;
}
</style>
@endpush

@push('scripts')
<script>
let currentExamId = null;

// Fonction pour afficher les détails de l'examen
function showExamDetails(examId, examName, examDescription, patientName, doctorName, examType, instructions, isDone, createdAt) {
    currentExamId = examId;
    
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Informations générales
                </h6>
                <div class="mb-3">
                    <strong>Nom de l'examen:</strong><br>
                    <span class="text-muted">${examName}</span>
                </div>
                <div class="mb-3">
                    <strong>Description:</strong><br>
                    <span class="text-muted">${examDescription || 'Aucune description'}</span>
                </div>
                <div class="mb-3">
                    <strong>Type:</strong><br>
                    <span class="badge bg-info">${examType}</span>
                </div>
                <div class="mb-3">
                    <strong>Statut:</strong><br>
                    <span class="badge bg-${isDone ? 'success' : 'warning'}">${isDone ? 'Terminé' : 'En cours'}</span>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-users me-2"></i>Personnes impliquées
                </h6>
                <div class="mb-3">
                    <strong>Patient:</strong><br>
                    <span class="text-muted">${patientName}</span>
                </div>
                <div class="mb-3">
                    <strong>Médecin:</strong><br>
                    <span class="text-muted">${doctorName}</span>
                </div>
                <div class="mb-3">
                    <strong>Date de création:</strong><br>
                    <span class="text-muted">${createdAt}</span>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-clipboard-list me-2"></i>Instructions
                </h6>
                <div class="alert alert-light">
                    ${instructions || 'Aucune instruction spécifique'}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-clipboard-check me-2"></i>Résultats de l'examen
                </h6>
                <div id="examResultsContent">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="text-muted mt-2">Chargement des résultats...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('examDetailsContent').innerHTML = content;
    
    // Gérer l'affichage des boutons selon l'état de l'examen
    const addResultBtn = document.getElementById('addResultBtn');
    const viewResultBtn = document.getElementById('viewResultBtn');
    
    if (isDone) {
        // Charger les résultats directement dans les détails
        loadExamResults(examId, addResultBtn, viewResultBtn);
    } else {
        // Pour les examens en cours, afficher le bouton "Voir le résultat"
        addResultBtn.style.display = 'none';
        viewResultBtn.style.display = 'inline-block';
        viewResultBtn.innerHTML = '<i class="fas fa-eye me-2"></i>Voir le résultat';
        
        // Afficher un bouton dans les détails pour les examens en cours
        document.getElementById('examResultsContent').innerHTML = `
            <div class="text-center py-3">
                <button type="button" class="btn btn-primary" onclick="viewExamResult()">
                    <i class="fas fa-eye me-2"></i>Voir le résultat
                </button>
            </div>
        `;
    }
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('examDetailsModal'));
    modal.show();
}

// Fonction pour charger et afficher les résultats dans les détails
function loadExamResults(examId, addResultBtn, viewResultBtn) {
    console.log('Loading exam results for examId:', examId);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/exams/${examId}/results`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        const resultsContent = document.getElementById('examResultsContent');
        
        if (data.success && data.result) {
            // Il y a un résultat, afficher le bouton "Voir le résultat"
            console.log('Result found, showing view button');
            resultsContent.innerHTML = `
                <div class="text-center py-3">
                    <button type="button" class="btn btn-primary" onclick="viewExamResult()">
                        <i class="fas fa-eye me-2"></i>Voir le résultat
                    </button>
                </div>
            `;
            addResultBtn.style.display = 'none';
            viewResultBtn.style.display = 'inline-block';
            viewResultBtn.innerHTML = '<i class="fas fa-eye me-2"></i>Voir le résultat';
        } else {
            // Pas de résultat, afficher le bouton "Ajouter le résultat"
            console.log('No result found, showing add button');
            resultsContent.innerHTML = `
                <div class="text-center py-3">
                    <button type="button" class="btn btn-primary" onclick="showAddResultForm()">
                        <i class="fas fa-plus me-2"></i>Ajouter le résultat
                    </button>
                </div>
            `;
            addResultBtn.style.display = 'inline-block';
            viewResultBtn.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // En cas d'erreur, afficher un message d'erreur
        document.getElementById('examResultsContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>Erreur lors du chargement des résultats.
            </div>
        `;
        addResultBtn.style.display = 'inline-block';
        viewResultBtn.style.display = 'none';
    });
}

// Fonction pour afficher le résultat directement dans les détails
function displayResultInDetails(result) {
    let filesHtml = '';
    
    if (result.files && result.files.length > 0) {
        filesHtml = '<div class="mt-3"><h6 class="text-secondary">Fichiers joints :</h6><div class="row">';
        
        result.files.forEach(file => {
            if (file.type === 'photo') {
                filesHtml += `
                    <div class="col-md-3 mb-2">
                        <div class="card">
                            <img src="/storage/${file.path}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="${file.name}">
                            <div class="card-body p-2">
                                <h6 class="card-title small">${file.name}</h6>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (file.type === 'pdf') {
                filesHtml += `
                    <div class="col-md-3 mb-2">
                        <div class="card">
                            <div class="card-body text-center p-2">
                                <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                <h6 class="card-title small">${file.name}</h6>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                <br>
                                <a href="/storage/${file.path}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        
        filesHtml += '</div></div>';
    }
    
    return `
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-clipboard-list me-2"></i>Résultat
                        </h6>
                        <div class="alert alert-light mb-3">
                            ${result.name}
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="text-primary mb-0 me-3">Statut:</h6>
                            <span class="badge bg-${result.status === 'normal' ? 'success' : (result.status === 'abnormal' ? 'danger' : 'warning')} fs-6">
                                ${result.status === 'normal' ? 'Normal' : (result.status === 'abnormal' ? 'Anormal' : 'En attente')}
                            </span>
                        </div>
                        
                        ${result.description ? `
                            <h6 class="text-primary mb-2">Notes:</h6>
                            <div class="alert alert-info">
                                ${result.description}
                            </div>
                        ` : ''}
                        
                        ${filesHtml}
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-user-md me-2"></i>Informations
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-2"><strong>Médecin :</strong><br>${result.doctor ? result.doctor.first_name + ' ' + result.doctor.last_name : 'N/A'}</p>
                                <p class="mb-2"><strong>Date :</strong><br>${new Date(result.created_at).toLocaleDateString('fr-FR')}</p>
                                <p class="mb-0"><strong>Heure :</strong><br>${new Date(result.created_at).toLocaleTimeString('fr-FR')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Fonction pour vérifier s'il y a un résultat pour l'examen (gardée pour compatibilité)
function checkExamResult(examId, addResultBtn, viewResultBtn) {
    console.log('Checking exam result for examId:', examId);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/exams/${examId}/results`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success && data.result) {
            // Il y a un résultat, afficher le bouton "Voir le résultat"
            console.log('Result found, showing view button');
            viewResultBtn.style.display = 'inline-block';
            addResultBtn.style.display = 'none';
        } else {
            // Pas de résultat, afficher le bouton "Ajouter le résultat"
            console.log('No result found, showing add button');
            addResultBtn.style.display = 'inline-block';
            viewResultBtn.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // En cas d'erreur, afficher le bouton "Ajouter le résultat"
        console.log('Error occurred, showing add button');
        addResultBtn.style.display = 'inline-block';
        viewResultBtn.style.display = 'none';
    });
}

// Fonction pour afficher le résultat de l'examen
function viewExamResult() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/exams/${currentExamId}/results`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.result) {
            displayResult(data.result);
        } else {
            // Afficher un modal avec un message informatif au lieu d'une alerte
            displayNoResultModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        displayNoResultModal();
    });
}

// Fonction pour afficher un modal quand il n'y a pas de résultat
function displayNoResultModal() {
    const content = `
        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Aucun résultat disponible</h5>
            <p class="text-muted">Aucun résultat n'a encore été ajouté pour cet examen.</p>
            <div class="mt-4">
                <button type="button" class="btn btn-primary" onclick="showAddResultForm()">
                    <i class="fas fa-plus me-2"></i>Ajouter le résultat
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('resultContent').innerHTML = content;
    
    // Fermer le modal des détails
    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('examDetailsModal'));
    detailsModal.hide();
    
    // Afficher le modal des résultats
    const resultModal = new bootstrap.Modal(document.getElementById('viewResultModal'));
    resultModal.show();
}

// Fonction pour afficher le contenu du résultat
function displayResult(result) {
    let filesHtml = '';
    
    if (result.files && result.files.length > 0) {
        filesHtml = '<div class="mt-4"><h6>Fichiers joints :</h6><div class="row">';
        
        result.files.forEach(file => {
            if (file.type === 'photo') {
                filesHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="/storage/${file.path}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${file.name}">
                            <div class="card-body">
                                <h6 class="card-title">${file.name}</h6>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (file.type === 'pdf') {
                filesHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <h6 class="card-title">${file.name}</h6>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                <br>
                                <a href="/storage/${file.path}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        
        filesHtml += '</div></div>';
    }
    
    const content = `
        <div class="row">
            <div class="col-md-8">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-clipboard-list me-2"></i>Résultat de l'examen
                </h6>
                <div class="alert alert-light">
                    ${result.name}
                </div>
                
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Statut
                </h6>
                <span class="badge bg-${result.status === 'normal' ? 'success' : (result.status === 'abnormal' ? 'danger' : 'warning')} fs-6">
                    ${result.status === 'normal' ? 'Normal' : (result.status === 'abnormal' ? 'Anormal' : 'En attente')}
                </span>
                
                ${result.description ? `
                    <h6 class="text-primary mb-3 mt-4">
                        <i class="fas fa-sticky-note me-2"></i>Notes
                    </h6>
                    <div class="alert alert-info">
                        ${result.description}
                    </div>
                ` : ''}
                
                ${filesHtml}
            </div>
            <div class="col-md-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-user-md me-2"></i>Informations
                </h6>
                <div class="card">
                    <div class="card-body">
                        <p><strong>Médecin :</strong><br>${result.doctor ? result.doctor.first_name + ' ' + result.doctor.last_name : 'N/A'}</p>
                        <p><strong>Date :</strong><br>${new Date(result.created_at).toLocaleDateString('fr-FR')}</p>
                        <p><strong>Heure :</strong><br>${new Date(result.created_at).toLocaleTimeString('fr-FR')}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('resultContent').innerHTML = content;
    
    // Fermer le modal des détails
    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('examDetailsModal'));
    detailsModal.hide();
    
    // Afficher le modal des résultats
    const resultModal = new bootstrap.Modal(document.getElementById('viewResultModal'));
    resultModal.show();
}

// Fonction pour afficher le formulaire d'ajout de résultat
function showAddResultForm() {
    // Fermer le modal des détails
    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('examDetailsModal'));
    detailsModal.hide();
    
    // Afficher le modal d'ajout de résultat
    const addResultModal = new bootstrap.Modal(document.getElementById('addResultModal'));
    addResultModal.show();
}

// Fonction pour sauvegarder le résultat
function saveResult() {
    const result = document.getElementById('result').value;
    const status = document.getElementById('status').value;
    const notes = document.getElementById('notes').value;
    const photos = document.getElementById('photos').files;
    const pdfs = document.getElementById('pdfs').files;
    
    if (!result.trim()) {
        alert('Veuillez saisir le résultat de l\'examen');
        return;
    }
    
    // Créer FormData pour envoyer les fichiers
    const formData = new FormData();
    formData.append('result', result);
    formData.append('status', status);
    formData.append('notes', notes);
    
    // Ajouter les photos
    for (let i = 0; i < photos.length; i++) {
        formData.append('photos[]', photos[i]);
    }
    
    // Ajouter les PDFs
    for (let i = 0; i < pdfs.length; i++) {
        formData.append('pdfs[]', pdfs[i]);
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/exams/${currentExamId}/results`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Résultat enregistré avec succès');
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addResultModal'));
            modal.hide();
            // Recharger la page
            location.reload();
        } else {
            alert('Erreur lors de l\'enregistrement: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'enregistrement');
    });
}

// Fonction pour afficher l'aperçu des fichiers
function previewFiles() {
    const photos = document.getElementById('photos').files;
    const pdfs = document.getElementById('pdfs').files;
    const filePreview = document.getElementById('filePreview');
    const fileList = document.getElementById('fileList');
    
    if (photos.length > 0 || pdfs.length > 0) {
        filePreview.style.display = 'block';
        fileList.innerHTML = '';
        
        // Afficher les photos
        for (let i = 0; i < photos.length; i++) {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center mb-2';
            fileItem.innerHTML = `
                <i class="fas fa-image text-primary me-2"></i>
                <span class="me-2">${photos[i].name}</span>
                <small class="text-muted">(${(photos[i].size / 1024 / 1024).toFixed(2)} MB)</small>
            `;
            fileList.appendChild(fileItem);
        }
        
        // Afficher les PDFs
        for (let i = 0; i < pdfs.length; i++) {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center mb-2';
            fileItem.innerHTML = `
                <i class="fas fa-file-pdf text-danger me-2"></i>
                <span class="me-2">${pdfs[i].name}</span>
                <small class="text-muted">(${(pdfs[i].size / 1024 / 1024).toFixed(2)} MB)</small>
            `;
            fileList.appendChild(fileItem);
        }
    } else {
        filePreview.style.display = 'none';
    }
}

// Fonctions pour les actions des examens
function markExamAsDone(examId) {
    if (confirm('Marquer cet examen comme terminé ?')) {
        updateExamStatus(examId, true);
    }
}

function markExamAsInProgress(examId) {
    if (confirm('Marquer cet examen comme en cours ?')) {
        updateExamStatus(examId, false);
    }
}

function updateExamStatus(examId, isDone) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/exams/${examId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: JSON.stringify({
            is_done: isDone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}

// Ajouter les événements pour l'aperçu des fichiers
document.addEventListener('DOMContentLoaded', function() {
    const photosInput = document.getElementById('photos');
    const pdfsInput = document.getElementById('pdfs');
    
    if (photosInput) {
        photosInput.addEventListener('change', previewFiles);
    }
    
    if (pdfsInput) {
        pdfsInput.addEventListener('change', previewFiles);
    }
});
</script>
@endpush
