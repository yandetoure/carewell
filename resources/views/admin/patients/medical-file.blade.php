@extends('layouts.admin')

@section('title', 'Dossier Médical - Admin')
@section('page-title', 'Dossier Médical')
@section('page-subtitle', 'Détails du dossier médical du patient')
@section('user-role', 'Administrateur')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête du patient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if($patient->photo)
                                <img src="{{ asset('storage/' . $patient->photo) }}"
                                     alt="{{ $patient->name }}"
                                     class="img-fluid rounded-circle"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-1">{{ $patient->name }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-envelope me-2"></i>{{ $patient->email }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-phone me-2"></i>{{ $patient->phone ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-id-card me-1"></i>
                                    {{ $patient->identification_number }}
                                </span>
                            </div>
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Membre depuis {{ $patient->created_at->format('M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du dossier médical -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-folder-medical me-2"></i>
                        Informations du Dossier Médical
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Numéro d'identification :</label>
                                <p class="mb-0">{{ $medicalFile->identification_number ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de création :</label>
                                <p class="mb-0">{{ $medicalFile->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dernière mise à jour :</label>
                                <p class="mb-0">{{ $medicalFile->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut :</label>
                                <span class="badge bg-success">Actif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $medicalFile->medicalHistories->count() ?? 0 }}</h4>
                            <p class="mb-0">Antécédents</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-history fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $ordonnances->count() }}</h4>
                            <p class="mb-0">Ordonnances</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-prescription-bottle-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $medicalFile->medicalexam->count() ?? 0 }}</h4>
                            <p class="mb-0">Examens</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-stethoscope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $medicalFile->note->count() ?? 0 }}</h4>
                            <p class="mb-0">Notes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $diseases->count() }}</h4>
                            <p class="mb-0">Maladies</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-virus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $appointments->count() }}</h4>
                            <p class="mb-0">Rendez-vous</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections du dossier médical -->
    <div class="row">
        <!-- Maladies diagnostiquées -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-virus me-2"></i>
                        Maladies Diagnostiquées
                    </h5>
                </div>
                <div class="card-body">
                    @if($diseases && $diseases->count() > 0)
                        @foreach($diseases as $disease)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1 text-danger">{{ $disease->name ?? 'Maladie' }}</h6>
                                <p class="mb-1 text-muted small">{{ $disease->description ?? 'Aucune description' }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Diagnostiquée le {{ $disease->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucune maladie diagnostiquée
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ordonnances récentes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-prescription-bottle-alt me-2"></i>
                        Ordonnances Récentes
                    </h5>
                </div>
                <div class="card-body">
                    @if($ordonnances && $ordonnances->count() > 0)
                        @foreach($ordonnances->take(5) as $ordonnance)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">
                                    <span class="badge bg-success me-2">{{ $ordonnance->numero_ordonnance }}</span>
                                    Dr. {{ $ordonnance->medecin_nom_complet }}
                                </h6>
                                <p class="mb-1 text-muted small">
                                    <i class="fas fa-pills me-1"></i>
                                    {{ $ordonnance->medicaments->count() }} médicament(s)
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $ordonnance->date_prescription->format('d/m/Y') }}
                                    <span class="badge bg-{{ $ordonnance->statut === 'active' ? 'success' : ($ordonnance->statut === 'expiree' ? 'warning' : 'danger') }} ms-2">
                                        {{ ucfirst($ordonnance->statut) }}
                                    </span>
                                </small>
                            </div>
                        @endforeach
                        @if($ordonnances->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">Et {{ $ordonnances->count() - 5 }} autres ordonnances...</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucune ordonnance enregistrée
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rendez-vous récents -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Rendez-vous Récents
                    </h5>
                </div>
                <div class="card-body">
                    @if($appointments && $appointments->count() > 0)
                        @foreach($appointments->take(5) as $appointment)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">
                                    {{ $appointment->service->name ?? 'Service' }}
                                    @if($appointment->doctor)
                                        <small class="text-muted">- Dr. {{ $appointment->doctor->name }}</small>
                                    @endif
                                </h6>
                                <p class="mb-1 text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y à H:i') }}
                                </p>
                                <small class="text-muted">
                                    <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </small>
                            </div>
                        @endforeach
                        @if($appointments->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">Et {{ $appointments->count() - 5 }} autres rendez-vous...</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun rendez-vous enregistré
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Examens médicaux -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Examens Médicaux
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->medicalexam && $medicalFile->medicalexam->count() > 0)
                        @foreach($medicalFile->medicalexam as $exam)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">{{ $exam->exam_name ?? 'Examen' }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $exam->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun examen enregistré
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Antécédents médicaux -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Antécédents Médicaux
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->medicalHistories && $medicalFile->medicalHistories->count() > 0)
                        @foreach($medicalFile->medicalHistories as $history)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">{{ $history->disease_name ?? 'Antécédent' }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $history->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun antécédent médical enregistré
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes médicales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Notes Médicales
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->note && $medicalFile->note->count() > 0)
                        @foreach($medicalFile->note as $note)
                            <div class="border-bottom pb-2 mb-2">
                                <h6 class="mb-1">{{ $note->title ?? 'Note' }}</h6>
                                <p class="mb-1 text-muted small">{{ Str::limit($note->content ?? '', 100) }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $note->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucune note médicale enregistrée
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('admin.patients') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <button class="btn btn-primary me-2" onclick="printMedicalFile()">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                    <button class="btn btn-success" onclick="exportMedicalFile()">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function printMedicalFile() {
    window.print();
}

function exportMedicalFile() {
    // TODO: Implémenter l'export du dossier médical
    alert('Fonctionnalité d\'export à venir');
}
</script>
@endsection
