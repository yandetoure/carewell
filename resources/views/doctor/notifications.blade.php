@extends('layouts.doctor')

@section('title', 'Notifications - Docteur')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Gestion des notifications du service')
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

    <!-- Statistiques des notifications -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $notifications->count() }}</h4>
                            <p class="text-muted mb-0">Total notifications</p>
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
                            <h4 class="mb-1">{{ $notifications->where('is_read', true)->count() }}</h4>
                            <p class="text-muted mb-0">Lues</p>
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
                            <h4 class="mb-1">{{ $notifications->where('is_read', false)->count() }}</h4>
                            <p class="text-muted mb-0">Non lues</p>
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
                            <h4 class="mb-1">{{ $notifications->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                            <i class="fas fa-bell me-2"></i>Notifications du service
                        </h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-success" onclick="markAllAsRead()">
                                <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
                            </button>
                            <a href="{{ route('doctor.messages') }}" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Titre</th>
                                        <th>Message</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                        <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-bell text-warning me-2"></i>
                                                    {{ $notification->title ?? 'Titre non spécifié' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-envelope text-info me-2"></i>
                                                    {{ Str::limit($notification->message ?? 'Message non disponible', 100) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $notification->type == 'urgent' ? 'danger' : ($notification->type == 'info' ? 'info' : 'primary') }}">
                                                    {{ ucfirst($notification->type ?? 'info') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $notification->is_read ? 'success' : 'warning' }}">
                                                    {{ $notification->is_read ? 'Lu' : 'Non lu' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="showNotificationDetails('{{ $notification->title ?? 'Titre non spécifié' }}', '{{ $notification->message ?? 'Message non disponible' }}', '{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}')" 
                                                            title="Voir la notification">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if(!$notification->is_read)
                                                        <button type="button" class="btn btn-outline-success" 
                                                                onclick="markAsRead({{ $notification->id }})" 
                                                                title="Marquer comme lu">
                                                            <i class="fas fa-check"></i>
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
                            <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune notification</h5>
                            <p class="text-muted">Aucune notification n'a été trouvée pour ce service.</p>
                            <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>Retour au tableau de bord
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
                        <i class="fas fa-chart-line me-2"></i>Résumé des notifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-bell text-primary me-2"></i><strong>Total notifications:</strong> {{ $notifications->count() }}</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i><strong>Notifications lues:</strong> {{ $notifications->where('is_read', true)->count() }}</li>
                                <li><i class="fas fa-exclamation-circle text-warning me-2"></i><strong>Non lues:</strong> {{ $notifications->where('is_read', false)->count() }}</li>
                                <li><i class="fas fa-calendar-check text-info me-2"></i><strong>Cette semaine:</strong> {{ $notifications->where('created_at', '>=', now()->subDays(7))->count() }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">💡 Bonnes pratiques</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-lightbulb text-warning me-2"></i>Consultez régulièrement vos notifications</li>
                                <li><i class="fas fa-file-medical text-info me-2"></i>Marquez les notifications importantes comme lues</li>
                                <li><i class="fas fa-clock text-primary me-2"></i>Réagissez rapidement aux notifications urgentes</li>
                                <li><i class="fas fa-notes-medical text-success me-2"></i>Gardez un historique des notifications importantes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails de la notification -->
<div class="modal fade" id="notificationDetailsModal" tabindex="-1" aria-labelledby="notificationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationDetailsModalLabel">
                    <i class="fas fa-bell me-2"></i>Détails de la notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notificationDetailsContent">
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
</style>
@endpush

@push('scripts')
<script>
function showNotificationDetails(title, message, date) {
    document.getElementById('notificationDetailsContent').innerHTML = `
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Date :</h6>
                    <p class="mb-0">${date}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-primary">Titre :</h6>
                    <p class="mb-0">${title}</p>
                </div>
                <div>
                    <h6 class="text-primary">Message :</h6>
                    <div class="notification-content" style="white-space: pre-wrap; line-height: 1.6; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                        ${message}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('notificationDetailsModal'));
    modal.show();
}

function markAsRead(notificationId) {
    // Ici vous pouvez ajouter une requête AJAX pour marquer la notification comme lue
    // Pour l'instant, on recharge la page
    if (confirm('Marquer cette notification comme lue ?')) {
        location.reload();
    }
}

function markAllAsRead() {
    // Ici vous pouvez ajouter une requête AJAX pour marquer toutes les notifications comme lues
    // Pour l'instant, on recharge la page
    if (confirm('Marquer toutes les notifications comme lues ?')) {
        location.reload();
    }
}
</script>
@endpush
