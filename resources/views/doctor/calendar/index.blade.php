@extends('layouts.doctor')

@section('title', 'Calendrier - Docteur')
@section('page-title', 'Mon Calendrier')
@section('page-subtitle', 'Visualisez vos disponibilités et planifiez vos absences')
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

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAvailabilities }}</h4>
                            <p class="text-muted mb-0">Disponibilités</p>
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
                            <i class="fas fa-calendar-times text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $totalAbsences }}</h4>
                            <p class="text-muted mb-0">Absences</p>
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
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ $upcomingAbsences }}</h4>
                            <p class="text-muted mb-0">À venir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-1">{{ now()->format('M Y') }}</h4>
                            <p class="text-muted mb-0">Mois actuel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Actions rapides
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('doctor.availability.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Nouvelle disponibilité
                            </a>
                            <a href="{{ route('doctor.calendar.create-absence') }}" class="btn btn-warning">
                                <i class="fas fa-calendar-times me-2"></i>Nouvelle absence
                            </a>
                            <button type="button" class="btn btn-info" onclick="refreshCalendar()">
                                <i class="fas fa-sync me-2"></i>Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar me-2"></i>Calendrier des disponibilités et absences
                        </h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="previousMonth()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="current-month" class="btn btn-sm btn-outline-secondary">{{ now()->format('F Y') }}</span>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="nextMonth()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar-container">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-3 text-muted">Chargement du calendrier...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Légende
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Disponibilités</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-success me-2"></div>
                                <span>Créneaux disponibles pour les rendez-vous</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Absences</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-warning me-2"></div>
                                <span>Congés</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-info me-2"></div>
                                <span>Formations</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-danger me-2"></div>
                                <span>Maladie</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-secondary me-2"></div>
                                <span>Personnel</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-purple me-2"></div>
                                <span>Autre</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Détails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <div id="eventModalActions">
                    <!-- Actions dynamiques -->
                </div>
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

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

#calendar-container {
    min-height: 600px;
}

.calendar-day {
    min-height: 120px;
    border: 1px solid #dee2e6;
    padding: 8px;
    position: relative;
}

.calendar-day:hover {
    background-color: #f8f9fa;
}

.calendar-day.today {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    color: #6c757d;
}

