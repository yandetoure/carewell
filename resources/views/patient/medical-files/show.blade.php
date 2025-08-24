@extends('layouts.patient')

@section('content')
<div class="container-fluid">
    <!-- En-tête du dossier médical -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-2">
                                <i class="fas fa-file-medical me-2"></i>
                                Mon dossier médical
                            </h5>
                            <p class="text-muted mb-0">
                                Dossier de {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-outline-primary" onclick="printMedicalFile()">
                                <i class="fas fa-print me-2"></i>Imprimer
                            </button>
                            <button class="btn btn-outline-secondary" onclick="exportMedicalFile()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Informations personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" 
                                 alt="Photo de profil" 
                                 class="rounded-circle mb-3" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                        <h6>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                        <p class="text-muted mb-0">Patient</p>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="fw-bold text-primary">{{ Auth::user()->age ?? 'N/A' }} ans</div>
                            <small class="text-muted">Âge</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="fw-bold text-primary">{{ Auth::user()->blood_type ?? 'N/A' }}</div>
                            <small class="text-muted">Groupe sanguin</small>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="fw-bold text-primary">{{ Auth::user()->height ?? 'N/A' }} cm</div>
                            <small class="text-muted">Taille</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="fw-bold text-primary">{{ Auth::user()->weight ?? 'N/A' }} kg</div>
                            <small class="text-muted">Poids</small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <strong>Date de naissance :</strong><br>
                        <span class="text-muted">{{ Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->format('d/m/Y') : 'Non renseigné' }}</span>
                    </div>

                    <div class="mb-2">
                        <strong>Genre :</strong><br>
                        <span class="text-muted">{{ Auth::user()->gender ?? 'Non renseigné' }}</span>
                    </div>

                    <div class="mb-2">
                        <strong>Téléphone :</strong><br>
                        <span class="text-muted">{{ Auth::user()->phone_number ?? 'Non renseigné' }}</span>
                    </div>

                    <div class="mb-0">
                        <strong>Email :</strong><br>
                        <span class="text-muted">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Allergies -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Allergies
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($allergies) && $allergies->count() > 0)
                        @foreach($allergies as $allergy)
                            <div class="alert alert-warning py-2 mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>{{ $allergy->name }}</strong><br>
                                <small class="text-muted">{{ $allergy->description }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Aucune allergie connue</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenu principal du dossier -->
        <div class="col-lg-8">
            <!-- Onglets -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="medicalFileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-eye me-2"></i>Vue d'ensemble
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                                <i class="fas fa-history me-2"></i>Historique médical
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab">
                                <i class="fas fa-pills me-2"></i>Prescriptions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="exams-tab" data-bs-toggle="tab" data-bs-target="#exams" type="button" role="tab">
                                <i class="fas fa-stethoscope me-2"></i>Examens
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vaccines-tab" data-bs-toggle="tab" data-bs-target="#vaccines" type="button" role="tab">
                                <i class="fas fa-syringe me-2"></i>Vaccins
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="medicalFileTabContent">
                        <!-- Vue d'ensemble -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                                            <h5>{{ $totalAppointments ?? 0 }}</h5>
                                            <p class="text-muted mb-0">Rendez-vous totaux</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-pills fa-2x text-success mb-2"></i>
                                            <h5>{{ $totalPrescriptions ?? 0 }}</h5>
                                            <p class="text-muted mb-0">Prescriptions</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-stethoscope fa-2x text-info mb-2"></i>
                                            <h5>{{ $totalExams ?? 0 }}</h5>
                                            <p class="text-muted mb-0">Examens</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-syringe fa-2x text-warning mb-2"></i>
                                            <h5>{{ $totalVaccines ?? 0 }}</h5>
                                            <p class="text-muted mb-0">Vaccins</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Dernière mise à jour :</strong> 
                                {{ now()->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                        <!-- Historique médical -->
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            @if(isset($medicalHistory) && $medicalHistory->count() > 0)
                                @foreach($medicalHistory as $history)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $history->title ?? 'Consultation' }}</h6>
                                                    <p class="text-muted mb-2">{{ $history->description }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $history->created_at ? $history->created_at->format('d/m/Y') : 'Date inconnue' }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-primary">{{ $history->type ?? 'Consultation' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-history fa-2x mb-2"></i>
                                    <p class="mb-0">Aucun historique médical disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Prescriptions -->
                        <div class="tab-pane fade" id="prescriptions" role="tabpanel">
                            @if(isset($prescriptions) && $prescriptions->count() > 0)
                                @foreach($prescriptions as $prescription)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $prescription->medication_name ?? 'Médicament' }}</h6>
                                                    <p class="text-muted mb-2">{{ $prescription->dosage ?? 'Dosage non spécifié' }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Prescrit le {{ $prescription->created_at ? $prescription->created_at->format('d/m/Y') : 'Date inconnue' }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-{{ $prescription->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ $prescription->status == 'active' ? 'Actif' : 'Terminé' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-pills fa-2x mb-2"></i>
                                    <p class="mb-0">Aucune prescription disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Examens -->
                        <div class="tab-pane fade" id="exams" role="tabpanel">
                            @if(isset($exams) && $exams->count() > 0)
                                @foreach($exams as $exam)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $exam->exam_type ?? 'Examen' }}</h6>
                                                    <p class="text-muted mb-2">{{ $exam->description ?? 'Description non disponible' }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Prescrit le {{ $exam->created_at ? $exam->created_at->format('d/m/Y') : 'Date inconnue' }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-{{ $exam->status == 'completed' ? 'success' : ($exam->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ $exam->status == 'completed' ? 'Terminé' : ($exam->status == 'pending' ? 'En attente' : 'Non défini') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-stethoscope fa-2x mb-2"></i>
                                    <p class="mb-0">Aucun examen disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Vaccins -->
                        <div class="tab-pane fade" id="vaccines" role="tabpanel">
                            @if(isset($vaccines) && $vaccines->count() > 0)
                                @foreach($vaccines as $vaccine)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $vaccine->vaccine_name ?? 'Vaccin' }}</h6>
                                                    <p class="text-muted mb-2">{{ $vaccine->description ?? 'Description non disponible' }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Administré le {{ $vaccine->administered_date ? \Carbon\Carbon::parse($vaccine->administered_date)->format('d/m/Y') : 'Date inconnue' }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-success">Administré</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-syringe fa-2x mb-2"></i>
                                    <p class="mb-0">Aucun vaccin enregistré</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printMedicalFile() {
    window.print();
}

function exportMedicalFile() {
    alert('Fonctionnalité d\'export en cours de développement');
}
</script>
@endsection
