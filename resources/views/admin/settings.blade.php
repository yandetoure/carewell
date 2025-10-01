@extends('layouts.admin')

@section('title', 'Paramètres - Admin')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'Configuration du système')
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
        <!-- Paramètres généraux -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Paramètres généraux
                    </h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="app_name" class="form-label">Nom de l'application</label>
                                <input type="text" class="form-control" id="app_name" name="app_name" 
                                       value="{{ config('app.name') }}" placeholder="CareWell">
                            </div>
                            <div class="col-md-6">
                                <label for="app_url" class="form-label">URL de l'application</label>
                                <input type="url" class="form-control" id="app_url" name="app_url" 
                                       value="{{ config('app.url') }}" placeholder="https://example.com">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="timezone" class="form-label">Fuseau horaire</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="UTC" {{ config('app.timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Africa/Dakar" {{ config('app.timezone') == 'Africa/Dakar' ? 'selected' : '' }}>Africa/Dakar (GMT)</option>
                                    <option value="Europe/Paris" {{ config('app.timezone') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="locale" class="form-label">Langue</label>
                                <select class="form-select" id="locale" name="locale">
                                    <option value="fr" {{ config('app.locale') == 'fr' ? 'selected' : '' }}>Français</option>
                                    <option value="en" {{ config('app.locale') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="maintenance_mode" class="form-label">Mode maintenance</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                <label class="form-check-label" for="maintenance_mode">
                                    Activer le mode maintenance
                                </label>
                            </div>
                            <small class="text-muted">Le site sera inaccessible pour les utilisateurs non administrateurs</small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" disabled>
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Paramètres de sécurité -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Sécurité
                    </h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="session_lifetime" class="form-label">Durée de session (minutes)</label>
                            <input type="number" class="form-control" id="session_lifetime" name="session_lifetime" 
                                   value="{{ config('session.lifetime') }}" min="5" max="1440">
                            <small class="text-muted">Temps avant déconnexion automatique</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Authentification à deux facteurs</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth">
                                <label class="form-check-label" for="two_factor_auth">
                                    Activer l'authentification à deux facteurs
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Politique de mot de passe</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="password_min_length" name="password_min_length" checked disabled>
                                <label class="form-check-label" for="password_min_length">
                                    Minimum 8 caractères
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="password_require_uppercase" name="password_require_uppercase">
                                <label class="form-check-label" for="password_require_uppercase">
                                    Exiger des majuscules
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="password_require_numbers" name="password_require_numbers">
                                <label class="form-check-label" for="password_require_numbers">
                                    Exiger des chiffres
                                </label>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" disabled>
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Paramètres de notification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Notifications par email</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                <label class="form-check-label" for="email_notifications">
                                    Activer les notifications par email
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Types de notifications</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_new_appointment" name="notify_new_appointment" checked>
                                <label class="form-check-label" for="notify_new_appointment">
                                    Nouveaux rendez-vous
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_new_patient" name="notify_new_patient" checked>
                                <label class="form-check-label" for="notify_new_patient">
                                    Nouveaux patients
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_bed_status" name="notify_bed_status" checked>
                                <label class="form-check-label" for="notify_bed_status">
                                    Changement de statut des lits
                                </label>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" disabled>
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations système -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations système
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Version Laravel:</strong>
                        <span class="float-end">{{ app()->version() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Version PHP:</strong>
                        <span class="float-end">{{ phpversion() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Environnement:</strong>
                        <span class="float-end">
                            <span class="badge bg-{{ app()->environment() === 'production' ? 'success' : 'warning' }}">
                                {{ ucfirst(app()->environment()) }}
                            </span>
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Base de données:</strong>
                        <span class="float-end">{{ config('database.default') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Cache:</strong>
                        <span class="float-end">{{ config('cache.default') }}</span>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="clearCache()">
                            <i class="fas fa-broom me-2"></i>Vider le cache
                        </button>
                        <button class="btn btn-outline-info" onclick="optimizeSystem()">
                            <i class="fas fa-rocket me-2"></i>Optimiser le système
                        </button>
                        <button class="btn btn-outline-success" onclick="createBackup()">
                            <i class="fas fa-database me-2"></i>Créer une sauvegarde
                        </button>
                        <button class="btn btn-outline-warning" onclick="viewLogs()">
                            <i class="fas fa-file-alt me-2"></i>Consulter les logs
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Zone dangereuse
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Ces actions sont irréversibles et peuvent affecter le fonctionnement du système.
                    </p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger" onclick="resetDatabase()">
                            <i class="fas fa-database me-2"></i>Réinitialiser la base
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function clearCache() {
    if (confirm('Voulez-vous vider le cache de l\'application ?')) {
        alert('Fonctionnalité en cours de développement');
    }
}

function optimizeSystem() {
    if (confirm('Voulez-vous optimiser le système ?')) {
        alert('Fonctionnalité en cours de développement');
    }
}

function createBackup() {
    if (confirm('Voulez-vous créer une sauvegarde de la base de données ?')) {
        alert('Fonctionnalité en cours de développement');
    }
}

function viewLogs() {
    window.location.href = '{{ route("admin.logs") }}';
}

function resetDatabase() {
    if (confirm('ATTENTION: Cette action va supprimer toutes les données !\n\nÊtes-vous absolument sûr de vouloir continuer ?')) {
        if (confirm('Dernière confirmation: Toutes les données seront perdues !')) {
            alert('Fonctionnalité désactivée pour des raisons de sécurité');
        }
    }
}

// Notification pour les fonctionnalités en développement
document.querySelectorAll('button[type="submit"]:disabled').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Cette fonctionnalité sera disponible dans une prochaine version.');
    });
});
</script>
@endsection

