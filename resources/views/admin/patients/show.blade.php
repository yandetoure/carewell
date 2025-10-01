@extends('layouts.admin')

@section('title', 'Détails du Patient - Admin')
@section('page-title', 'Détails du Patient')
@section('page-subtitle', 'Informations complètes du patient')
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
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-1">{{ $patient->name }}</h3>
                            <p class="text-muted mb-1">
                                <i class="fas fa-envelope me-2"></i>{{ $patient->email }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-phone me-2"></i>{{ $patient->phone ?? 'N/A' }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $patient->adress ?? 'Adresse non renseignée' }}
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

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $appointmentsCount }}</h4>
                            <p class="mb-0">Rendez-vous</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $medicalFilesCount }}</h4>
                            <p class="mb-0">Dossiers médicaux</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-folder-medical fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $patient->created_at->format('M Y') }}</h4>
                            <p class="mb-0">Membre depuis</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                @if($lastAppointment)
                                    {{ $lastAppointment->created_at->format('d/m') }}
                                @else
                                    N/A
                                @endif
                            </h4>
                            <p class="mb-0">Dernier RDV</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Informations Personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Prénom :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->first_name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Nom :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->last_name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Email :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->email }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Téléphone :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->phone ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Date de naissance :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->day_of_birth ? \Carbon\Carbon::parse($patient->day_of_birth)->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Adresse :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->adress ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations médicales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Informations Médicales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Numéro d'identification :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->identification_number }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Statut :</strong>
                        </div>
                        <div class="col-sm-8">
                            @if($patient->status === 'active')
                                <span class="badge bg-success">Actif</span>
                            @elseif($patient->status === 'inactive')
                                <span class="badge bg-danger">Inactif</span>
                            @else
                                <span class="badge bg-warning">En attente</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Dossiers médicaux :</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-info">{{ $medicalFilesCount }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Rendez-vous :</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-primary">{{ $appointmentsCount }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Dernière activité :</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $patient->updated_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
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
                    <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('admin.patients.medical-file', $patient) }}" class="btn btn-success me-2">
                        <i class="fas fa-folder-medical me-2"></i>Dossier médical
                    </a>
                    <button class="btn btn-danger" onclick="deletePatient({{ $patient->id }})">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deletePatient(patientId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce patient ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/patients/${patientId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
