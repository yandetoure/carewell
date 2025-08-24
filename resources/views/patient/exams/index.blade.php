@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Mes examens
                    </h5>
                    <p class="text-muted mb-0">Consultez vos examens médicaux et leurs résultats</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.exams') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="exam_type" class="form-label">Type d'examen</label>
                            <select class="form-select" id="exam_type" name="exam_type">
                                <option value="">Tous les types</option>
                                <option value="blood" {{ request('exam_type') == 'blood' ? 'selected' : '' }}>Analyse sanguine</option>
                                <option value="urine" {{ request('exam_type') == 'urine' ? 'selected' : '' }}>Analyse d'urine</option>
                                <option value="imaging" {{ request('exam_type') == 'imaging' ? 'selected' : '' }}>Imagerie</option>
                                <option value="cardiology" {{ request('exam_type') == 'cardiology' ? 'selected' : '' }}>Cardiologie</option>
                                <option value="other" {{ request('exam_type') == 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
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

    <!-- Liste des examens -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($exams) && $exams->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type d'examen</th>
                                        <th>Description</th>
                                        <th>Médecin</th>
                                        <th>Date de prescription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exams as $exam)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $exam->exam_type ?? 'Examen' }}</div>
                                            @if($exam->exam_category)
                                                <small class="text-muted">{{ $exam->exam_category }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ Str::limit($exam->description ?? 'Description non disponible', 50) }}</div>
                                            @if($exam->instructions)
                                                <small class="text-muted">{{ Str::limit($exam->instructions, 80) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($exam->doctor)
                                                <div class="d-flex align-items-center">
                                                    @if($exam->doctor->photo)
                                                        <img src="{{ asset('storage/' . $exam->doctor->photo) }}" 
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
                                                        <div class="fw-bold">Dr. {{ $exam->doctor->first_name }} {{ $exam->doctor->last_name }}</div>
                                                        <small class="text-muted">{{ $exam->doctor->grade->name ?? 'Médecin' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Non spécifié</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $exam->created_at ? $exam->created_at->format('d/m/Y') : 'N/A' }}</div>
                                            @if($exam->scheduled_date)
                                                <small class="text-muted">Programmé le {{ \Carbon\Carbon::parse($exam->scheduled_date)->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'in_progress' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'En attente',
                                                    'in_progress' => 'En cours',
                                                    'completed' => 'Terminé',
                                                    'cancelled' => 'Annulé'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$exam->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$exam->status] ?? ucfirst($exam->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('exams.show', $exam->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($exam->status == 'completed' && $exam->result)
                                                    <a href="{{ route('results.show', $exam->result->id) }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Voir résultats">
                                                        <i class="fas fa-clipboard-list"></i>
                                                    </a>
                                                @endif
                                                
                                                @if($exam->status == 'pending')
                                                    <button type="button" 
                                                            class="btn btn-outline-warning" 
                                                            onclick="rescheduleExam({{ $exam->id }})"
                                                            title="Reprogrammer">
                                                        <i class="fas fa-calendar-alt"></i>
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
                        @if($exams->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $exams->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-stethoscope fa-3x mb-3"></i>
                            <h5>Aucun examen trouvé</h5>
                            <p class="mb-3">Vous n'avez pas encore d'examens ou aucun ne correspond à vos critères de recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations importantes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Information :</strong> 
                Les résultats de vos examens seront disponibles dans les 24-48h suivant la réalisation de l'examen. 
                Vous recevrez une notification par email dès qu'ils seront disponibles.
            </div>
        </div>
    </div>

    <!-- Instructions générales -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Conseils avant un examen
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Respectez les consignes de préparation
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Apportez votre carte vitale et ordonnance
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Arrivez 15 minutes à l'avance
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Informez le personnel de vos allergies
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Questions fréquentes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="examFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Combien de temps pour les résultats ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#examFAQ">
                                <div class="accordion-body">
                                    Les résultats sont généralement disponibles dans les 24-48h pour les analyses courantes, 
                                    et jusqu'à une semaine pour les examens plus complexes.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Puis-je annuler un examen ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#examFAQ">
                                <div class="accordion-body">
                                    Oui, vous pouvez annuler un examen jusqu'à 24h avant sa réalisation. 
                                    Contactez le secrétariat pour toute modification.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function rescheduleExam(examId) {
    if (confirm('Souhaitez-vous reprogrammer cet examen ?')) {
        // Ici vous pouvez ajouter la logique pour reprogrammer l'examen
        alert('Fonctionnalité de reprogrammation en cours de développement');
    }
}
</script>
@endsection
