@extends('layouts.doctor')

@section('title', 'Paramètres - Docteur')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'Configuration de votre compte et préférences')
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

    <div class="row">
        <!-- Paramètres de notification -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Paramètres de notification
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                       {{ $settings['email_notifications'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Notifications par email
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" 
                                       {{ $settings['sms_notifications'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_notifications">
                                    Notifications par SMS
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="appointment_reminders" name="appointment_reminders" 
                                       {{ $settings['appointment_reminders'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="appointment_reminders">
                                    Rappels de rendez-vous
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent_notifications" name="urgent_notifications" 
                                       {{ $settings['urgent_notifications'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="urgent_notifications">
                                    Notifications urgentes
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Paramètres d'affichage -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-palette me-2"></i>Paramètres d'affichage
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="theme" class="form-label">Thème</label>
                            <select class="form-select" id="theme" name="theme">
                                <option value="light" {{ ($settings['theme'] ?? 'light') == 'light' ? 'selected' : '' }}>Clair</option>
                                <option value="dark" {{ ($settings['theme'] ?? 'light') == 'dark' ? 'selected' : '' }}>Sombre</option>
                                <option value="auto" {{ ($settings['theme'] ?? 'light') == 'auto' ? 'selected' : '' }}>Automatique</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="language" class="form-label">Langue</label>
                            <select class="form-select" id="language" name="language">
                                <option value="fr" {{ ($settings['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ ($settings['language'] ?? 'fr') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="timezone" class="form-label">Fuseau horaire</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="Africa/Dakar" {{ ($settings['timezone'] ?? 'Africa/Dakar') == 'Africa/Dakar' ? 'selected' : '' }}>Dakar (GMT+0)</option>
                                <option value="Africa/Abidjan" {{ ($settings['timezone'] ?? 'Africa/Dakar') == 'Africa/Abidjan' ? 'selected' : '' }}>Abidjan (GMT+0)</option>
                                <option value="Africa/Bamako" {{ ($settings['timezone'] ?? 'Africa/Dakar') == 'Africa/Bamako' ? 'selected' : '' }}>Bamako (GMT+0)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_format" class="form-label">Format de date</label>
                            <select class="form-select" id="date_format" name="date_format">
                                <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ ($settings['date_format'] ?? 'd/m/Y') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Paramètres de sécurité -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Paramètres de sécurité
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth" 
                                       {{ $settings['two_factor_auth'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="two_factor_auth">
                                    Authentification à deux facteurs
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="session_timeout" name="session_timeout" 
                                       {{ $settings['session_timeout'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="session_timeout">
                                    Déconnexion automatique après inactivité
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="session_duration" class="form-label">Durée de session (minutes)</label>
                            <input type="number" class="form-control" id="session_duration" name="session_duration" 
                                   value="{{ $settings['session_duration'] ?? 120 }}" min="30" max="480">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="login_notifications" name="login_notifications" 
                                       {{ $settings['login_notifications'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="login_notifications">
                                    Notifications de connexion
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Paramètres de disponibilité -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Paramètres de disponibilité
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="working_hours_start" class="form-label">Heure de début de travail</label>
                            <input type="time" class="form-control" id="working_hours_start" name="working_hours_start" 
                                   value="{{ $settings['working_hours_start'] ?? '08:00' }}">
                        </div>

                        <div class="mb-3">
                            <label for="working_hours_end" class="form-label">Heure de fin de travail</label>
                            <input type="time" class="form-control" id="working_hours_end" name="working_hours_end" 
                                   value="{{ $settings['working_hours_end'] ?? '18:00' }}">
                        </div>

                        <div class="mb-3">
                            <label for="appointment_duration" class="form-label">Durée par défaut des rendez-vous (minutes)</label>
                            <input type="number" class="form-control" id="appointment_duration" name="appointment_duration" 
                                   value="{{ $settings['appointment_duration'] ?? 30 }}" min="15" max="120" step="15">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="weekend_appointments" name="weekend_appointments" 
                                       {{ $settings['weekend_appointments'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="weekend_appointments">
                                    Accepter les rendez-vous le weekend
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Sauvegarder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions avancées -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-download me-2"></i>Export de données
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Téléchargez vos données personnelles et professionnelles.</p>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="exportData('profile')">
                            <i class="fas fa-user me-2"></i>Exporter le profil
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="exportData('appointments')">
                            <i class="fas fa-calendar me-2"></i>Exporter les rendez-vous
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="exportData('patients')">
                            <i class="fas fa-users me-2"></i>Exporter les patients
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions dangereuses -->
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Actions dangereuses
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Ces actions sont irréversibles. Procédez avec prudence.</p>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                            <i class="fas fa-trash me-2"></i>Vider le cache
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteAccount()">
                            <i class="fas fa-user-times me-2"></i>Supprimer le compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header h5 {
    color: #495057;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.btn {
    border-radius: 8px;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}

.border-danger .card-header {
    background-color: #dc3545;
    border-bottom: 1px solid #dc3545;
}
</style>
@endpush

@push('scripts')
<script>
function exportData(type) {
    if (confirm(`Êtes-vous sûr de vouloir exporter vos données ${type} ?`)) {
        // Ici vous pouvez ajouter la logique d'export
        alert('Fonctionnalité d\'export en cours de développement');
    }
}

function clearCache() {
    if (confirm('Êtes-vous sûr de vouloir vider le cache ? Cette action peut temporairement ralentir l\'application.')) {
        // Ici vous pouvez ajouter la logique de nettoyage du cache
        alert('Cache vidé avec succès');
    }
}

function deleteAccount() {
    if (confirm('ÊTES-VOUS ABSOLUMENT SÛR DE VOULOIR SUPPRIMER VOTRE COMPTE ? Cette action est irréversible et toutes vos données seront définitivement perdues.')) {
        if (confirm('Dernière confirmation : Voulez-vous vraiment supprimer votre compte ?')) {
            // Ici vous pouvez ajouter la logique de suppression du compte
            alert('Fonctionnalité de suppression de compte en cours de développement');
        }
    }
}
</script>
@endpush
