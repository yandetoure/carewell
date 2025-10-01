@extends('layouts.doctor')

@section('title', 'Détails Patient - Docteur')
@section('page-title', 'Détails Patient')
@section('page-subtitle', 'Informations complètes du patient')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- En-tête du patient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="patient-avatar me-3">
                                <div class="avatar bg-primary text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope me-1"></i>{{ $patient->email }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                                </p>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-birthday-cake me-1"></i>
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} ans
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-{{ $patient->gender == 'male' ? 'mars' : 'venus' }} me-1"></i>
                                        {{ $patient->gender == 'male' ? 'Homme' : 'Femme' }}
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        {{ $totalAppointments }} RDV
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.patients.edit', $patient) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            <a href="{{ route('doctor.patients.history', $patient) }}" class="btn btn-outline-info">
                                <i class="fas fa-history me-1"></i>Historique
                            </a>
                            <a href="{{ route('doctor.patients.appointments', $patient) }}" class="btn btn-outline-success">
                                <i class="fas fa-calendar-plus me-1"></i>Nouveau RDV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Prénom:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->first_name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Nom:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->last_name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-8">
                            <a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Téléphone:</strong>
                        </div>
                        <div class="col-sm-8">
                            <a href="tel:{{ $patient->phone }}">{{ $patient->phone }}</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Date de naissance:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Sexe:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->gender == 'male' ? 'Homme' : 'Femme' }}
                        </div>
                    </div>
                    @if($patient->address)
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Adresse:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->address }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact d'urgence -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-phone me-2"></i>Contact d'urgence
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->emergency_contact || $patient->emergency_phone)
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Contact:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $patient->emergency_contact ?? 'Non renseigné' }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Téléphone:</strong>
                            </div>
                            <div class="col-sm-8">
                                @if($patient->emergency_phone)
                                    <a href="tel:{{ $patient->emergency_phone }}">{{ $patient->emergency_phone }}</a>
                                @else
                                    Non renseigné
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-phone-slash fa-2x mb-2"></i>
                            <p>Aucun contact d'urgence renseigné</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations médicales -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat me-2"></i>Informations médicales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Antécédents médicaux</h6>
                            @if($patient->medical_history)
                                <p>{{ $patient->medical_history }}</p>
                            @else
                                <p class="text-muted">Aucun antécédent médical renseigné</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Allergies</h6>
                            @if($patient->allergies)
                                <p>{{ $patient->allergies }}</p>
                            @else
                                <p class="text-muted">Aucune allergie connue</p>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Médicaments actuels</h6>
                            @if($patient->current_medications)
                                <p>{{ $patient->current_medications }}</p>
                            @else
                                <p class="text-muted">Aucun médicament actuel renseigné</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rendez-vous récents -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Dernier rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    @if($lastAppointment)
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $lastAppointment->service->name ?? 'Service non spécifié' }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('H:i') }}
                                </p>
                            </div>
                            <span class="badge bg-{{ $lastAppointment->status == 'confirmed' ? 'success' : ($lastAppointment->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($lastAppointment->status) }}
                            </span>
                        </div>
                        @if($lastAppointment->notes)
                            <hr>
                            <p class="mb-0"><strong>Notes:</strong> {{ $lastAppointment->notes }}</p>
                        @endif
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>Aucun rendez-vous précédent</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Prochain rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    @if($nextAppointment)
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $nextAppointment->service->name ?? 'Service non spécifié' }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('H:i') }}
                                </p>
                            </div>
                            <span class="badge bg-success">Confirmé</span>
                        </div>
                        @if($nextAppointment->notes)
                            <hr>
                            <p class="mb-0"><strong>Notes:</strong> {{ $nextAppointment->notes }}</p>
                        @endif
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <p>Aucun prochain rendez-vous</p>
                            <a href="{{ route('doctor.patients.appointments', $patient) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Planifier un RDV
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Dossier médical -->
    @if($medicalFile)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-medical me-2"></i>Dossier médical
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Numéro de dossier:</strong> {{ $medicalFile->id }}
                        </div>
                        <div class="col-md-6">
                            <strong>Date de création:</strong> {{ $medicalFile->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('medical-files.show', $patient->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i>Voir le dossier complet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions rapides -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('doctor.patients.edit', $patient) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Modifier les informations
                        </a>
                        <a href="{{ route('doctor.patients.history', $patient) }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-1"></i>Voir l'historique complet
                        </a>
                        <a href="{{ route('doctor.patients.appointments', $patient) }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-plus me-1"></i>Gérer les rendez-vous
                        </a>
                        @if($medicalFile)
                        <a href="{{ route('medical-files.show', $patient->id) }}" class="btn btn-outline-warning">
                            <i class="fas fa-file-medical me-1"></i>Dossier médical
                        </a>
                        @endif
                        <a href="mailto:{{ $patient->email }}" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope me-1"></i>Envoyer un email
                        </a>
                        <a href="tel:{{ $patient->phone }}" class="btn btn-outline-secondary">
                            <i class="fas fa-phone me-1"></i>Appeler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.patient-avatar .avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush
