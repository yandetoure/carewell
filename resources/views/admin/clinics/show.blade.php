@extends('layouts.admin')

@section('title', 'Détails de la Clinique - Admin')
@section('page-title', 'Détails de la Clinique')
@section('page-subtitle', 'Informations complètes sur la clinique')
@section('user-role', 'Super Administrateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        {{ $clinic->name }}
                    </h5>
                    <div>
                        <a href="{{ route('admin.clinics.edit', $clinic) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <a href="{{ route('admin.clinics.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($clinic->logo)
                                <img src="{{ asset('storage/' . $clinic->logo) }}" 
                                     alt="{{ $clinic->name }}" 
                                     class="img-fluid rounded mb-3"
                                     style="max-height: 200px;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center mb-3" 
                                     style="height: 200px;">
                                    <i class="fas fa-building fa-4x text-white"></i>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Statut :</strong>
                                @if($clinic->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-4">Informations générales</h4>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-envelope me-2"></i>Email :</strong>
                                    <p>{{ $clinic->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-phone me-2"></i>Téléphone :</strong>
                                    <p>{{ $clinic->phone_number }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Adresse :</strong>
                                    <p>{{ $clinic->address }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-city me-2"></i>Ville :</strong>
                                    <p>{{ $clinic->city ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-globe me-2"></i>Pays :</strong>
                                    <p>{{ $clinic->country ?? 'N/A' }}</p>
                                </div>
                            </div>

                            @if($clinic->description)
                            <div class="mb-3">
                                <strong><i class="fas fa-info-circle me-2"></i>Description :</strong>
                                <p>{{ $clinic->description }}</p>
                            </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-calendar me-2"></i>Date de création :</strong>
                                    <p>{{ $clinic->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-edit me-2"></i>Dernière modification :</strong>
                                    <p>{{ $clinic->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $clinic->users_count ?? 0 }}</h4>
                            <small>Utilisateurs</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $clinic->appointments_count ?? 0 }}</h4>
                            <small>Rendez-vous</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $clinic->services_count ?? 0 }}</h4>
                            <small>Services</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">{{ $clinic->medical_files_count ?? 0 }}</h4>
                            <small>Dossiers médicaux</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users') }}?clinic_id={{ $clinic->id }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users me-2"></i>Voir les utilisateurs
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.appointments') }}?clinic_id={{ $clinic->id }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar me-2"></i>Voir les rendez-vous
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.services') }}?clinic_id={{ $clinic->id }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-stethoscope me-2"></i>Voir les services
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <form action="{{ route('admin.clinics.set-selected') }}" method="POST">
                                @csrf
                                <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check me-2"></i>Sélectionner cette clinique
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

