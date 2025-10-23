@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Mes rendez-vous
                    </h5>
                    <p class="text-muted mb-0">Gérez vos rendez-vous médicaux</p>
                </div>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus me-2"></i>Nouveau rendez-vous
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.appointments') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rendez-vous -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($appointments) && $appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Heure</th>
                                        <th>Service</th>
                                        <th>Médecin</th>
                                        <th>Statut</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                        </td>
                                        <td>
                                            @if($appointment->service)
                                                <span class="badge bg-primary">{{ $appointment->service->name }}</span>
                                                <div class="small text-muted">{{ Str::limit($appointment->service->description, 50) }}</div>
                                            @else
                                                <span class="text-muted">Non spécifié</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->doctor)
                                                <div class="d-flex align-items-center">
                                                    @if($appointment->doctor->photo)
                                                        <img src="{{ asset('storage/' . $appointment->doctor->photo) }}" 
                                                             alt="Photo médecin" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user-md text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div>
                                                        <small class="text-muted">{{ $appointment->doctor->grade->name ?? 'Médecin' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'completed' => 'info',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'En attente',
                                                    'confirmed' => 'Confirmé',
                                                    'completed' => 'Terminé',
                                                    'cancelled' => 'Annulé'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($appointment->service)
                                                <div class="fw-bold">{{ number_format($appointment->service->price, 0) }} FCFA</div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('patient.appointments.show', $appointment->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($appointment->status == 'pending')
                                                    <a href="{{ route('patient.appointments.edit', $appointment->id) }}" 
                                                       class="btn btn-outline-warning" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            onclick="cancelAppointment({{ $appointment->id }})"
                                                            title="Annuler">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                                
                                                @if($appointment->status == 'confirmed')
                                                    <button type="button" 
                                                            class="btn btn-outline-success" 
                                                            onclick="confirmArrival({{ $appointment->id }})"
                                                            title="Confirmer arrivée">
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

                        <!-- Pagination -->
                        @if($appointments->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                <!-- Pagination Info -->
                                <div class="pagination-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Affichage de {{ $appointments->firstItem() }} à {{ $appointments->lastItem() }} sur {{ $appointments->total() }} résultats
                                </div>
                                
                                <!-- Pagination Links -->
                                {{ $appointments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <h5>Aucun rendez-vous trouvé</h5>
                            <p class="mb-3">Vous n'avez pas encore de rendez-vous ou aucun ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Prendre un premier rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cancelAppointment(appointmentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // Ici vous pouvez ajouter la logique pour annuler le rendez-vous
        alert('Fonctionnalité d\'annulation en cours de développement');
    }
}

function confirmArrival(appointmentId) {
    if (confirm('Confirmez-vous votre arrivée pour ce rendez-vous ?')) {
        // Ici vous pouvez ajouter la logique pour confirmer l'arrivée
        alert('Fonctionnalité de confirmation d\'arrivée en cours de développement');
    }
}
</script>
@endsection
