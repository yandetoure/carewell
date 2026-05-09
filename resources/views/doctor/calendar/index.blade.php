@extends('layouts.doctor')

@section('title', 'Calendrier - Docteur')
@section('page-title', 'Mon Calendrier')
@section('page-subtitle', 'Gérez vos disponibilités et planifiez vos absences en toute simplicité')
@section('user-role', 'Médecin')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistiques & Actions -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-xs font-weight-bold text-uppercase text-success mb-1">Disponibilités</p>
                            <h3 class="font-weight-bolder mb-0">{{ $totalAvailabilities }}</h3>
                        </div>
                        <div class="icon-shape bg-gradient-success shadow-success text-center border-radius-md">
                            <i class="fas fa-calendar-check text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-xs font-weight-bold text-uppercase text-warning mb-1">Absences Totales</p>
                            <h3 class="font-weight-bolder mb-0">{{ $totalAbsences }}</h3>
                        </div>
                        <div class="icon-shape bg-gradient-warning shadow-warning text-center border-radius-md">
                            <i class="fas fa-calendar-times text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-xs font-weight-bold text-uppercase text-info mb-1">Absences à venir</p>
                            <h3 class="font-weight-bolder mb-0">{{ $upcomingAbsences }}</h3>
                        </div>
                        <div class="icon-shape bg-gradient-info shadow-info text-center border-radius-md">
                            <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card action-card h-100 border-dashed border-2 border-primary bg-light-primary">
                <div class="card-body d-flex flex-column justify-content-center align-items-center py-2">
                    <div class="btn-group w-100 mb-2">
                        <a href="{{ route('doctor.availability.create') }}" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-plus me-1"></i> Dispo.
                        </a>
                        <a href="{{ route('doctor.calendar.create-absence') }}" class="btn btn-outline-warning btn-sm flex-grow-1">
                            <i class="fas fa-calendar-minus me-1"></i> Absence
                        </a>
                    </div>
                    <button type="button" class="btn btn-white btn-sm w-100" onclick="refreshCalendar()">
                        <i class="fas fa-sync-alt me-1"></i> Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier Main Section -->
    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-lg calendar-main-card">
                <div class="card-header pb-0 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bolder">Planning Interactif</h5>
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge bg-light text-dark border">
                                <span id="calendar-view-title" class="font-weight-bold text-primary"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="fullCalendar" class="min-height-600"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white pb-0">
                    <h6 class="font-weight-bolder mb-0">Légende & Infos</h6>
                </div>
                <div class="card-body p-3">
                    <div class="legend-section mb-4">
                        <p class="text-xs text-uppercase text-muted font-weight-bold mb-3">Types d'événements</p>
                        <div class="d-flex align-items-center mb-3">
                            <span class="legend-dot bg-success"></span>
                            <div class="ms-3">
                                <h6 class="text-sm mb-0">Disponibilité</h6>
                                <p class="text-xs text-muted mb-0">Créneau de consultation</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="legend-dot bg-warning"></span>
                            <div class="ms-3">
                                <h6 class="text-sm mb-0">Congé</h6>
                                <p class="text-xs text-muted mb-0">Absence planifiée</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="legend-dot bg-info"></span>
                            <div class="ms-3">
                                <h6 class="text-sm mb-0">Formation</h6>
                                <p class="text-xs text-muted mb-0">Séminaire ou cours</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="legend-dot bg-danger"></span>
                            <div class="ms-3">
                                <h6 class="text-sm mb-0">Maladie</h6>
                                <p class="text-xs text-muted mb-0">Arrêt maladie</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="legend-dot bg-secondary"></span>
                            <div class="ms-3">
                                <h6 class="text-sm mb-0">Personnel</h6>
                                <p class="text-xs text-muted mb-0">Affaires privées</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="legend-dot bg-purple"></span>
                            <div class="ms-3">
                                <h6 class="text-sm mb-0">Autre</h6>
                                <p class="text-xs text-muted mb-0">Motifs divers</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-alert p-3 bg-light-info border-radius-md border-1 border-info">
                        <div class="d-flex">
                            <i class="fas fa-info-circle text-info mt-1"></i>
                            <div class="ms-3">
                                <p class="text-xs mb-0">
                                    Cliquez sur un événement pour voir les détails ou le modifier. Vous pouvez aussi glisser les événements pour les replanifier.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Événement -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-xl border-radius-lg">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="icon-shape icon-sm bg-gradient-primary shadow text-center border-radius-md me-3">
                        <i id="modalEventIcon" class="fas fa-calendar text-white opacity-10"></i>
                    </div>
                    <h5 class="modal-title font-weight-bolder mb-0" id="eventTitle">Détails de l'événement</h5>
                </div>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                <div id="eventDetails">
                    <div class="row mb-3">
                        <div class="col-4 text-muted text-xs font-weight-bold">TYPE</div>
                        <div class="col-8 text-sm font-weight-bolder" id="detailType">-</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 text-muted text-xs font-weight-bold">DATE & HEURE</div>
                        <div class="col-8 text-sm font-weight-bolder" id="detailTime">-</div>
                    </div>
                    <div class="row mb-3" id="detailServiceRow">
                        <div class="col-4 text-muted text-xs font-weight-bold">SERVICE</div>
                        <div class="col-8 text-sm font-weight-bolder" id="detailService">-</div>
                    </div>
                    <div class="row mb-3" id="detailDurationRow">
                        <div class="col-4 text-muted text-xs font-weight-bold">DURÉE RDV</div>
                        <div class="col-8 text-sm font-weight-bolder" id="detailDuration">-</div>
                    </div>
                    <div class="row mb-0" id="detailStatusRow">
                        <div class="col-4 text-muted text-xs font-weight-bold">STATUT</div>
                        <div class="col-8" id="detailStatus">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light btn-sm mb-0" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="editEventBtn" class="btn btn-primary btn-sm mb-0">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<style>
    /* Premium Stats Cards */
    .stat-card {
        border: none;
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        background-position: 50%;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-gradient-success { background: linear-gradient(310deg, #17ad37, #98ec2d); }
    .bg-gradient-warning { background: linear-gradient(310deg, #f53939, #fbcf33); }
    .bg-gradient-info { background: linear-gradient(310deg, #2152ff, #21d4fd); }
    .bg-gradient-primary { background: linear-gradient(310deg, #7928ca, #ff0080); }
    
    .shadow-success { box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(23, 173, 55, 0.4); }
    .shadow-warning { box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(245, 57, 57, 0.4); }
    .shadow-info { box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(33, 82, 255, 0.4); }
    
    .text-xs { font-size: 0.75rem; }
    .font-weight-bolder { font-weight: 700 !important; }
    
    /* Calendar Styling */
    .calendar-main-card {
        border-radius: 1.25rem;
        overflow: hidden;
    }
    .min-height-600 { min-height: 700px; }
    
    /* FullCalendar Overrides */
    .fc { font-family: 'Inter', sans-serif; --fc-border-color: #f0f2f5; }
    .fc .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 700; color: #344767; }
    .fc .fc-button { 
        background: #fff; border: 1px solid #d2d6da; color: #344767; 
        font-weight: 600; text-transform: capitalize; padding: 0.5rem 1rem;
        border-radius: 0.5rem !important; transition: all 0.2s ease;
    }
    .fc .fc-button:hover { background: #f8f9fa !important; color: #344767 !important; border-color: #d2d6da !important; }
    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active { 
        background-color: #344767 !important; color: #fff !important; border-color: #344767 !important;
    }
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #f0f2f5 !important; }
    .fc .fc-daygrid-day.fc-day-today { background-color: rgba(33, 82, 255, 0.05) !important; }
    .fc .fc-col-header-cell { background: #f8f9fa; padding: 10px 0; font-size: 0.85rem; font-weight: 600; color: #67748e; }
    
    /* Legend Dots */
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    .bg-purple { background-color: #6f42c1 !important; }
    .bg-light-info { background-color: #e8f2ff !important; }
    .bg-light-primary { background-color: rgba(121, 40, 202, 0.05) !important; }
    
    /* Event Badges */
    .fc-event { border: none !important; border-radius: 4px !important; padding: 2px 4px !important; font-size: 0.8rem !important; }
    .fc-event-main { padding-left: 2px; }
    
    /* Animations */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .stat-card { animation: fadeIn 0.5s ease-out forwards; }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('fullCalendar');
        const eventDetailModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/doctor/calendar/data?start=${fetchInfo.startStr.split('T')[0]}&end=${fetchInfo.endStr.split('T')[0]}`)
                    .then(response => response.json())
                    .then(data => {
                        const events = [];
                        
                        // Formater les disponibilités
                        data.availabilities.forEach(avail => {
                            events.push({
                                id: avail.id,
                                title: avail.title.replace('Disponible: ', ''),
                                start: avail.start,
                                end: avail.end,
                                backgroundColor: '#2dce89',
                                borderColor: '#2dce89',
                                extendedProps: {
                                    type: 'availability',
                                    time: avail.time,
                                    duration: avail.duration,
                                    service: avail.title.replace('Disponible: ', '')
                                }
                            });
                        });
                        
                        // Formater les absences
                        data.absences.forEach(abs => {
                            events.push({
                                id: abs.id,
                                title: abs.title,
                                start: abs.start,
                                end: abs.end,
                                backgroundColor: abs.color,
                                borderColor: abs.color,
                                extendedProps: {
                                    type: 'absence',
                                    status: abs.status,
                                    isFullDay: abs.is_full_day
                                }
                            });
                        });
                        
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        failureCallback(error);
                    });
            },
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                document.getElementById('eventTitle').textContent = event.title;
                document.getElementById('detailType').textContent = props.type === 'availability' ? 'DISPONIBILITÉ' : 'ABSENCE';
                
                // Formater l'heure
                let timeStr = FullCalendar.formatDate(event.start, {
                    month: 'long',
                    year: 'numeric',
                    day: 'numeric',
                    locale: 'fr'
                });
                
                if (props.type === 'availability') {
                    timeStr += ` (${props.time})`;
                    document.getElementById('detailServiceRow').style.display = 'flex';
                    document.getElementById('detailDurationRow').style.display = 'flex';
                    document.getElementById('detailStatusRow').style.display = 'none';
                    document.getElementById('detailService').textContent = props.service;
                    document.getElementById('detailDuration').textContent = props.duration;
                    document.getElementById('modalEventIcon').className = 'fas fa-calendar-check text-white opacity-10';
                    document.getElementById('editEventBtn').href = `/doctor/availability/${event.id.replace('avail_', '')}/edit`;
                } else {
                    document.getElementById('detailServiceRow').style.display = 'none';
                    document.getElementById('detailDurationRow').style.display = 'none';
                    document.getElementById('detailStatusRow').style.display = 'flex';
                    document.getElementById('detailStatus').innerHTML = `<span class="badge bg-light text-dark border">${props.status.toUpperCase()}</span>`;
                    document.getElementById('modalEventIcon').className = 'fas fa-calendar-times text-white opacity-10';
                    document.getElementById('editEventBtn').href = `/doctor/calendar/absence/${event.id.replace('absence_', '')}/edit`;
                }
                
                document.getElementById('detailTime').textContent = timeStr;
                eventDetailModal.show();
            },
            eventMouseEnter: function(info) {
                info.el.style.cursor = 'pointer';
            }
        });
        
        calendar.render();
        
        window.refreshCalendar = function() {
            calendar.refetchEvents();
        };
    });
</script>
@endpush
