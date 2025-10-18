@extends('layouts.doctor')

@section('title', 'Résultats du Service - Docteur')
@section('page-title', 'Résultats du Service')
@section('page-subtitle', 'Gestion des résultats d\'examens du service')
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

    <!-- Statistiques des résultats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalResults }}</h4>
                            <p class="text-muted mb-0">Total résultats</p>
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
                            <h4 class="mb-1">{{ $normalResults }}</h4>
                            <p class="text-muted mb-0">Normaux</p>
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
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $abnormalResults }}</h4>
                            <p class="text-muted mb-0">Anormaux</p>
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
                            <h4 class="mb-1">{{ $recentResults }}</h4>
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
                            <i class="fas fa-clipboard-list me-2"></i>Résultats du service
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.exams') }}" class="btn btn-outline-primary">
                                <i class="fas fa-flask me-2"></i>Examens
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

    <!-- Liste des résultats -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($results->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Type d'examen</th>
                                        <th>Résultat</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                        <tr class="{{ $result->status == 'normal' ? 'table-success' : ($result->status == 'abnormal' ? 'table-warning' : 'table-info') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-success me-2"></i>
                                                    <div>
                                                        <div class="fw-bold">Patient non spécifié</div>
                                                        <small class="text-muted">Informations non disponibles</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-md text-primary me-2"></i>
                                                    {{ $result->doctor->first_name ?? 'N/A' }} {{ $result->doctor->last_name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-flask text-warning me-2"></i>
                                                    {{ $result->exam->name ?? 'Examen non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clipboard-list text-info me-2"></i>
                                                    {{ Str::limit($result->name ?? 'Résultat non disponible', 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $result->status == 'normal' ? 'success' : ($result->status == 'abnormal' ? 'danger' : 'warning') }}">
                                                    {{ $result->status == 'normal' ? 'Normal' : ($result->status == 'abnormal' ? 'Anormal' : 'En attente') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="viewResultDetails({{ $result->id }})" 
                                                            title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="editResult({{ $result->id }})" 
                                                            title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($result->status == 'abnormal')
                                                        <button type="button" class="btn btn-outline-warning" 
                                                                onclick="alert('Résultat anormal détecté - Consultation recommandée')" 
                                                                title="Alerte">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                        </button>
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
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun résultat</h5>
                            <p class="text-muted">Aucun résultat d'examen n'a été trouvé pour ce service.</p>
                            <a href="{{ route('doctor.exams') }}" class="btn btn-primary">
                                <i class="fas fa-flask me-2"></i>Voir les examens
                            </a>
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des résultats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clipboard-list text-primary me-2"></i><strong>Total résultats:</strong> {{ $totalResults }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Résultats normaux:</strong> {{ $normalResults }}</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>Résultats anormaux:</strong> {{ $abnormalResults }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $recentResults }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Analysez attentivement tous les résultats</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Comparez avec les résultats précédents</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Communiquez rapidement les résultats anormaux</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Expliquez clairement les résultats au patient</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails du résultat -->
<div class="modal fade" id="viewResultModal" tabindex="-1" aria-labelledby="viewResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewResultModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Détails du résultat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="viewResultContent">
                    <!-- Le contenu sera inséré ici par JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier le résultat -->
<div class="modal fade" id="editResultModal" tabindex="-1" aria-labelledby="editResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editResultModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le résultat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editResultForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="editResult" class="form-label">Résultat de l'examen</label>
                        <textarea class="form-control" id="editResult" name="name" rows="4" placeholder="Décrivez le résultat de l'examen..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Statut du résultat</label>
                        <select class="form-select" id="editStatus" name="status">
                            <option value="normal">Normal</option>
                            <option value="abnormal">Anormal</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Notes ou recommandations..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPhotos" class="form-label">Nouvelles photos (optionnel)</label>
                        <input type="file" class="form-control" id="editPhotos" name="photos[]" multiple accept="image/*">
                        <div class="form-text">Vous pouvez sélectionner plusieurs photos (JPG, PNG, GIF)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editPdfs" class="form-label">Nouveaux documents PDF (optionnel)</label>
                        <input type="file" class="form-control" id="editPdfs" name="pdfs[]" multiple accept=".pdf">
                        <div class="form-text">Vous pouvez sélectionner plusieurs fichiers PDF</div>
                    </div>
                    
                    <!-- Aperçu des fichiers existants -->
                    <div id="existingFilesPreview" class="mb-3" style="display: none;">
                        <h6>Fichiers existants :</h6>
                        <div id="existingFilesList"></div>
                    </div>
                    
                    <!-- Aperçu des nouveaux fichiers -->
                    <div id="newFilesPreview" class="mb-3" style="display: none;">
                        <h6>Nouveaux fichiers sélectionnés :</h6>
                        <div id="newFilesList"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="updateResult()">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </button>
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

.table-info {
    background-color: rgba(23, 162, 184, 0.1);
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
</style>
@endpush

@push('scripts')
<script>
let currentResultId = null;

// Fonction pour afficher les détails du résultat
function viewResultDetails(resultId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/results/${resultId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status && data.data) {
            displayResultDetails(data.data);
        } else {
            alert('Erreur lors du chargement des détails: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des détails');
    });
}

// Fonction pour afficher les détails du résultat dans le modal
function displayResultDetails(result) {
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
                        <p><strong>Examen :</strong><br>${result.exam ? result.exam.name : 'N/A'}</p>
                        <p><strong>Date :</strong><br>${new Date(result.created_at).toLocaleDateString('fr-FR')}</p>
                        <p><strong>Heure :</strong><br>${new Date(result.created_at).toLocaleTimeString('fr-FR')}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('viewResultContent').innerHTML = content;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('viewResultModal'));
    modal.show();
}

// Fonction pour éditer le résultat
function editResult(resultId) {
    currentResultId = resultId;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/results/${resultId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status && data.data) {
            populateEditForm(data.data);
        } else {
            alert('Erreur lors du chargement des données: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du chargement des données');
    });
}

// Fonction pour remplir le formulaire d'édition
function populateEditForm(result) {
    document.getElementById('editResult').value = result.name || '';
    document.getElementById('editStatus').value = result.status || 'normal';
    document.getElementById('editDescription').value = result.description || '';
    
    // Afficher les fichiers existants
    if (result.files && result.files.length > 0) {
        const existingFilesPreview = document.getElementById('existingFilesPreview');
        const existingFilesList = document.getElementById('existingFilesList');
        
        existingFilesPreview.style.display = 'block';
        existingFilesList.innerHTML = '';
        
        result.files.forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center mb-2 p-2 border rounded';
            
            if (file.type === 'photo') {
                fileItem.innerHTML = `
                    <i class="fas fa-image text-primary me-2"></i>
                    <span class="me-2">${file.name}</span>
                    <small class="text-muted me-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-auto" onclick="removeExistingFile('${file.path}')">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            } else if (file.type === 'pdf') {
                fileItem.innerHTML = `
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <span class="me-2">${file.name}</span>
                    <small class="text-muted me-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-auto" onclick="removeExistingFile('${file.path}')">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            }
            
            existingFilesList.appendChild(fileItem);
        });
    }
    
    // Afficher le modal d'édition
    const modal = new bootstrap.Modal(document.getElementById('editResultModal'));
    modal.show();
}

// Fonction pour mettre à jour le résultat
function updateResult() {
    const name = document.getElementById('editResult').value;
    const status = document.getElementById('editStatus').value;
    const description = document.getElementById('editDescription').value;
    const photos = document.getElementById('editPhotos').files;
    const pdfs = document.getElementById('editPdfs').files;
    
    if (!name.trim()) {
        alert('Veuillez saisir le résultat de l\'examen');
        return;
    }
    
    // Créer FormData pour envoyer les fichiers
    const formData = new FormData();
    formData.append('name', name);
    formData.append('status', status);
    formData.append('description', description);
    
    // Ajouter les nouvelles photos
    for (let i = 0; i < photos.length; i++) {
        formData.append('photos[]', photos[i]);
    }
    
    // Ajouter les nouveaux PDFs
    for (let i = 0; i < pdfs.length; i++) {
        formData.append('pdfs[]', pdfs[i]);
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/doctor/results/${currentResultId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            alert('Résultat mis à jour avec succès');
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editResultModal'));
            modal.hide();
            // Recharger la page
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

// Fonction pour afficher l'aperçu des nouveaux fichiers
function previewNewFiles() {
    const photos = document.getElementById('editPhotos').files;
    const pdfs = document.getElementById('editPdfs').files;
    const newFilesPreview = document.getElementById('newFilesPreview');
    const newFilesList = document.getElementById('newFilesList');
    
    if (photos.length > 0 || pdfs.length > 0) {
        newFilesPreview.style.display = 'block';
        newFilesList.innerHTML = '';
        
        // Afficher les nouvelles photos
        for (let i = 0; i < photos.length; i++) {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center mb-2';
            fileItem.innerHTML = `
                <i class="fas fa-image text-primary me-2"></i>
                <span class="me-2">${photos[i].name}</span>
                <small class="text-muted">(${(photos[i].size / 1024 / 1024).toFixed(2)} MB)</small>
            `;
            newFilesList.appendChild(fileItem);
        }
        
        // Afficher les nouveaux PDFs
        for (let i = 0; i < pdfs.length; i++) {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center mb-2';
            fileItem.innerHTML = `
                <i class="fas fa-file-pdf text-danger me-2"></i>
                <span class="me-2">${pdfs[i].name}</span>
                <small class="text-muted">(${(pdfs[i].size / 1024 / 1024).toFixed(2)} MB)</small>
            `;
            newFilesList.appendChild(fileItem);
        }
    } else {
        newFilesPreview.style.display = 'none';
    }
}

// Ajouter les événements pour l'aperçu des fichiers
document.addEventListener('DOMContentLoaded', function() {
    const editPhotosInput = document.getElementById('editPhotos');
    const editPdfsInput = document.getElementById('editPdfs');
    
    if (editPhotosInput) {
        editPhotosInput.addEventListener('change', previewNewFiles);
    }
    
    if (editPdfsInput) {
        editPdfsInput.addEventListener('change', previewNewFiles);
    }
});
</script>
@endpush
