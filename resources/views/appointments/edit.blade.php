@extends('layouts.app')

@section('title', 'Modifier le Rendez-vous')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Modifier le Rendez-vous
                        </h5>
                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">Service *</label>
                                    <select class="form-select @error('service_id') is-invalid @enderror" 
                                            id="service_id" 
                                            name="service_id" 
                                            required>
                                        <option value="">Sélectionnez un service</option>
                                        @foreach(\App\Models\Service::all() as $service)
                                            <option value="{{ $service->id }}" 
                                                    {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}
                                                    data-price="{{ $service->price }}">
                                                {{ $service->name }} - {{ number_format($service->price, 2) }} €
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Date *</label>
                                    <input type="date" 
                                           class="form-control @error('appointment_date') is-invalid @enderror" 
                                           id="appointment_date" 
                                           name="appointment_date" 
                                           value="{{ old('appointment_date', $appointment->appointment_date) }}" 
                                           min="{{ date('Y-m-d') }}" 
                                           required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Heure *</label>
                                    <select class="form-select @error('appointment_time') is-invalid @enderror" 
                                            id="appointment_time" 
                                            name="appointment_time" 
                                            required>
                                        <option value="">Sélectionnez une heure</option>
                                        @for($hour = 8; $hour <= 18; $hour++)
                                            @foreach(['00', '30'] as $minute)
                                                <option value="{{ sprintf('%02d:%s', $hour, $minute) }}"
                                                        {{ old('appointment_time', $appointment->appointment_time) == sprintf('%02d:%s', $hour, $minute) ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:%s', $hour, $minute) }}
                                                </option>
                                            @endforeach
                                        @endfor
                                    </select>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lieu</label>
                                    <input type="text" 
                                           class="form-control @error('location') is-invalid @enderror" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location', $appointment->location) }}" 
                                           placeholder="Adresse ou nom du cabinet">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="appointment_type" class="form-label">Type de rendez-vous</label>
                                    <select class="form-select @error('appointment_type') is-invalid @enderror" 
                                            id="appointment_type" 
                                            name="appointment_type">
                                        <option value="standard" {{ old('appointment_type', $appointment->appointment_type) == 'standard' ? 'selected' : '' }}>
                                            Standard
                                        </option>
                                        <option value="urgent" {{ old('appointment_type', $appointment->appointment_type) == 'urgent' ? 'selected' : '' }}>
                                            Urgent
                                        </option>
                                        <option value="consultation" {{ old('appointment_type', $appointment->appointment_type) == 'consultation' ? 'selected' : '' }}>
                                            Consultation
                                        </option>
                                        <option value="suivi" {{ old('appointment_type', $appointment->appointment_type) == 'suivi' ? 'selected' : '' }}>
                                            Suivi
                                        </option>
                                    </select>
                                    @error('appointment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Informations supplémentaires, symptômes, questions...">{{ old('notes', $appointment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_urgent" 
                                               name="is_urgent" 
                                               value="1" 
                                               {{ old('is_urgent', $appointment->is_urgent) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_urgent">
                                            Rendez-vous urgent
                                        </label>
                                    </div>
                                    <div class="form-text">Cochez cette case si vous avez besoin d'un rendez-vous en urgence.</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Current Appointment Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Informations actuelles
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">Statut actuel</small>
                                                <div class="fw-bold">
                                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Créé le</small>
                                                <div class="fw-bold">{{ $appointment->created_at->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Dernière modification</small>
                                                <div class="fw-bold">{{ $appointment->updated_at->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Numéro</small>
                                                <div class="fw-bold">#{{ $appointment->id }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('service_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.dataset.price;
    
    if (price) {
        console.log('Prix du service:', price);
    }
});

// Validation de la date (pas de rendez-vous le dimanche)
document.getElementById('appointment_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const dayOfWeek = selectedDate.getDay();
    
    if (dayOfWeek === 0) { // 0 = dimanche
        alert('Les rendez-vous ne sont pas disponibles le dimanche. Veuillez choisir une autre date.');
        this.value = '';
    }
});

// Vérifier si la date sélectionnée est dans le passé
document.getElementById('appointment_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        alert('Vous ne pouvez pas sélectionner une date dans le passé.');
        this.value = '';
    }
});
</script>
@endsection
