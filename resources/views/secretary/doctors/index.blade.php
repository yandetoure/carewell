@extends('layouts.secretary')

@section('title', 'Gestion des Médecins - Secrétariat')
@section('page-title', 'Gestion des Médecins')
@section('page-subtitle', 'Gérer les médecins du service')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques des médecins -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $doctors->count() }}</h4>
                            <p class="text-muted mb-0">Total médecins</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $activeDoctors }}</h4>
                            <p class="text-muted mb-0">Actifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $doctorsWithAppointments }}</h4>
                            <p class="text-muted mb-0">Avec RDV aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $doctorsWithAvailability }}</h4>
                            <p class="text-muted mb-0">Disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        Médecins du Service
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('secretary.doctors.availability') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Disponibilités
                        </a>
                        <a href="{{ route('secretary.doctors.schedule') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-week me-2"></i>Planning
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($doctors->count() > 0)
                        <div class="row">
                            @foreach($doctors as $doctor)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 doctor-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($doctor->photo)
                                                    <img src="{{ asset('storage/' . $doctor->photo) }}" 
                                                         alt="Photo" 
                                                         class="rounded-circle me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-user-md text-white fa-lg"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h6>
                                                    @if($doctor->grade)
                                                        <small class="text-muted">{{ $doctor->grade->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-envelope text-muted me-2"></i>
                                                    <small>{{ $doctor->email }}</small>
                                                </div>
                                                @if($doctor->phone_number)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-phone text-muted me-2"></i>
                                                        <small>{{ $doctor->phone_number }}</small>
                                                    </div>
                                                @endif
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-stethoscope text-muted me-2"></i>
                                                    <small>{{ $doctor->service->name ?? 'Service non assigné' }}</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Statistiques du médecin -->
                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <div class="border-end">
                                                        <h6 class="mb-1 text-primary">
                                                            @php
                                                                $todayAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
                                                                    ->whereDate('appointment_date', now()->toDateString())
                                                                    ->count();
                                                            @endphp
                                                            {{ $todayAppointments }}
                                                        </h6>
                                                        <small class="text-muted">RDV aujourd'hui</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="border-end">
                                                        <h6 class="mb-1 text-success">
                                                            @php
                                                                $weekAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
                                                                    ->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
                                                                    ->count();
                                                            @endphp
                                                            {{ $weekAppointments }}
                                                        </h6>
                                                        <small class="text-muted">Cette semaine</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <h6 class="mb-1 text-info">
                                                        @php
                                                            $totalAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)->count();
                                                        @endphp
                                                        {{ $totalAppointments }}
                                                    </h6>
                                                    <small class="text-muted">Total RDV</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm flex-fill" 
                                                        onclick="viewDoctor({{ $doctor->id }})" 
                                                        title="Voir le profil">
                                                    <i class="fas fa-eye me-1"></i>Profil
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm flex-fill" 
                                                        onclick="viewSchedule({{ $doctor->id }})" 
                                                        title="Voir le planning">
                                                    <i class="fas fa-calendar me-1"></i>Planning
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm flex-fill" 
                                                        onclick="viewAvailability({{ $doctor->id }})" 
                                                        title="Voir les disponibilités">
                                                    <i class="fas fa-clock me-1"></i>Disponibilités
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun médecin trouvé</h5>
                            <p class="text-muted">Aucun médecin n'est assigné à votre service pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails du médecin -->
<div class="modal fade" id="doctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-md me-2"></i>Détails du Médecin
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="doctorDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="viewScheduleFromModal()">
                    <i class="fas fa-calendar me-1"></i>Voir le Planning
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.doctor-card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e3e6f0;
}

.doctor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}
</style>
@endpush

@push('scripts')
<script>
let selectedDoctorId = null;

// Voir les détails d'un médecin
function viewDoctor(doctorId) {
    selectedDoctorId = doctorId;
    
    document.getElementById('doctorDetails').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('doctorModal'));
    modal.show();
    
    // Simuler le chargement des détails (à remplacer par un appel AJAX réel)
    setTimeout(() => {
        document.getElementById('doctorDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user-md me-2"></i>Informations professionnelles</h6>
                    <div class="mb-3">
                        <strong>Nom complet:</strong><br>
                        Dr. [Nom du médecin]
                    </div>
                    <div class="mb-3">
                        <strong>Spécialité:</strong><br>
                        [Spécialité du médecin]
                    </div>
                    <div class="mb-3">
                        <strong>Service:</strong><br>
                        [Nom du service]
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-calendar me-2"></i>Statistiques</h6>
                    <div class="mb-3">
                        <strong>RDV aujourd'hui:</strong><br>
                        [Nombre de RDV aujourd'hui]
                    </div>
                    <div class="mb-3">
                        <strong>RDV cette semaine:</strong><br>
                        [Nombre de RDV cette semaine]
                    </div>
                    <div class="mb-3">
                        <strong>Total RDV:</strong><br>
                        [Nombre total de RDV]
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

// Voir le planning d'un médecin
function viewSchedule(doctorId) {
    selectedDoctorId = doctorId;
    window.location.href = `{{ route('secretary.doctors.schedule') }}?doctor=${doctorId}`;
}

// Voir le planning depuis le modal
function viewScheduleFromModal() {
    if (selectedDoctorId) {
        viewSchedule(selectedDoctorId);
    }
}

// Voir les disponibilités d'un médecin
function viewAvailability(doctorId) {
    window.location.href = `{{ route('secretary.doctors.availability') }}?doctor=${doctorId}`;
}
</script>
@endpush
