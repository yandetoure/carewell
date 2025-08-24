@extends('layouts.app')

@section('title', 'Prendre un Rendez-vous')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Prendre un Rendez-vous
                        </h5>
                        <a href="{{ route('appointments') }}" class="btn btn-outline-secondary">
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

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        
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
                                                    {{ old('service_id') == $service->id ? 'selected' : '' }}
                                                    data-price="{{ $service->price }}">
                                                {{ $service->name }} - {{ number_format($service->price, 0, ',', ' ') }} FCFA
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
                                           value="{{ old('appointment_date') }}" 
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
                                                        {{ old('appointment_time') == sprintf('%02d:%s', $hour, $minute) ? 'selected' : '' }}>
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
                                    <label for="reason" class="form-label">Motif du rendez-vous</label>
                                    <input type="text" 
                                           class="form-control @error('reason') is-invalid @enderror" 
                                           id="reason" 
                                           name="reason" 
                                           value="{{ old('reason') }}" 
                                           placeholder="Raison de la consultation">
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="symptoms" class="form-label">Symptômes</label>
                                    <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                              id="symptoms" 
                                              name="symptoms" 
                                              rows="3" 
                                              placeholder="Décrivez vos symptômes...">{{ old('symptoms') }}</textarea>
                                    @error('symptoms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Prix du service :</strong> 
                                        <span id="service-price">Sélectionnez un service pour voir le prix</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Informations importantes
                                        </h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Présentez-vous 10 minutes avant l'heure</li>
                                            <li>Apportez vos documents médicaux</li>
                                            <li>Annulez au moins 24h à l'avance</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-clock me-2"></i>
                                            Prochaines disponibilités
                                        </h6>
                                        <p class="mb-0">Nous vous confirmerons le rendez-vous par email et SMS.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('appointments') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Confirmer le Rendez-vous
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
    const priceDisplay = document.getElementById('service-price');
    
    if (price) {
        // Formater le prix en FCFA
        const formattedPrice = new Intl.NumberFormat('fr-FR').format(price);
        priceDisplay.textContent = formattedPrice + ' FCFA';
        priceDisplay.style.color = '#28a745';
        priceDisplay.style.fontWeight = 'bold';
    } else {
        priceDisplay.textContent = 'Sélectionnez un service pour voir le prix';
        priceDisplay.style.color = '#6c757d';
        priceDisplay.style.fontWeight = 'normal';
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

// Validation de l'heure (pas de rendez-vous avant 8h et après 18h)
document.getElementById('appointment_time').addEventListener('change', function() {
    const selectedTime = this.value;
    const hour = parseInt(selectedTime.split(':')[0]);
    
    if (hour < 8 || hour > 18) {
        alert('Les rendez-vous ne sont disponibles qu\'entre 8h et 18h. Veuillez choisir une autre heure.');
        this.value = '';
    }
});
</script>
@endsection
