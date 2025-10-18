@extends('layouts.doctor')

@section('title', 'Dossier Médical - ' . $patient->first_name . ' ' . $patient->last_name)
@section('page-title', 'Dossier Médical')
@section('page-subtitle', $patient->first_name . ' ' . $patient->last_name)
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header avec boutons d'action en haut à droite -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($patient->photo)
                            <img src="{{ asset('storage/' . $patient->photo) }}" 
                                 alt="Photo patient" 
                                 class="rounded-circle me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                            <p class="text-muted mb-0">{{ $patient->email }}</p>
                            <small class="text-muted">
                                Âge: {{ $patient->age ?? 'N/A' }} ans | 
                                Tél: {{ $patient->phone_number ?? 'N/A' }} | 
                                Groupe sanguin: {{ $patient->blood_type ?? 'N/A' }}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('doctor.medical-files') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="addNote({{ $medicalFile->id }})" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ajouter une note">
                                <i class="fas fa-sticky-note"></i>
                            </button>
                            <button class="btn btn-success" onclick="addPrescription({{ $medicalFile->id }})" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Prescrire un soin hospitalier">
                                <i class="fas fa-pills"></i>
                            </button>
                            <button class="btn btn-danger" onclick="addOrdonnance({{ $medicalFile->id }})" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Rédiger une ordonnance">
                                <i class="fas fa-prescription-bottle-alt"></i>
                            </button>
                            <button class="btn btn-warning" onclick="addExam({{ $medicalFile->id }})" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Prescrire un examen">
                                <i class="fas fa-flask"></i>
                            </button>
                            <button class="btn btn-info" onclick="addMedicalHistory({{ $medicalFile->id }})" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ajouter un antécédent médical">
                                <i class="fas fa-history"></i>
                            </button>
                            <button class="btn btn-secondary" onclick="addDisease({{ $medicalFile->id }})" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Diagnostiquer une maladie">
                                <i class="fas fa-virus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Medical History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Antécédents médicaux
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->medicalHistories && $medicalFile->medicalHistories->count() > 0)
                        @foreach($medicalFile->medicalHistories as $history)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <p class="mb-1">{{ $history->content }}</p>
                                    </div>
                                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p class="mb-0">Aucun antécédent médical enregistré</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Diagnosed Diseases -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-virus text-primary me-2"></i>
                        Maladies diagnostiquées
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->medicaldisease && $medicalFile->medicaldisease->count() > 0)
                        @foreach($medicalFile->medicaldisease->sortByDesc('created_at') as $disease)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $disease->disease->name ?? 'Maladie' }}</h6>
                                    <small class="text-muted">{{ $disease->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>État:</strong> 
                                            <span class="badge bg-{{ $disease->state == 'guéri' ? 'success' : ($disease->state == 'chronique' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($disease->state ?? 'N/A') }}
                                            </span>
                                        </p>
                                        <p class="mb-1"><strong>Traitement:</strong> {{ $disease->treatment ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Description:</strong> {{ $disease->disease->description ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-virus fa-2x mb-2"></i>
                            <p class="mb-0">Aucune maladie diagnostiquée</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Medical Notes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note text-primary me-2"></i>
                        Notes médicales
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->note && $medicalFile->note->count() > 0)
                        @foreach($medicalFile->note->sortByDesc('created_at') as $note)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $note->title ?? 'Note médicale' }}</h6>
                                    <small class="text-muted">{{ $note->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-2">{{ $note->content }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-user-md me-1"></i>
                                    Dr. {{ $note->doctor->first_name ?? 'Médecin' }} {{ $note->doctor->last_name ?? '' }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-sticky-note fa-2x mb-2"></i>
                            <p class="mb-0">Aucune note médicale enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prescriptions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pills text-primary me-2"></i>
                        Prescriptions
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->medicalprescription && $medicalFile->medicalprescription->count() > 0)
                        @foreach($medicalFile->medicalprescription->sortByDesc('created_at') as $prescription)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $prescription->prescription->name ?? 'Prescription' }}</h6>
                                    <small class="text-muted">{{ $prescription->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Quantité:</strong> {{ $prescription->quantity ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Fréquence:</strong> {{ $prescription->frequency ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Durée:</strong> {{ $prescription->duration ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Instructions:</strong> {{ $prescription->instructions ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="mb-1"><strong>Statut:</strong> 
                                            <span class="badge bg-{{ $prescription->is_done ? 'success' : 'warning' }}">
                                                {{ $prescription->is_done ? 'Terminé' : 'En cours' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-user-md me-1"></i>
                                    Dr. {{ $prescription->doctor->first_name ?? 'Médecin' }} {{ $prescription->doctor->last_name ?? '' }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-pills fa-2x mb-2"></i>
                            <p class="mb-0">Aucune prescription enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Exams -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-flask text-primary me-2"></i>
                        Examens prescrits
                    </h5>
                </div>
                <div class="card-body">
                    @if($medicalFile->medicalexam && $medicalFile->medicalexam->count() > 0)
                        @foreach($medicalFile->medicalexam->sortByDesc('created_at') as $exam)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $exam->exam->name ?? 'Examen' }}</h6>
                                    <small class="text-muted">{{ $exam->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Type:</strong> {{ $exam->type ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Description:</strong> {{ $exam->exam->description ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Instructions:</strong> {{ $exam->instructions ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Statut:</strong> 
                                            <span class="badge bg-{{ $exam->is_done ? 'success' : 'warning' }}">
                                                {{ $exam->is_done ? 'Terminé' : 'En attente' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-user-md me-1"></i>
                                    Dr. {{ $exam->doctor->first_name ?? 'Médecin' }} {{ $exam->doctor->last_name ?? '' }}
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-flask fa-2x mb-2"></i>
                            <p class="mb-0">Aucun examen prescrit</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Diseases -->
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Patient Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Informations patient
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="fw-bold text-primary">{{ $patient->age ?? 'N/A' }}</div>
                                <small class="text-muted">Âge</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="fw-bold text-primary">{{ $patient->blood_type ?? 'N/A' }}</div>
                            <small class="text-muted">Groupe sanguin</small>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <strong>Adresse:</strong><br>
                        <small class="text-muted">{{ $patient->address ?? 'Non renseignée' }}</small>
                    </div>
                    <div class="mb-2">
                        <strong>Profession:</strong><br>
                        <small class="text-muted">{{ $patient->profession ?? 'Non renseignée' }}</small>
                    </div>
                    <div class="mb-2">
                        <strong>Contact d'urgence:</strong><br>
                        <small class="text-muted">{{ $patient->emergency_contact ?? 'Non renseigné' }}</small>
                    </div>
                </div>
            </div>

            <!-- Ordonnances -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-prescription-bottle-alt text-danger me-2"></i>
                        Ordonnances récentes
                    </h6>
                </div>
                <div class="card-body">
                    @if($ordonnances && $ordonnances->count() > 0)
                        @foreach($ordonnances->take(5) as $ordonnance)
                            <div class="border-bottom pb-2 mb-2 cursor-pointer" onclick="showOrdonnanceDetails({{ $ordonnance->id }})" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $ordonnance->numero_ordonnance }}</strong><br>
                                        <small class="text-muted">{{ $ordonnance->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $ordonnance->statut == 'active' ? 'success' : ($ordonnance->statut == 'expiree' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($ordonnance->statut) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-pills me-1"></i>
                                    {{ $ordonnance->medicaments->count() }} médicament(s)
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-prescription-bottle-alt fa-2x mb-2"></i>
                            <p class="mb-0">Aucune ordonnance</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Patient Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user-md text-primary me-2"></i>
                        Actions patient
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-1"></i>Voir profil patient
                        </a>
                        <a href="{{ route('doctor.appointments') }}?patient={{ $patient->id }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-calendar me-1"></i>Rendez-vous
                        </a>
                        <button class="btn btn-outline-warning btn-sm" onclick="printMedicalFile()">
                            <i class="fas fa-print me-1"></i>Imprimer dossier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une note -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une note médicale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Contenu de la note</label>
                        <textarea class="form-control" id="noteContent" rows="5" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveNote()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un antécédent -->
<div class="modal fade" id="addMedicalHistoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un antécédent médical</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="medicalHistoryForm">
                    <div class="mb-3">
                        <label for="historyContent" class="form-label">Description de l'antécédent</label>
                        <textarea class="form-control" id="historyContent" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveMedicalHistory()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une prescription -->
<div class="modal fade" id="addPrescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prescrire un soin hospitalier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="prescriptionForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prescriptionSelect" class="form-label">Soin hospitalier <span class="text-danger">*</span></label>
                                <select class="form-select" id="prescriptionSelect" name="prescriptionId" required>
                                    <option value="">Sélectionner un soin hospitalier</option>
                                    @foreach($prescriptions as $prescription)
                                        <option value="{{ $prescription->id }}" 
                                                data-quantity="{{ $prescription->quantity }}" 
                                                data-price="{{ $prescription->price }}"
                                                data-service="{{ $prescription->service->name ?? 'N/A' }}">
                                            {{ $prescription->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <span id="prescriptionInfo" class="text-muted">Tapez pour rechercher dans la liste</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prescriptionQuantity" class="form-label">Quantité prescrite</label>
                                <input type="number" class="form-control" id="prescriptionQuantity" min="1" value="1" required>
                                <small class="form-text text-muted">Nombre de fois à effectuer ce soin</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prescriptionFrequency" class="form-label">Fréquence</label>
                                <select class="form-control" id="prescriptionFrequency">
                                    <option value="unique">Unique</option>
                                    <option value="quotidien">Quotidien</option>
                                    <option value="2x/jour">2 fois par jour</option>
                                    <option value="3x/jour">3 fois par jour</option>
                                    <option value="hebdomadaire">Hebdomadaire</option>
                                    <option value="selon besoin">Selon besoin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prescriptionDuration" class="form-label">Durée</label>
                                <input type="text" class="form-control" id="prescriptionDuration" placeholder="ex: 7 jours, 1 semaine">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="prescriptionInstructions" class="form-label">Instructions spéciales</label>
                        <textarea class="form-control" id="prescriptionInstructions" rows="3" placeholder="Instructions pour l'équipe soignante..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> 
                        <span id="prescriptionInfo">Sélectionnez un soin pour voir les détails</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="savePrescription()">Prescrire</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un examen -->
<div class="modal fade" id="addExamModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prescrire un examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="examForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="examSelect" class="form-label">Examen <span class="text-danger">*</span></label>
                                <select class="form-select" id="examSelect" name="examId" required>
                                    <option value="">Sélectionner un examen</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}" 
                                                data-description="{{ $exam->description }}" 
                                                data-price="{{ $exam->price }}"
                                                data-service="{{ $exam->service->name ?? 'N/A' }}">
                                            {{ $exam->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <span id="examInfo" class="text-muted">Tapez pour rechercher dans la liste</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="examType" class="form-label">Type d'examen</label>
                                <select class="form-control" id="examType">
                                    <option value="biologie">Biologie</option>
                                    <option value="imagerie">Imagerie</option>
                                    <option value="cardiologie">Cardiologie</option>
                                    <option value="neurologie">Neurologie</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="examDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="examDescription" rows="3" placeholder="Description de l'examen..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="examInstructions" class="form-label">Instructions pour le patient</label>
                        <textarea class="form-control" id="examInstructions" rows="3" placeholder="Instructions spéciales..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> 
                        <span id="examInfo">Sélectionnez un examen pour voir les détails</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" onclick="saveExam()">Prescrire</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une ordonnance (médicaments) -->
<div class="modal fade" id="addOrdonnanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Rédiger une ordonnance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ordonnanceForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medicamentSelect" class="form-label">Médicament <span class="text-danger">*</span></label>
                                <select class="form-select" id="medicamentSelect" name="medicamentId" required>
                                    <option value="">Sélectionner un médicament</option>
                                    @foreach($medicaments as $medicament)
                                        <option value="{{ $medicament->id }}" 
                                                data-dosage="{{ $medicament->dosage }}" 
                                                data-forme="{{ $medicament->forme }}"
                                                data-prix="{{ $medicament->prix }}"
                                                data-laboratoire="{{ $medicament->laboratoire }}">
                                            {{ $medicament->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <span id="medicamentInfo" class="text-muted">Tapez pour rechercher dans la liste</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medicamentQuantite" class="form-label">Quantité prescrite</label>
                                <input type="number" class="form-control" id="medicamentQuantite" min="1" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medicamentPosologie" class="form-label">Posologie <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="medicamentPosologie" placeholder="Ex: 1 comprimé 3 fois par jour" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medicamentDuree" class="form-label">Durée (jours)</label>
                                <input type="number" class="form-control" id="medicamentDuree" min="1" placeholder="Ex: 7">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="medicamentInstructions" class="form-label">Instructions spéciales</label>
                        <textarea class="form-control" id="medicamentInstructions" rows="3" placeholder="Avant/pendant/après les repas, précautions particulières..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> 
                        <span id="medicamentInfoDetails">Sélectionnez un médicament pour voir les détails</span>
                    </div>
                </form>
                
                <!-- Liste des médicaments ajoutés -->
                <div class="mt-4">
                    <h6 class="text-danger mb-3">
                        <i class="fas fa-prescription-bottle-alt me-2"></i>
                        Médicaments de l'ordonnance
                    </h6>
                    <div id="medicamentsList" class="border rounded p-3" style="min-height: 100px; background-color: #f8f9fa;">
                        <div class="text-center text-muted">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <p class="mb-0">Aucun médicament ajouté</p>
                            <small>Ajoutez des médicaments ci-dessus</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-outline-danger" onclick="addMedicamentToOrdonnance()">Ajouter ce médicament</button>
                <button type="button" class="btn btn-danger" onclick="finalizeOrdonnance()" id="finalizeBtn" disabled>
                    <i class="fas fa-check me-2"></i>Finaliser l'ordonnance
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une maladie -->
<div class="modal fade" id="addDiseaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une maladie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="diseaseForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="diseaseSelect" class="form-label">Maladie <span class="text-danger">*</span></label>
                                <select class="form-select" id="diseaseSelect" name="diseaseId" required>
                                    <option value="">Sélectionner une maladie</option>
                                    @foreach($diseases as $disease)
                                        <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <span class="text-muted">Tapez pour rechercher dans la liste</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="diseaseState" class="form-label">État</label>
                                <select class="form-select" id="diseaseState">
                                    <option value="actif">Actif</option>
                                    <option value="guéri">Guéri</option>
                                    <option value="chronique">Chronique</option>
                                    <option value="en traitement">En traitement</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="diseaseTreatment" class="form-label">Traitement</label>
                        <textarea class="form-control" id="diseaseTreatment" rows="3" placeholder="Description du traitement..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="diseaseNotes" class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" id="diseaseNotes" rows="2" placeholder="Notes du médecin..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Astuce :</strong> Si la maladie n'est pas dans la liste, vous pouvez la créer en utilisant le bouton "Nouvelle maladie" ci-dessous.
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-primary" onclick="showNewDiseaseForm()">
                            <i class="fas fa-plus me-2"></i>Créer une nouvelle maladie
                        </button>
                    </div>
                </form>
                
                <!-- Formulaire pour créer une nouvelle maladie -->
                <div id="newDiseaseForm" style="display: none;" class="mt-4 border-top pt-4">
                    <h6 class="mb-3">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Créer une nouvelle maladie
                    </h6>
                    <form id="createDiseaseForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newDiseaseName" class="form-label">Nom de la nouvelle maladie</label>
                                    <input type="text" class="form-control" id="newDiseaseName" placeholder="Ex: Nouvelle maladie rare">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="newDiseaseState" class="form-label">État</label>
                                    <select class="form-control" id="newDiseaseState">
                                        <option value="actif">Actif</option>
                                        <option value="guéri">Guéri</option>
                                        <option value="chronique">Chronique</option>
                                        <option value="en traitement">En traitement</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newDiseaseTreatment" class="form-label">Traitement</label>
                            <textarea class="form-control" id="newDiseaseTreatment" rows="3" placeholder="Description du traitement..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="newDiseaseNotes" class="form-label">Notes supplémentaires</label>
                            <textarea class="form-control" id="newDiseaseNotes" rows="2" placeholder="Notes du médecin..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="hideNewDiseaseForm()">
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" onclick="saveNewDisease()">
                                Créer et ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-secondary" onclick="saveDisease()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentMedicalFileId = null;
let ordonnanceMedicaments = []; // Liste des médicaments de l'ordonnance en cours

function addNote(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addNoteModal'));
    modal.show();
}

function saveNote() {
    const content = document.getElementById('noteContent').value;
    
    if (!content.trim()) {
        alert('Veuillez saisir le contenu de la note');
        return;
    }
    
    fetch(`/doctor/medical-files/${currentMedicalFileId}/addnote`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Note ajoutée avec succès');
            location.reload();
        } else {
            alert('Erreur lors de l\'ajout de la note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de la note');
    });
}

function addMedicalHistory(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addMedicalHistoryModal'));
    modal.show();
}

function saveMedicalHistory() {
    const content = document.getElementById('historyContent').value;
    
    if (!content.trim()) {
        alert('Veuillez saisir la description de l\'antécédent');
        return;
    }
    
    fetch(`/doctor/medical-files/${currentMedicalFileId}/addHistory`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Antécédent ajouté avec succès');
            location.reload();
        } else {
            alert('Erreur lors de l\'ajout de l\'antécédent');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de l\'antécédent');
    });
}

function addPrescription(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addPrescriptionModal'));
    modal.show();
}

function savePrescription() {
    const prescriptionId = document.getElementById('prescriptionSelect').value;
    const quantity = document.getElementById('prescriptionQuantity').value;
    const frequency = document.getElementById('prescriptionFrequency').value;
    const duration = document.getElementById('prescriptionDuration').value;
    const instructions = document.getElementById('prescriptionInstructions').value;
    
    if (!prescriptionId) {
        alert('Veuillez sélectionner un soin hospitalier');
        return;
    }
    
    if (!quantity || quantity < 1) {
        alert('Veuillez saisir une quantité valide');
        return;
    }
    
    // Ajouter la prescription au dossier médical
    fetch(`/doctor/medical-files/${currentMedicalFileId}/addprescription`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            prescription_id: prescriptionId,
            quantity: quantity,
            frequency: frequency,
            duration: duration,
            instructions: instructions
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Soin hospitalier prescrit avec succès');
            location.reload();
        } else {
            alert('Erreur lors de la prescription du soin');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la prescription du soin');
    });
}

function addExam(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addExamModal'));
    modal.show();
}

function saveExam() {
    const examId = document.getElementById('examSelect').value;
    const type = document.getElementById('examType').value;
    const description = document.getElementById('examDescription').value;
    const instructions = document.getElementById('examInstructions').value;
    
    if (!examId) {
        alert('Veuillez sélectionner un examen');
        return;
    }
    
    // Ajouter l'examen au dossier médical
    fetch(`/doctor/medical-files/${currentMedicalFileId}/addexam`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            exam_id: examId,
            type: type,
            description: description,
            instructions: instructions
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Examen prescrit avec succès');
            location.reload();
        } else {
            alert('Erreur lors de la prescription de l\'examen');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la prescription de l\'examen');
    });
}

function addDisease(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addDiseaseModal'));
    modal.show();
}

function addOrdonnance(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    ordonnanceMedicaments = []; // Réinitialiser la liste des médicaments
    updateMedicamentsList(); // Mettre à jour l'affichage
    const modal = new bootstrap.Modal(document.getElementById('addOrdonnanceModal'));
    modal.show();
}

function addPrescription(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addPrescriptionModal'));
    modal.show();
}

function addPrescription(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addPrescriptionModal'));
    modal.show();
}

function addPrescription(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addPrescriptionModal'));
    modal.show();
}

function addPrescription(medicalFileId) {
    currentMedicalFileId = medicalFileId;
    const modal = new bootstrap.Modal(document.getElementById('addPrescriptionModal'));
    modal.show();
}

function saveDisease() {
    const diseaseId = document.getElementById('diseaseSelect').value;
    const state = document.getElementById('diseaseState').value;
    const treatment = document.getElementById('diseaseTreatment').value;
    const notes = document.getElementById('diseaseNotes').value;
    
    if (!diseaseId) {
        alert('Veuillez sélectionner une maladie');
        return;
    }
    
    // Ajouter la maladie au dossier médical
    fetch(`/doctor/medical-files/${currentMedicalFileId}/adddisease`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            disease_id: diseaseId,
            treatment: treatment,
            state: state
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Maladie ajoutée avec succès');
            location.reload();
        } else {
            alert('Erreur lors de l\'ajout de la maladie');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de la maladie');
    });
}

function addMedicamentToOrdonnance() {
    const medicamentId = document.getElementById('medicamentSelect').value;
    const quantite = document.getElementById('medicamentQuantite').value;
    const posologie = document.getElementById('medicamentPosologie').value;
    const duree = document.getElementById('medicamentDuree').value;
    const instructions = document.getElementById('medicamentInstructions').value;
    
    if (!medicamentId) {
        alert('Veuillez sélectionner un médicament');
        return;
    }
    
    if (!posologie) {
        alert('Veuillez renseigner la posologie');
        return;
    }
    
    // Récupérer les informations du médicament sélectionné
    const medicamentSelect = document.getElementById('medicamentSelect');
    const selectedOption = medicamentSelect.options[medicamentSelect.selectedIndex];
    const medicamentNom = selectedOption.textContent;
    const medicamentDosage = selectedOption.getAttribute('data-dosage');
    const medicamentForme = selectedOption.getAttribute('data-forme');
    const medicamentPrix = selectedOption.getAttribute('data-prix');
    const medicamentLaboratoire = selectedOption.getAttribute('data-laboratoire');
    
    // Créer l'objet médicament
    const medicament = {
        id: medicamentId,
        nom: medicamentNom,
        dosage: medicamentDosage,
        forme: medicamentForme,
        prix: medicamentPrix,
        laboratoire: medicamentLaboratoire,
        quantite: quantite || 1,
        posologie: posologie,
        duree_jours: duree,
        instructions_speciales: instructions
    };
    
    // Ajouter à la liste
    ordonnanceMedicaments.push(medicament);
    
    // Mettre à jour l'affichage
    updateMedicamentsList();
    
    // Réinitialiser le formulaire
    document.getElementById('ordonnanceForm').reset();
    document.getElementById('medicamentInfoDetails').textContent = 'Sélectionnez un médicament pour voir les détails';
    
    // Activer le bouton de finalisation
    document.getElementById('finalizeBtn').disabled = false;
    
    alert('Médicament ajouté à l\'ordonnance');
}

function updateMedicamentsList() {
    const listContainer = document.getElementById('medicamentsList');
    
    if (ordonnanceMedicaments.length === 0) {
        listContainer.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                <p class="mb-0">Aucun médicament ajouté</p>
                <small>Ajoutez des médicaments ci-dessus</small>
            </div>
        `;
        return;
    }
    
    let html = '';
    ordonnanceMedicaments.forEach((medicament, index) => {
        html += `
            <div class="card mb-2">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">${medicament.nom}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Forme:</strong> ${medicament.forme || 'N/A'}<br>
                                        <strong>Dosage:</strong> ${medicament.dosage || 'N/A'}<br>
                                        <strong>Laboratoire:</strong> ${medicament.laboratoire || 'N/A'}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Quantité:</strong> ${medicament.quantite}<br>
                                        <strong>Posologie:</strong> ${medicament.posologie}<br>
                                        <strong>Durée:</strong> ${medicament.duree_jours || 'N/A'} jours
                                    </small>
                                </div>
                            </div>
                            ${medicament.instructions_speciales ? `<small class="text-muted"><strong>Instructions:</strong> ${medicament.instructions_speciales}</small>` : ''}
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeMedicamentFromOrdonnance(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    listContainer.innerHTML = html;
}

function removeMedicamentFromOrdonnance(index) {
    ordonnanceMedicaments.splice(index, 1);
    updateMedicamentsList();
    
    // Désactiver le bouton de finalisation si plus de médicaments
    if (ordonnanceMedicaments.length === 0) {
        document.getElementById('finalizeBtn').disabled = true;
    }
}

function finalizeOrdonnance() {
    if (ordonnanceMedicaments.length === 0) {
        alert('Aucun médicament dans l\'ordonnance');
        return;
    }
    
    // Envoyer tous les médicaments au serveur
    fetch(`/doctor/medical-files/${currentMedicalFileId}/addordonnance`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            medicaments: ordonnanceMedicaments
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Ordonnance créée avec succès');
            location.reload();
        } else {
            alert('Erreur lors de la création de l\'ordonnance');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la création de l\'ordonnance');
    });
}

function showNewDiseaseForm() {
    document.getElementById('newDiseaseForm').style.display = 'block';
    document.getElementById('diseaseForm').style.display = 'none';
}

function hideNewDiseaseForm() {
    document.getElementById('newDiseaseForm').style.display = 'none';
    document.getElementById('diseaseForm').style.display = 'block';
}

function saveNewDisease() {
    const name = document.getElementById('newDiseaseName').value;
    const state = document.getElementById('newDiseaseState').value;
    const treatment = document.getElementById('newDiseaseTreatment').value;
    const notes = document.getElementById('newDiseaseNotes').value;
    
    if (!name.trim()) {
        alert('Veuillez saisir le nom de la nouvelle maladie');
        return;
    }
    
    // Créer d'abord la maladie
    fetch('/diseases', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.id) {
            // Ajouter la maladie au dossier médical
            return fetch(`/doctor/medical-files/${currentMedicalFileId}/adddisease`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    disease_id: data.id,
                    treatment: treatment,
                    state: state
                })
            });
        } else {
            throw new Error('Erreur lors de la création de la maladie');
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Nouvelle maladie créée et ajoutée avec succès');
            location.reload();
        } else {
            alert('Erreur lors de l\'ajout de la maladie');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de la maladie');
    });
}

function printMedicalFile() {
    window.print();
}

// Fonction simple et efficace pour les selects avec recherche
function enhanceSelect(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    // Créer un input de recherche
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'form-control mb-2';
    searchInput.placeholder = 'Tapez pour rechercher...';
    searchInput.style.display = 'none';
    
    // Insérer l'input avant le select
    select.parentNode.insertBefore(searchInput, select);
    
    // Gérer le focus sur le select
    select.addEventListener('focus', function() {
        searchInput.style.display = 'block';
        searchInput.focus();
    });
    
    // Gérer le blur
    select.addEventListener('blur', function() {
        setTimeout(() => {
            if (!searchInput.matches(':focus')) {
                searchInput.style.display = 'none';
                searchInput.value = '';
                // Réinitialiser toutes les options
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    option.style.display = 'block';
                });
            }
        }, 200);
    });
    
    // Recherche en temps réel
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const options = select.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') return;
            
            const optionText = option.textContent.toLowerCase();
            if (optionText.includes(searchTerm)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });
    
    // Sélectionner l'option correspondante avec Entrée
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const visibleOptions = select.querySelectorAll('option:not([style*="display: none"])');
            if (visibleOptions.length > 1) { // Plus que l'option par défaut
                select.value = visibleOptions[1].value;
                select.dispatchEvent(new Event('change'));
            }
            this.blur();
        }
    });
}

// Fermer les modals et réinitialiser les formulaires
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const forms = this.querySelectorAll('form');
            forms.forEach(form => form.reset());
            // Réinitialiser l'affichage des formulaires maladie
            if (this.id === 'addDiseaseModal') {
                document.getElementById('newDiseaseForm').style.display = 'none';
                document.getElementById('diseaseForm').style.display = 'block';
            }
        });
    });
    
    // Améliorer les selects avec recherche simple
    enhanceSelect('diseaseSelect');
    enhanceSelect('prescriptionSelect');
    enhanceSelect('examSelect');
    enhanceSelect('medicamentSelect');
    
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Afficher les informations du soin sélectionné
    const prescriptionSelect = document.getElementById('prescriptionSelect');
    if (prescriptionSelect) {
        prescriptionSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const infoSpan = document.getElementById('prescriptionInfo');
            
            if (selectedOption.value) {
                const quantity = selectedOption.getAttribute('data-quantity');
                const price = selectedOption.getAttribute('data-price');
                const service = selectedOption.getAttribute('data-service');
                
                let info = `Service: ${service}`;
                if (quantity) {
                    info += ` | Quantité unitaire: ${quantity}`;
                }
                if (price) {
                    info += ` | Prix: ${parseInt(price).toLocaleString()} FCFA`;
                }
                
                infoSpan.textContent = info;
            } else {
                infoSpan.textContent = 'Sélectionnez un soin pour voir les détails';
            }
        });
    }
    
    
    // Afficher les informations de l'examen sélectionné
    const examSelect = document.getElementById('examSelect');
    if (examSelect) {
        examSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const infoSpan = document.getElementById('examInfo');
            
            if (selectedOption.value) {
                const description = selectedOption.getAttribute('data-description');
                const price = selectedOption.getAttribute('data-price');
                const service = selectedOption.getAttribute('data-service');
                
                let info = `Service: ${service}`;
                if (description) {
                    info += ` | Description: ${description.substring(0, 50)}...`;
                }
                if (price) {
                    info += ` | Prix: ${parseInt(price).toLocaleString()} FCFA`;
                }
                
                infoSpan.textContent = info;
                
                // Auto-remplir la description si elle existe
                const descriptionField = document.getElementById('examDescription');
                if (description && !descriptionField.value) {
                    descriptionField.value = description;
                }
            } else {
                infoSpan.textContent = 'Sélectionnez un examen pour voir les détails';
            }
        });
    }
    
    // Afficher les informations du médicament sélectionné
    const medicamentSelect = document.getElementById('medicamentSelect');
    if (medicamentSelect) {
        medicamentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const infoSpan = document.getElementById('medicamentInfoDetails');
            
            if (selectedOption.value) {
                const dosage = selectedOption.getAttribute('data-dosage');
                const forme = selectedOption.getAttribute('data-forme');
                const prix = selectedOption.getAttribute('data-prix');
                const laboratoire = selectedOption.getAttribute('data-laboratoire');
                
                let info = `Forme: ${forme}`;
                if (dosage) {
                    info += ` | Dosage: ${dosage}`;
                }
                if (laboratoire) {
                    info += ` | Laboratoire: ${laboratoire}`;
                }
                if (prix) {
                    info += ` | Prix: ${parseFloat(prix).toLocaleString()} FCFA`;
                }
                
                infoSpan.textContent = info;
            } else {
                infoSpan.textContent = 'Sélectionnez un médicament pour voir les détails';
            }
        });
    }
});

// Fonction pour afficher les détails d'une ordonnance
function showOrdonnanceDetails(ordonnanceId) {
    // Récupérer les données de l'ordonnance depuis les données passées à la vue
    const ordonnances = @json($ordonnances);
    const ordonnance = ordonnances.find(o => o.id === ordonnanceId);
    
    if (!ordonnance) {
        alert('Ordonnance non trouvée');
        return;
    }
    
    // Construire le contenu HTML
    let content = `
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-danger">Informations de l'ordonnance</h6>
                <p><strong>Numéro:</strong> ${ordonnance.numero_ordonnance}</p>
                <p><strong>Date de prescription:</strong> ${new Date(ordonnance.date_prescription).toLocaleDateString('fr-FR')}</p>
                <p><strong>Date de validité:</strong> ${ordonnance.date_validite ? new Date(ordonnance.date_validite).toLocaleDateString('fr-FR') : 'N/A'}</p>
                <p><strong>Statut:</strong> 
                    <span class="badge bg-${ordonnance.statut === 'active' ? 'success' : (ordonnance.statut === 'expiree' ? 'danger' : 'secondary')}">
                        ${ordonnance.statut.charAt(0).toUpperCase() + ordonnance.statut.slice(1)}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="text-danger">Informations du patient</h6>
                <p><strong>Patient:</strong> ${ordonnance.patient_first_name} ${ordonnance.patient_last_name}</p>
                <p><strong>Médecin:</strong> ${ordonnance.medecin_first_name} ${ordonnance.medecin_last_name}</p>
            </div>
        </div>
        
        <hr>
        
        <h6 class="text-danger mb-3">Médicaments prescrits</h6>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Médicament</th>
                        <th>Quantité</th>
                        <th>Posologie</th>
                        <th>Durée</th>
                        <th>Instructions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    ordonnance.medicaments.forEach(medicament => {
        content += `
            <tr>
                <td>
                    <strong>${medicament.nom}</strong><br>
                    <small class="text-muted">${medicament.forme || ''} ${medicament.dosage || ''}</small>
                </td>
                <td>${medicament.pivot.quantite}</td>
                <td>${medicament.pivot.posologie}</td>
                <td>${medicament.pivot.duree_jours ? medicament.pivot.duree_jours + ' jours' : 'N/A'}</td>
                <td>${medicament.pivot.instructions_speciales || 'N/A'}</td>
            </tr>
        `;
    });
    
    content += `
                </tbody>
            </table>
        </div>
    `;
    
    if (ordonnance.instructions) {
        content += `
            <hr>
            <h6 class="text-danger">Instructions générales</h6>
            <p>${ordonnance.instructions}</p>
        `;
    }
    
    // Afficher le modal
    document.getElementById('ordonnanceDetailsContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('ordonnanceDetailsModal'));
    modal.show();
}

// Fonction pour imprimer l'ordonnance
function printOrdonnance() {
    const content = document.getElementById('ordonnanceDetailsContent').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>Ordonnance</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="container mt-4">
                <div class="text-center mb-4">
                    <h2 class="text-danger">ORDONNANCE MÉDICALE</h2>
                    <hr>
                </div>
                ${content}
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
}

.border-bottom:last-child {
    border-bottom: none !important;
}

.btn-group .btn {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.text-primary {
    color: #2563eb !important;
}

.text-success {
    color: #10b981 !important;
}

.text-warning {
    color: #f59e0b !important;
}

.text-danger {
    color: #ef4444 !important;
}

.bg-primary {
    background-color: #2563eb !important;
}

.bg-success {
    background-color: #10b981 !important;
}

.bg-warning {
    background-color: #f59e0b !important;
}

.bg-danger {
    background-color: #ef4444 !important;
}

.btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-success {
    background-color: #10b981;
    border-color: #10b981;
}

.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
}

.btn-info {
    background-color: #06b6d4;
    border-color: #06b6d4;
}

.btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
}

.modal-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    font-weight: 600;
    color: #374151;
}

.form-label {
    font-weight: 500;
    color: #374151;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

@media print {
    .btn-group, .modal, .alert {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    
    .card-header {
        background: #f8f9fa !important;
    }
}
</style>

<!-- Modal pour afficher les détails d'une ordonnance -->
<div class="modal fade" id="ordonnanceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Détails de l'ordonnance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="ordonnanceDetailsContent">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-danger" onclick="printOrdonnance()">
                    <i class="fas fa-print me-2"></i>Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