.calendar-event {
    font-size: 0.75rem;
    padding: 2px 4px;
    margin: 1px 0;
    border-radius: 3px;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.calendar-event:hover {
    opacity: 0.8;
}

.calendar-event.availability {
    background-color: #28a745;
    color: white;
}

.calendar-event.absence {
    background-color: #ffc107;
    color: #212529;
}

.calendar-event.absence.formation {
    background-color: #17a2b8;
    color: white;
}

.calendar-event.absence.maladie {
    background-color: #dc3545;
    color: white;
}

.calendar-event.absence.personnel {
    background-color: #6c757d;
    color: white;
}

.calendar-event.absence.autre {
    background-color: #6f42c1;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
let currentDate = new Date();
let calendarData = {};

// Charger les données du calendrier
function loadCalendarData(year, month) {
    const startDate = new Date(year, month, 1);
    const endDate = new Date(year, month + 1, 0);
    
    fetch(`/doctor/calendar/data?start=${startDate.toISOString().split('T')[0]}&end=${endDate.toISOString().split('T')[0]}`)
        .then(response => response.json())
        .then(data => {
            calendarData = data;
            renderCalendar(year, month);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des données:', error);
            document.getElementById('calendar-container').innerHTML = 
                '<div class="alert alert-danger">Erreur lors du chargement du calendrier</div>';
        });
}

// Rendre le calendrier
function renderCalendar(year, month) {
    const container = document.getElementById('calendar-container');
    const monthNames = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    
    document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    let calendarHTML = `
        <div class="calendar-header">
            <div class="row">
                <div class="col text-center"><strong>Dim</strong></div>
                <div class="col text-center"><strong>Lun</strong></div>
                <div class="col text-center"><strong>Mar</strong></div>
                <div class="col text-center"><strong>Mer</strong></div>
                <div class="col text-center"><strong>Jeu</strong></div>
                <div class="col text-center"><strong>Ven</strong></div>
                <div class="col text-center"><strong>Sam</strong></div>
            </div>
        </div>
        <div class="calendar-body">
    `;
    
    const today = new Date();
    const currentDate = new Date(startDate);
    
    for (let week = 0; week < 6; week++) {
        calendarHTML += '<div class="row">';
        
        for (let day = 0; day < 7; day++) {
            const isCurrentMonth = currentDate.getMonth() === month;
            const isToday = currentDate.toDateString() === today.toDateString();
            
            let dayClass = 'calendar-day col';
            if (!isCurrentMonth) dayClass += ' other-month';
            if (isToday) dayClass += ' today';
            
            calendarHTML += `<div class="${dayClass}">`;
            calendarHTML += `<div class="day-number">${currentDate.getDate()}</div>`;
            
            // Ajouter les événements
            const dateStr = currentDate.toISOString().split('T')[0];
            const dayEvents = getEventsForDate(dateStr);
            
            dayEvents.forEach(event => {
                calendarHTML += `<div class="calendar-event ${event.type} ${event.type === 'absence' ? event.status : ''}" onclick="showEventDetails('${event.id}')">${event.title}</div>`;
            });
            
            calendarHTML += '</div>';
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        calendarHTML += '</div>';
    }
    
    calendarHTML += '</div>';
    container.innerHTML = calendarHTML;
}

// Obtenir les événements pour une date
function getEventsForDate(date) {
    const events = [];
    
    // Ajouter les disponibilités
    if (calendarData.availabilities) {
        calendarData.availabilities.forEach(availability => {
            if (availability.start === date) {
                events.push(availability);
            }
        });
    }
    
    // Ajouter les absences
    if (calendarData.absences) {
        calendarData.absences.forEach(absence => {
            if (absence.start <= date && absence.end > date) {
                events.push(absence);
            }
        });
    }
    
    return events;
}

// Afficher les détails d'un événement
function showEventDetails(eventId) {
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    const modalTitle = document.getElementById('eventModalTitle');
    const modalBody = document.getElementById('eventModalBody');
    const modalActions = document.getElementById('eventModalActions');
    
    // Trouver l'événement
    let event = null;
    if (eventId.startsWith('avail_')) {
        event = calendarData.availabilities.find(a => a.id === eventId);
    } else if (eventId.startsWith('absence_')) {
        event = calendarData.absences.find(a => a.id === eventId);
    }
    
    if (!event) return;
    
    modalTitle.textContent = event.title;
    
    if (event.type === 'availability') {
        modalBody.innerHTML = `
            <p><strong>Service:</strong> ${event.title.replace('Disponible: ', '')}</p>
            <p><strong>Horaires:</strong> ${event.time}</p>
            <p><strong>Durée RDV:</strong> ${event.duration}</p>
        `;
        modalActions.innerHTML = `
            <a href="/doctor/availability/${event.id.replace('avail_', '')}/edit" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
        `;
    } else if (event.type === 'absence') {
        modalBody.innerHTML = `
            <p><strong>Type:</strong> ${event.title}</p>
            <p><strong>Statut:</strong> ${event.status}</p>
            <p><strong>Jour entier:</strong> ${event.is_full_day ? 'Oui' : 'Non'}</p>
        `;
        modalActions.innerHTML = `
            <a href="/doctor/calendar/absence/${event.id.replace('absence_', '')}/edit" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
        `;
    }
    
    modal.show();
}

// Navigation du calendrier
function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    loadCalendarData(currentDate.getFullYear(), currentDate.getMonth());
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    loadCalendarData(currentDate.getFullYear(), currentDate.getMonth());
}

function refreshCalendar() {
    loadCalendarData(currentDate.getFullYear(), currentDate.getMonth());
}

// Initialiser le calendrier
document.addEventListener('DOMContentLoaded', function() {
    loadCalendarData(currentDate.getFullYear(), currentDate.getMonth());
});
</script>
@endpush
