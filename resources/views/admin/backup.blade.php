@extends('layouts.admin')

@section('title', 'Sauvegardes - Admin')
@section('page-title', 'Gestion des Sauvegardes')
@section('page-subtitle', 'Sauvegarde et restauration des données')
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
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Actions de sauvegarde -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer une sauvegarde
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Créez une sauvegarde complète de votre base de données et de vos fichiers.
                    </p>
                    
                    <div class="mb-3">
                        <label class="form-label">Type de sauvegarde</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="backup_type" id="backup_full" value="full" checked>
                            <label class="form-check-label" for="backup_full">
                                <strong>Complète</strong>
                                <small class="d-block text-muted">Base de données + fichiers</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="backup_type" id="backup_db" value="database">
                            <label class="form-check-label" for="backup_db">
                                <strong>Base de données uniquement</strong>
                                <small class="d-block text-muted">Données de l'application</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="backup_type" id="backup_files" value="files">
                            <label class="form-check-label" for="backup_files">
                                <strong>Fichiers uniquement</strong>
                                <small class="d-block text-muted">Documents et images</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" onclick="createBackup()">
                            <i class="fas fa-database me-2"></i>Créer la sauvegarde
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Sauvegarde automatique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="auto_backup" checked>
                        <label class="form-check-label" for="auto_backup">
                            Activer la sauvegarde automatique
                        </label>
                    </div>

                    <div class="mb-3">
                        <label for="backup_frequency" class="form-label">Fréquence</label>
                        <select class="form-select" id="backup_frequency">
                            <option value="daily" selected>Quotidienne</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly">Mensuelle</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="backup_time" class="form-label">Heure de sauvegarde</label>
                        <input type="time" class="form-control" id="backup_time" value="02:00">
                    </div>

                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Prochaine sauvegarde: Demain à 02:00
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des sauvegardes -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique des sauvegardes
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshBackups()">
                        <i class="fas fa-sync-alt me-1"></i>Actualiser
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Fonctionnalité en développement</strong><br>
                        Le système de sauvegarde automatique sera disponible prochainement.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Type</th>
                                    <th>Taille</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Exemple de sauvegarde -->
                                <tr>
                                    <td>
                                        <div>{{ now()->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ now()->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-database me-1"></i>Complète
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">15.4 MB</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Réussie
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadBackup(1)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="restoreBackup(1)" title="Restaurer">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup(1)" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>{{ now()->subDay()->format('d/m/Y') }}</div>
                                        <small class="text-muted">02:00:00</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-database me-1"></i>Base de données
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">8.2 MB</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Réussie
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadBackup(2)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="restoreBackup(2)" title="Restaurer">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup(2)" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>{{ now()->subDays(2)->format('d/m/Y') }}</div>
                                        <small class="text-muted">02:00:00</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-folder me-1"></i>Fichiers
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">6.8 MB</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Réussie
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadBackup(3)" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="restoreBackup(3)" title="Restaurer">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup(3)" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-muted">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Les sauvegardes sont conservées pendant 30 jours. Les sauvegardes plus anciennes sont automatiquement supprimées.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-database text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">3</h4>
                                    <p class="text-muted mb-0">Sauvegardes totales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">100%</h4>
                                    <p class="text-muted mb-0">Taux de réussite</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-hdd text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-1">30.4 MB</h4>
                                    <p class="text-muted mb-0">Espace utilisé</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function createBackup() {
    const backupType = document.querySelector('input[name="backup_type"]:checked').value;
    const types = {
        'full': 'complète',
        'database': 'de la base de données',
        'files': 'des fichiers'
    };
    
    if (confirm(`Créer une sauvegarde ${types[backupType]} ?\n\nCela peut prendre quelques minutes.`)) {
        alert('Fonctionnalité en cours de développement.\n\nLa sauvegarde sera créée automatiquement.');
    }
}

function downloadBackup(id) {
    alert(`Téléchargement de la sauvegarde #${id}...\n\nFonctionnalité en cours de développement.`);
}

function restoreBackup(id) {
    if (confirm(`ATTENTION: Restaurer cette sauvegarde remplacera toutes les données actuelles !\n\nÊtes-vous sûr de vouloir continuer ?`)) {
        alert(`Restauration de la sauvegarde #${id}...\n\nFonctionnalité en cours de développement.`);
    }
}

function deleteBackup(id) {
    if (confirm('Supprimer cette sauvegarde ?\n\nCette action est irréversible.')) {
        alert(`Suppression de la sauvegarde #${id}...\n\nFonctionnalité en cours de développement.`);
    }
}

function refreshBackups() {
    location.reload();
}

// Auto-save settings
document.getElementById('auto_backup').addEventListener('change', function() {
    if (this.checked) {
        alert('Sauvegarde automatique activée');
    } else {
        alert('Sauvegarde automatique désactivée');
    }
});
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

