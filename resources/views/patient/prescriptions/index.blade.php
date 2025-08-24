@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-pills me-2"></i>
                        Mes prescriptions
                    </h5>
                    <p class="text-muted mb-0">Consultez vos prescriptions médicales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.prescriptions') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
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

    <!-- Liste des prescriptions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($prescriptions) && $prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Médicament</th>
                                        <th>Dosage</th>
                                        <th>Médecin</th>
                                        <th>Date de prescription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $prescription->medication_name ?? 'Médicament' }}</div>
                                            @if($prescription->medication_type)
                                                <small class="text-muted">{{ $prescription->medication_type }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $prescription->dosage ?? 'Non spécifié' }}</div>
                                            @if($prescription->frequency)
                                                <small class="text-muted">{{ $prescription->frequency }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($prescription->doctor)
                                                <div class="d-flex align-items-center">
                                                    @if($prescription->doctor->photo)
                                                        <img src="{{ asset('storage/' . $prescription->doctor->photo) }}" 
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
                                                        <div class="fw-bold">Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</div>
                                                        <small class="text-muted">{{ $prescription->doctor->grade->name ?? 'Médecin' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Non spécifié</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $prescription->created_at ? $prescription->created_at->format('d/m/Y') : 'N/A' }}</div>
                                            @if($prescription->expiry_date)
                                                <small class="text-muted">Expire le {{ \Carbon\Carbon::parse($prescription->expiry_date)->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'completed' => 'info',
                                                    'expired' => 'danger',
                                                    'suspended' => 'warning'
                                                ];
                                                $statusLabels = [
                                                    'active' => 'Actif',
                                                    'completed' => 'Terminé',
                                                    'expired' => 'Expiré',
                                                    'suspended' => 'Suspendu'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$prescription->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$prescription->status] ?? ucfirst($prescription->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('prescriptions.show', $prescription->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($prescription->status == 'active')
                                                    <button type="button" 
                                                            class="btn btn-outline-success" 
                                                            onclick="markAsCompleted({{ $prescription->id }})"
                                                            title="Marquer comme terminé">
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
                        @if($prescriptions->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $prescriptions->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-pills fa-3x mb-3"></i>
                            <h5>Aucune prescription trouvée</h5>
                            <p class="mb-3">Vous n'avez pas encore de prescriptions ou aucune ne correspond à vos critères de recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations importantes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important :</strong> 
                Respectez toujours la posologie prescrite par votre médecin. 
                En cas de doute ou d'effets secondaires, contactez immédiatement votre médecin traitant.
            </div>
        </div>
    </div>
</div>

<script>
function markAsCompleted(prescriptionId) {
    if (confirm('Êtes-vous sûr de vouloir marquer cette prescription comme terminée ?')) {
        // Ici vous pouvez ajouter la logique pour marquer la prescription comme terminée
        alert('Fonctionnalité en cours de développement');
    }
}
</script>
@endsection
