@extends('layouts.admin')

@section('title', 'Détails du Médecin')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        {{ $doctor->name }}
                    </h1>
                    <p class="text-muted mb-0">Détails du médecin</p>
                </div>
                <div>
                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Modifier
                    </a>
                    <a href="{{ route('admin.doctors') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($doctor->photo)
                        <img src="{{ asset('storage/' . $doctor->photo) }}"
                             alt="{{ $doctor->name }}"
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto"
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-white"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $doctor->name }}</h4>
                    <p class="text-muted mb-2">{{ $doctor->email }}</p>
                    
                    @if($doctor->specialty)
                        <span class="badge bg-info mb-2">{{ $doctor->specialty }}</span>
                    @endif
                    
                    @if($doctor->status === 'active')
                        <span class="badge bg-success">Actif</span>
                    @elseif($doctor->status === 'inactive')
                        <span class="badge bg-danger">Inactif</span>
                    @else
                        <span class="badge bg-warning">En attente</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Détails -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations détaillées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Prénom</label>
                                <p class="form-control-plaintext">{{ $doctor->first_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nom de famille</label>
                                <p class="form-control-plaintext">{{ $doctor->last_name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext">{{ $doctor->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Téléphone</label>
                                <p class="form-control-plaintext">{{ $doctor->phone }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($doctor->description)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="form-control-plaintext">{{ $doctor->description }}</p>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date d'inscription</label>
                                <p class="form-control-plaintext">{{ $doctor->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dernière modification</label>
                                <p class="form-control-plaintext">{{ $doctor->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $doctor->services_count ?? 0 }}</h4>
                            <p class="mb-0">Services</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-stethoscope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $doctor->appointments_count ?? 0 }}</h4>
                            <p class="mb-0">Rendez-vous</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $doctor->created_at->diffInDays(now()) }}</h4>
                            <p class="mb-0">Jours actifs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
