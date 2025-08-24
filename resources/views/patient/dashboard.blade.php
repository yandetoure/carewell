@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user text-primary me-2"></i>
                        Vue d'ensemble
                    </h1>
                    <p class="text-muted mb-0">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                    </a>
                    <a href="{{ route('patient.medical-file') }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-medical me-2"></i>Mon Dossier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $upcomingAppointments ?? 0 }}</h4>
                            <p class="text-muted mb-0">RDV à venir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-pills text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $activePrescriptions ?? 0 }}</h4>
                            <p class="text-muted mb-0">Prescriptions actives</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $pendingExams ?? 0 }}</h4>
                            <p class="text-muted mb-0">Examens en attente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-file-medical text-white"></i>
                        </div>
                        <div class="div class="ms-3">
                            <h4 class="mb-1">{{ $totalMedicalFiles ?? 0 }}</h4>
                            <p class="text-muted mb-0">Dossiers médicaux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Upcoming Appointments -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Mes prochains rendez-vous
                        </h5>
                        <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary btn-sm">
                            Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($upcomingAppointmentsList) && $upcomingAppointmentsList->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Heure</th>
                                        <th>Service</th>
                                        <th>Médecin</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAppointmentsList as $appointment)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                        </td>
                                        <td>
                                            @if($appointment->service)
                                                <span class="badge bg-primary">{{ $appointment->service->name }}</span>
                                                <div class="small text-muted">{{ number_format($appointment->service->price, 2) }} €</div>
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
                                            <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('patient.appointments.show', $appointment->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($appointment->status == 'pending')
                                                    <button class="btn btn-outline-success" 
                                                            onclick="confirmAppointment({{ $appointment->id }})" 
                                                            title="Confirmer">
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
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p class="mb-0">Aucun rendez-vous à venir</p>
                            <small>Prenez rendez-vous pour consulter un professionnel de santé</small>
                            <div class="mt-3">
                                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Prendre un RDV
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Health Summary -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Prendre RDV
                        </a>
                        <a href="{{ route('patient.medical-file') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-medical me-2"></i>Consulter dossier
                        </a>
                        <a href="{{ route('patient.prescriptions') }}" class="btn btn-outline-info">
                            <i class="fas fa-pills me-2"></i>Mes prescriptions
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-warning">
                            <i class="fas fa-phone me-2"></i>Contacter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Health Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-heartbeat me-2"></i>
                        Résumé santé
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="fw-bold text-primary">{{ Auth::user()->height ?? 'N/A' }} cm</div>
                                <small class="text-muted">Taille</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="fw-bold text-primary">{{ Auth::user()->weight ?? 'N/A' }} kg</div>
                            <small class="text-muted">Poids</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="fw-bold text-primary">{{ Auth::user()->blood_type ?? 'N/A' }}</div>
                            <small class="text-muted">Groupe sanguin</small>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold text-primary">{{ Auth::user()->age ?? 'N/A' }} ans</div>
                            <small class="text-muted">Âge</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Activité récente
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentActivity) && $recentActivity->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity as $activity)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="activity-icon me-2">
                                        @if($activity->type == 'appointment')
                                            <i class="fas fa-calendar-check text-primary"></i>
                                        @elseif($activity->type == 'prescription')
                                            <i class="fas fa-pills text-success"></i>
                                        @elseif($activity->type == 'exam')
                                            <i class="fas fa-stethoscope text-info"></i>
                                        @else
                                            <i class="fas fa-circle text-secondary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small">{{ $activity->description }}</div>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p class="mb-0">Aucune activité récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Health Tips & Articles -->
    <div class="row g-4 mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Conseils santé personnalisés
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-heartbeat fa-3x text-danger mb-3"></i>
                                    <h6>Surveillez votre tension</h6>
                                    <p class="small text-muted">Vérifiez régulièrement votre tension artérielle, surtout si vous avez des antécédents familiaux.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-walking fa-3x text-success mb-3"></i>
                                    <h6>Activité physique</h6>
                                    <p class="small text-muted">30 minutes d'activité physique modérée par jour améliorent significativement votre santé.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        Articles recommandés
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recommendedArticles) && $recommendedArticles->count() > 0)
                        @foreach($recommendedArticles as $article)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-1">{{ $article->title }}</h6>
                            <p class="small text-muted mb-2">{{ Str::limit($article->content, 80) }}</p>
                            <a href="{{ route('patient.articles.show', $article->id) }}" class="btn btn-outline-primary btn-sm">
                                Lire plus
                            </a>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-newspaper fa-2x mb-2"></i>
                            <p class="mb-0">Aucun article recommandé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.activity-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary { background-color: var(--primary-color) !important; }
.bg-success { background-color: var(--success-color) !important; }
.bg-warning { background-color: var(--warning-color) !important; }
.bg-info { background-color: var(--info-color) !important; }
</style>

<script>
function confirmAppointment(appointmentId) {
    if (confirm('Voulez-vous confirmer ce rendez-vous ?')) {
        // Ici vous pouvez ajouter la logique pour confirmer le rendez-vous
        alert('Rendez-vous confirmé !');
    }
}
</script>
@endsection
