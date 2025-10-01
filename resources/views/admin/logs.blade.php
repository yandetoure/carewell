@extends('layouts.admin')

@section('title', 'Logs Système - Admin')
@section('page-title', 'Logs Système')
@section('page-subtitle', 'Consultation des journaux d\'activité')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Journaux d'activité
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearLogs()">
                            <i class="fas fa-trash me-1"></i>Effacer les logs
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Page en développement</strong><br>
                        La consultation des logs système sera disponible prochainement.
                        En attendant, vous pouvez consulter le fichier <code>storage/logs/laravel.log</code> directement.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="logLevel" class="form-label">Niveau de log</label>
                            <select class="form-select" id="logLevel">
                                <option value="all">Tous les niveaux</option>
                                <option value="emergency">Emergency</option>
                                <option value="alert">Alert</option>
                                <option value="critical">Critical</option>
                                <option value="error">Error</option>
                                <option value="warning">Warning</option>
                                <option value="notice">Notice</option>
                                <option value="info">Info</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="logDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="logDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="searchLog" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="searchLog" placeholder="Rechercher dans les logs...">
                        </div>
                    </div>

                    <div class="bg-dark text-light p-3 rounded" style="max-height: 600px; overflow-y: auto; font-family: monospace; font-size: 0.875rem;">
                        <div class="log-entry">
                            <span class="text-success">[{{ now()->format('Y-m-d H:i:s') }}]</span> 
                            <span class="text-primary">INFO</span>: 
                            Système de logs en cours de développement
                        </div>
                        <div class="log-entry mt-2">
                            <span class="text-success">[{{ now()->subMinutes(5)->format('Y-m-d H:i:s') }}]</span> 
                            <span class="text-info">DEBUG</span>: 
                            Consultation de la page des logs par {{ auth()->user()->first_name ?? 'Admin' }}
                        </div>
                        <div class="log-entry mt-2">
                            <span class="text-success">[{{ now()->subMinutes(10)->format('Y-m-d H:i:s') }}]</span> 
                            <span class="text-primary">INFO</span>: 
                            Connexion réussie de l'utilisateur {{ auth()->user()->email ?? 'admin@example.com' }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Les logs sont conservés pendant 30 jours par défaut.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des logs -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">0</h4>
                            <p class="text-muted mb-0">Erreurs</p>
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
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">0</h4>
                            <p class="text-muted mb-0">Avertissements</p>
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
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">3</h4>
                            <p class="text-muted mb-0">Informations</p>
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
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ number_format(filesize(storage_path('logs/laravel.log')) / 1024, 2) }} KB</h4>
                            <p class="text-muted mb-0">Taille du fichier</p>
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
function clearLogs() {
    if (confirm('Êtes-vous sûr de vouloir effacer tous les logs ? Cette action est irréversible.')) {
        alert('Fonctionnalité en cours de développement');
    }
}

function refreshLogs() {
    location.reload();
}

// Auto-scroll to bottom
const logContainer = document.querySelector('.bg-dark');
if (logContainer) {
    logContainer.scrollTop = logContainer.scrollHeight;
}
</script>
@endsection

