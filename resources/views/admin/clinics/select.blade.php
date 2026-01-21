@extends('layouts.admin')

@section('title', 'Sélectionner une clinique - CareWell')
@section('page-title', 'Sélectionner une clinique')
@section('page-subtitle', 'Choisissez la clinique à gérer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Sélectionner une clinique
                    </h5>
                </div>
                <div class="card-body">
                    @if($selectedClinic)
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Clinique actuellement sélectionnée
                            </h6>
                            <p class="mb-2"><strong>{{ $selectedClinic->name }}</strong></p>
                            <p class="mb-0 text-muted small">{{ $selectedClinic->address }}, {{ $selectedClinic->city }}</p>
                            <form action="{{ route('admin.clinics.clear-selected') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Passer en vue globale
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($clinics->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucune clinique active disponible. 
                            <a href="{{ route('admin.clinics.create') }}" class="alert-link">Créer une nouvelle clinique</a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($clinics as $clinic)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border {{ session('selected_clinic_id') == $clinic->id ? 'border-primary shadow' : '' }}">
                                        @if($clinic->logo)
                                            <img src="{{ asset('storage/' . $clinic->logo) }}" class="card-img-top" alt="{{ $clinic->name }}" style="height: 150px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fas fa-building fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $clinic->name }}</h5>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $clinic->address }}, {{ $clinic->city }}
                                            </p>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-phone me-1"></i>
                                                {{ $clinic->phone_number }}
                                            </p>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-envelope me-1"></i>
                                                {{ $clinic->email }}
                                            </p>
                                            @if($clinic->description)
                                                <p class="card-text small text-muted flex-grow-1">{{ Str::limit($clinic->description, 100) }}</p>
                                            @endif
                                            
                                            <div class="mt-auto">
                                                @if(session('selected_clinic_id') == $clinic->id)
                                                    <button class="btn btn-primary w-100" disabled>
                                                        <i class="fas fa-check me-1"></i>Clinique active
                                                    </button>
                                                @else
                                                    <form action="{{ route('admin.clinics.set-selected') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="fas fa-check me-1"></i>Sélectionner
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Utilisateurs</small>
                                                    <strong>{{ $clinic->users_count ?? 0 }}</strong>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Rendez-vous</small>
                                                    <strong>{{ $clinic->appointments_count ?? 0 }}</strong>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Services</small>
                                                    <strong>{{ $clinic->services_count ?? 0 }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

