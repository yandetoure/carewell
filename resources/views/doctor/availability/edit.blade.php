@extends('layouts.doctor')

@section('title', 'Modifier Créneau - Docteur')
@section('page-title', 'Modifier Créneau de Disponibilité')
@section('page-subtitle', 'Modifier un créneau de disponibilité existant')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-edit me-2"></i>Modifier le créneau du {{ \Carbon\Carbon::parse($availability->available_date)->format('d/m/Y') }}
                            </h4>
                            <p class="text-muted mb-0">{{ $availability->service->name ?? 'Service non spécifié' }} • {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('doctor.availability') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-edit me-2"></i>Informations du créneau
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.availability.update', $availability) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Service -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-stethoscope me-2"></i>Service médical
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                                    <select class="form-select @error('service_id') is-invalid @enderror" 
                                            id="service_id" name="service_id" required>
                                        <option value="">Sélectionner un service...</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id', $availability->service_id) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }} - {{ number_format($service->price, 0, ',', ' ') }} FCFA
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Date et heures -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Date et horaires
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="available_date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('available_date') is-invalid @enderror" 
                                           id="available_date" name="available_date" 
                                           value="{{ old('available_date', $availability->available_date) }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('available_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="appointment_duration" class="form-label">Durée des RDV (minutes) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('appointment_duration') is-invalid @enderror" 
                                            id="appointment_duration" name="appointment_duration" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="15" {{ old('appointment_duration', $availability->appointment_duration) == '15' ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ old('appointment_duration', $availability->appointment_duration) == '30' ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ old('appointment_duration', $availability->appointment_duration) == '45' ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ old('appointment_duration', $availability->appointment_duration) == '60' ? 'selected' : '' }}>1 heure</option>
                                        <option value="90" {{ old('appointment_duration', $availability->appointment_duration) == '90' ? 'selected' : '' }}>1h30</option>
                                        <option value="120" {{ old('appointment_duration', $availability->appointment_duration) == '120' ? 'selected' : '' }}>2 heures</option>
                                    </select>
                                    @error('appointment_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_time" class="form-label">Heure de début <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" 
                                           value="{{ old('start_time', $availability->start_time) }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_time" class="form-label">Heure de fin <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" 
                                           value="{{ old('end_time', $availability->end_time) }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Récurrence -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-repeat me-2"></i>Récurrence
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="recurrence_type" class="form-label">Type de récurrence</label>
                                    <select class="form-select @error('recurrence_type') is-invalid @enderror" 
                                            id="recurrence_type" name="recurrence_type">
                                        <option value="none" {{ old('recurrence_type', $availability->recurrence_type) == 'none' ? 'selected' : '' }}>Aucune récurrence</option>
                                        <option value="daily" {{ old('recurrence_type', $availability->recurrence_type) == 'daily' ? 'selected' : '' }}>Quotidienne</option>
                                        <option value="weekly" {{ old('recurrence_type', $availability->recurrence_type) == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                    </select>
                                    @error('recurrence_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('doctor.availability') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations actuelles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <strong>Service:</strong>
                            <p class="mb-0">{{ $availability->service->name ?? 'Service non spécifié' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Date:</strong>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($availability->available_date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Horaires:</strong>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Durée RDV:</strong>
                            <p class="mb-0">{{ $availability->appointment_duration }} minutes</p>
                        </div>
                        <div class="col-12">
                            <strong>Récurrence:</strong>
                            <p class="mb-0">{{ ucfirst($availability->recurrence_type) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>Calcul automatique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nombre de RDV possibles</label>
                        <div class="input-group">
                            <span class="input-group-text" id="slots-count">0</span>
                            <span class="input-group-text">créneaux</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Durée totale</label>
                        <div class="input-group">
                            <span class="input-group-text" id="total-duration">0</span>
                            <span class="input-group-text">heures</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const duration = document.getElementById('appointment_duration');
    const slotsCount = document.getElementById('slots-count');
    const totalDuration = document.getElementById('total-duration');

    function calculateSlots() {
        if (startTime.value && endTime.value && duration.value) {
            const start = new Date('2000-01-01 ' + startTime.value);
            const end = new Date('2000-01-01 ' + endTime.value);
            const appointmentDuration = parseInt(duration.value);
            
            if (end > start) {
                const totalMinutes = (end - start) / (1000 * 60);
                const slots = Math.floor(totalMinutes / appointmentDuration);
                const totalHours = (slots * appointmentDuration) / 60;
                
                slotsCount.textContent = slots;
                totalDuration.textContent = totalHours.toFixed(1);
            } else {
                slotsCount.textContent = '0';
                totalDuration.textContent = '0';
            }
        }
    }

    // Calculer au chargement
    calculateSlots();

    startTime.addEventListener('change', calculateSlots);
    endTime.addEventListener('change', calculateSlots);
    duration.addEventListener('change', calculateSlots);

    // Validation en temps réel
    startTime.addEventListener('change', function() {
        if (endTime.value && this.value >= endTime.value) {
            alert('L\'heure de début doit être antérieure à l\'heure de fin.');
            this.value = '';
        }
    });

    endTime.addEventListener('change', function() {
        if (startTime.value && this.value <= startTime.value) {
            alert('L\'heure de fin doit être postérieure à l\'heure de début.');
            this.value = '';
        }
    });

    // Validation des heures de travail (8h - 18h)
    [startTime, endTime].forEach(input => {
        input.addEventListener('change', function() {
            const hour = parseInt(this.value.split(':')[0]);
            if (hour < 8 || hour > 18) {
                alert('Les créneaux doivent être entre 8h et 18h.');
                this.value = '';
            }
        });
    });

    // Confirmation avant de quitter si des modifications ont été faites
    let formChanged = false;
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    form.addEventListener('submit', function() {
        formChanged = false;
    });
});
</script>
@endpush

