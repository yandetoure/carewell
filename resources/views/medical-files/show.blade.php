@extends('layouts.app')

@section('title', 'Dossier Médical')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-3">
            <!-- Sidebar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        Informations Patient
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" 
                             alt="Photo de profil" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    @endif
                    
                    <h6 class="mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                    <p class="text-muted small mb-2">{{ Auth::user()->email }}</p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="fw-bold text-primary">{{ Auth::user()->age ?? 'N/A' }}</div>
                                <small class="text-muted">Âge</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold text-primary">{{ Auth::user()->blood_type ?? 'N/A' }}</div>
                            <small class="text-muted">Groupe sanguin</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Nouveau RDV
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user-edit me-1"></i>Modifier profil
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-phone me-1"></i>Contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- Main Content -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical me-2"></i>
                            Dossier Médical
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                <i class="fas fa-plus me-1"></i>Ajouter une note
                            </button>
                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                                <i class="fas fa-pills me-1"></i>Nouvelle prescription
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Medical History -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-history text-primary me-2"></i>
                            Antécédents médicaux
                        </h6>
                        <div class="card">
                            <div class="card-body">
                                @if(Auth::user()->biographie)
                                    <p class="mb-0">{{ Auth::user()->biographie }}</p>
                                @else
                                    <p class="text-muted mb-0">Aucun antécédent médical renseigné.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Appointments -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Rendez-vous récents
                        </h6>
                        <div class="list-group">
                            @forelse(Auth::user()->appointments()->latest()->take(5)->get() as $appointment)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            @if($appointment->service)
                                                {{ $appointment->service->name }}
                                            @else
                                                Rendez-vous médical
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <p class="mb-1">
                                        <i class="fas fa-clock me-1"></i>{{ $appointment->appointment_time }}
                                        @if($appointment->location)
                                            <i class="fas fa-map-marker-alt ms-3 me-1"></i>{{ $appointment->location }}
                                        @endif
                                    </p>
                                    <small>
                                        <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </small>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                    <p class="mb-0">Aucun rendez-vous trouvé</p>
                                </div>
                            @endforelse
                        </div>
                        @if(Auth::user()->appointments()->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('appointments') }}" class="btn btn-outline-primary btn-sm">
                                    Voir tous les rendez-vous
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Medical Notes -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-sticky-note text-primary me-2"></i>
                            Notes médicales
                        </h6>
                        <div class="row">
                            @forelse(\App\Models\Note::where('user_id', Auth::id())->latest()->take(6)->get() as $note)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $note->title }}</h6>
                                                <small class="text-muted">{{ $note->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="card-text small">{{ Str::limit($note->content, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-user-md me-1"></i>
                                                    {{ $note->doctor_name ?? 'Médecin' }}
                                                </small>
                                                <a href="#" class="btn btn-outline-primary btn-sm">Lire plus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-sticky-note fa-2x mb-2"></i>
                                        <p class="mb-0">Aucune note médicale trouvée</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Prescriptions -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-pills text-primary me-2"></i>
                            Prescriptions récentes
                        </h6>
                        <div class="row">
                            @forelse(\App\Models\Prescription::where('user_id', Auth::id())->latest()->take(4)->get() as $prescription)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $prescription->medication_name }}</h6>
                                                <small class="text-muted">{{ $prescription->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="card-text small">
                                                <strong>Posologie:</strong> {{ $prescription->dosage }}<br>
                                                <strong>Durée:</strong> {{ $prescription->duration }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-user-md me-1"></i>
                                                    {{ $prescription->doctor_name ?? 'Médecin' }}
                                                </small>
                                                <span class="badge bg-{{ $prescription->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($prescription->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-pills fa-2x mb-2"></i>
                                        <p class="mb-0">Aucune prescription trouvée</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une note médicale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('medical-files.addNote') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="note_title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="note_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="note_content" class="form-label">Contenu</label>
                        <textarea class="form-control" id="note_content" name="content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Prescription Modal -->
<div class="modal fade" id="addPrescriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('medical-files.addPrescription') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="medication_name" class="form-label">Médicament</label>
                        <input type="text" class="form-control" id="medication_name" name="medication_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosage" class="form-label">Posologie</label>
                        <input type="text" class="form-control" id="dosage" name="dosage" placeholder="ex: 1 comprimé 3x/jour" required>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Durée du traitement</label>
                        <input type="text" class="form-control" id="duration" name="duration" placeholder="ex: 7 jours" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Prescrire</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
