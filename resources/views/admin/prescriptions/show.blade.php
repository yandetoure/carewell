@extends('layouts.admin')

@section('title', 'Détails du Soin Médical - Admin')
@section('page-title', 'Détails du Soin Médical')
@section('page-subtitle', 'Informations complètes du soin')
@section('user-role', 'Administrateur')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Informations principales -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical me-2"></i>
                            {{ $prescription->name }}
                        </h5>
                        <span class="badge bg-light text-primary">ID: #{{ $prescription->id }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-stethoscope me-2"></i>Service
                                </h6>
                                <p class="h5">
                                    <span class="badge bg-primary">{{ $prescription->service->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-hashtag me-2"></i>Quantité
                                </h6>
                                <p class="h5">
                                    <span class="badge bg-info">{{ $prescription->quantity }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-money-bill-wave me-2"></i>Prix
                                </h6>
                                <p class="h5 text-success">
                                    {{ number_format($prescription->price, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-calculator me-2"></i>Prix Total
                                </h6>
                                <p class="h5 text-success">
                                    {{ number_format($prescription->price * $prescription->quantity, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>

                        @if($prescription->created_at)
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Créé le: {{ $prescription->created_at->format('d/m/Y à H:i') }}
                                    </small>
                                </div>
                                @if($prescription->updated_at && $prescription->updated_at != $prescription->created_at)
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-edit me-1"></i>
                                            Modifié le: {{ $prescription->updated_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.prescriptions') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                            <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <form action="{{ route('admin.prescriptions.destroy', $prescription->id) }}" method="POST"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce soin ?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection