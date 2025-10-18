@extends('layouts.doctor')

@section('title', 'Mes Statistiques - CareWell')
@section('page-title', 'Mes Statistiques')
@section('page-subtitle', 'Analyse de votre activité médicale')

@section('content')
<div class="row">
    <!-- Statistiques générales -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $totalAppointments }}</h4>
                        <p class="mb-0">Total RDV</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $todayAppointments }}</h4>
                        <p class="mb-0">RDV Aujourd'hui</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $pendingAppointments }}</h4>
                        <p class="mb-0">En Attente</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $uniquePatients }}</h4>
                        <p class="mb-0">Patients Uniques</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistiques détaillées -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie text-primary me-2"></i>
                    Répartition des RDV
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $confirmedAppointments }}</h4>
                            <small class="text-muted">Confirmés</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h4 class="text-success mb-1">{{ $completedAppointments }}</h4>
                            <small class="text-muted">Terminés</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <h4 class="text-warning mb-1">{{ $pendingAppointments }}</h4>
                        <small class="text-muted">En Attente</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    Activité Mensuelle
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h2 class="text-primary mb-2">{{ $monthlyAppointments }}</h2>
                    <p class="text-muted mb-0">Rendez-vous ce mois</p>
                    <small class="text-muted">{{ now()->format('F Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Rendez-vous d'aujourd'hui -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day text-primary me-2"></i>
                    Rendez-vous d'aujourd'hui
                </h5>
            </div>
            <div class="card-body">
                @if($todayAppointmentsList->count() > 0)
                    @foreach($todayAppointmentsList as $appointment)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <h6 class="mb-1">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</h6>
                                <small class="text-muted">{{ $appointment->service->name }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p class="mb-0">Aucun rendez-vous aujourd'hui</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rendez-vous récents -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history text-primary me-2"></i>
                    Rendez-vous récents
                </h5>
            </div>
            <div class="card-body">
                @if($recentAppointments->count() > 0)
                    @foreach($recentAppointments as $appointment)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <h6 class="mb-1">{{ $appointment->user->first_name }} {{ $appointment->user->last_name }}</h6>
                                <small class="text-muted">{{ $appointment->service->name }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p class="mb-0">Aucun rendez-vous récent</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection