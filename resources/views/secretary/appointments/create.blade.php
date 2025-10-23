@extends('layouts.secretary')

@section('title', 'Nouveau Rendez-vous - Secrétariat')
@section('page-title', 'Nouveau Rendez-vous')
@section('page-subtitle', 'Créer un nouveau rendez-vous pour un patient')
@section('user-role', 'Secrétaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Créer un nouveau rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Service médical</label>
                                    <div class="form-control-plaintext bg-light p-2 rounded">
                                        <i class="fas fa-stethoscope text-primary me-2"></i>
                                        <strong>{{ $service->name ?? 'Service non assigné' }}</strong>
                                        @if($service)
                                            <span class="text-muted">- {{ number_format($service->price, 2) }} €</span>
                                        @endif
                                    </div>
                                    <input type="hidden" name="service_id" value="{{ $service->id ?? '' }}">
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient *</label>
                                    <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                        <option value="">Sélectionnez un patient</option>
                                        @foreach($patients ?? [] as $patient)
                                            <option value="{{ $patient->id }}">
                                                {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Date du rendez-vous *</label>
                                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                           id="appointment_date" name="appointment_date" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Heure du rendez-vous *</label>
                                    <select class="form-select @error('appointment_time') is-invalid @enderror" id="appointment_time" name="appointment_time" required>
                                        <option value="">Sélectionnez une heure</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11:00</option>
                                        <option value="11:30">11:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:30">15:30</option>
                                        <option value="16:00">16:00</option>
                                        <option value="16:30">16:30</option>
                                        <option value="17:00">17:00</option>
                                        <option value="17:30">17:30</option>
                                    </select>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">Médecin du service</label>
                                    <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id">
                                        <option value="">Sélectionnez un médecin</option>
                                        @foreach($doctors ?? [] as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                @if($doctor->grade)
                                                    - {{ $doctor->grade->name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Sélectionnez un médecin de votre service pour ce rendez-vous</small>
                                </div>

                                <div class="mb-3">
                                    <label for="reason" class="form-label">Motif de consultation</label>
                                    <input type="text" class="form-control @error('reason') is-invalid @enderror" 
                                           id="reason" name="reason" 
                                           placeholder="Motif de la consultation...">
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="symptoms" class="form-label">Symptômes</label>
                                    <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                              id="symptoms" name="symptoms" rows="3" 
                                              placeholder="Décrivez les symptômes du patient..."></textarea>
                                    @error('symptoms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes supplémentaires</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Notes internes..."></textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut initial</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="pending">En attente</option>
                                        <option value="confirmed">Confirmé</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Information :</strong> Le rendez-vous sera créé pour le service {{ $service->name ?? 'non assigné' }} et le patient sera notifié par email. 
                                    Vous pouvez sélectionner un médecin de votre service ou laisser le système en choisir un automatiquement.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('secretary.appointments') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Créer le rendez-vous
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation de la date
    const dateInput = document.getElementById('appointment_date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    // Validation de l'heure selon la date
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        const timeSelect = document.getElementById('appointment_time');
        
        // Réinitialiser les heures disponibles
        timeSelect.innerHTML = '<option value="">Sélectionnez une heure</option>';
        
        if (selectedDate === today) {
            // Pour aujourd'hui, ne montrer que les heures futures
            const currentHour = new Date().getHours();
            const times = [
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
            ];
            
            times.forEach(time => {
                const hour = parseInt(time.split(':')[0]);
                if (hour > currentHour) {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                }
            });
        } else {
            // Pour les autres jours, montrer toutes les heures
            const times = [
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
            ];
            
            times.forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time;
                timeSelect.appendChild(option);
            });
        }
    });
});
</script>
@endsection
