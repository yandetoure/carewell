<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalFileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ExamPrescriptionController;
use App\Http\Controllers\MedicalFilePrescriptionController;

// Routes publiques
Route::get('/', function () {
    return view('home');
})->name('home');

// Route de test pour les sidebars
Route::get('/test-sidebar', function () {
    return view('test-sidebar');
})->name('test-sidebar');

// Route de test pour l'authentification
Route::get('/test-auth', function () {
    return view('test-auth');
})->name('test-auth');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Routes des services
Route::get('/services', [ServiceController::class, 'index'])->name('services');

// Routes des articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Routes d'authentification
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {

    // Dashboard principal (redirige vers le bon dashboard selon le rôle)
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Rendez-vous
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Dossiers médicaux
    Route::get('/medical-files', [MedicalFileController::class, 'index'])->name('medical-files');
    Route::get('/medical-files/{medicalFile}', [MedicalFileController::class, 'show'])->name('medical-files.show');

    // Prescriptions
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
    Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    // Examens
    Route::get('/exams', [ExamController::class, 'index'])->name('exams');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');

    // Résultats
    Route::get('/results', [ResultController::class, 'index'])->name('results');
    Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');

    // Notes médicales
    Route::get('/notes', [NoteController::class, 'index'])->name('notes');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');

    // Maladies
    Route::get('/diseases', [DiseaseController::class, 'index'])->name('diseases');
    Route::post('/diseases', [DiseaseController::class, 'store'])->name('diseases.store');
    Route::get('/diseases/{disease}', [DiseaseController::class, 'show'])->name('diseases.show');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Disponibilités (pour les médecins)
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::get('/availability/create', [AvailabilityController::class, 'create'])->name('availability.create');
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::get('/availability/{availability}', [AvailabilityController::class, 'show'])->name('availability.show');
    Route::get('/availability/{availability}/edit', [AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability/{availability}', [AvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');

    // Routes pour les médecins uniquement (sans middleware de rôle pour l'instant)
    Route::group(['prefix' => 'doctor'], function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
        Route::get('/appointments', [AppointmentController::class, 'doctorAppointments'])->name('doctor.appointments');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('doctor.appointments.store');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('doctor.appointments.show');
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('doctor.appointments.status');
        Route::get('/appointments/today', [AppointmentController::class, 'doctorTodayAppointments'])->name('doctor.appointments.today');
        Route::get('/appointments/week', [AppointmentController::class, 'doctorWeekAppointments'])->name('doctor.appointments.week');
        Route::get('/patients', [AppointmentController::class, 'getPatientsWithAppointments'])->name('doctor.patients');
        Route::get('/patients/new', [AppointmentController::class, 'createPatient'])->name('doctor.patients.new');
        Route::post('/patients', [AppointmentController::class, 'storePatient'])->name('doctor.patients.store');
        Route::get('/patients/{patient}', [AppointmentController::class, 'showPatient'])->name('doctor.patients.show');
        Route::get('/patients/{patient}/edit', [AppointmentController::class, 'editPatient'])->name('doctor.patients.edit');
        Route::put('/patients/{patient}', [AppointmentController::class, 'updatePatient'])->name('doctor.patients.update');
        Route::get('/patients/{patient}/history', [AppointmentController::class, 'patientHistory'])->name('doctor.patients.history');
        Route::get('/patients/{patient}/appointments', [AppointmentController::class, 'patientAppointments'])->name('doctor.patients.appointments');
        
        // Routes pour la gestion des disponibilités du docteur
        Route::get('/availability', [AvailabilityController::class, 'doctorAvailability'])->name('doctor.availability');
        Route::get('/availability/create', [AvailabilityController::class, 'doctorCreateAvailability'])->name('doctor.availability.create');
        Route::post('/availability', [AvailabilityController::class, 'doctorStoreAvailability'])->name('doctor.availability.store');
        Route::get('/availability/{availability}/edit', [AvailabilityController::class, 'doctorEditAvailability'])->name('doctor.availability.edit');
        Route::put('/availability/{availability}', [AvailabilityController::class, 'doctorUpdateAvailability'])->name('doctor.availability.update');
        Route::delete('/availability/{availability}', [AvailabilityController::class, 'doctorDestroyAvailability'])->name('doctor.availability.destroy');
        
        // Routes pour le calendrier et les absences
        Route::get('/calendar', [App\Http\Controllers\AbsenceController::class, 'calendar'])->name('doctor.calendar');
        Route::get('/calendar/data', [App\Http\Controllers\AbsenceController::class, 'getCalendarData'])->name('doctor.calendar.data');
        Route::get('/calendar/absence/create', [App\Http\Controllers\AbsenceController::class, 'create'])->name('doctor.calendar.create-absence');
        Route::post('/calendar/absence', [App\Http\Controllers\AbsenceController::class, 'store'])->name('doctor.calendar.store-absence');
        Route::get('/calendar/absence/{absence}/edit', [App\Http\Controllers\AbsenceController::class, 'edit'])->name('doctor.calendar.edit-absence');
        Route::put('/calendar/absence/{absence}', [App\Http\Controllers\AbsenceController::class, 'update'])->name('doctor.calendar.update-absence');
        Route::delete('/calendar/absence/{absence}', [App\Http\Controllers\AbsenceController::class, 'destroy'])->name('doctor.calendar.destroy-absence');
        
        Route::get('/stats', [AppointmentController::class, 'doctorStats'])->name('doctor.stats');
        Route::get('/statistics', [AppointmentController::class, 'doctorStatistics'])->name('doctor.statistics');
        Route::get('/prescriptions', [MedicalFilePrescriptionController::class, 'getPrescriptionsByService'])->name('doctor.prescriptions');
        Route::get('/exams', [ExamPrescriptionController::class, 'getExamByService'])->name('doctor.exams');
        Route::get('/results', [ResultController::class, 'doctorResults'])->name('doctor.results');
        Route::get('/results/{result}', [ResultController::class, 'show'])->name('doctor.results.show');
        Route::put('/results/{result}', [ResultController::class, 'update'])->name('doctor.results.update');
        Route::get('/consultations', [AppointmentController::class, 'doctorConsultations'])->name('doctor.consultations');
        Route::get('/medical-files', [MedicalFileController::class, 'doctorMedicalFiles'])->name('doctor.medical-files');
        Route::get('/medical-files/{patient}', [MedicalFileController::class, 'showPatientMedicalFile'])->name('doctor.medical-files.show');
        Route::get('/medical-history', [MedicalFileController::class, 'doctorMedicalHistory'])->name('doctor.medical-history');
        Route::get('/notes', [NoteController::class, 'doctorNotes'])->name('doctor.notes');
        Route::get('/follow-up', [AppointmentController::class, 'doctorFollowUp'])->name('doctor.follow-up');
        Route::get('/messages', [MessageController::class, 'doctorMessages'])->name('doctor.messages');
        Route::get('/messages/create', [MessageController::class, 'createMessage'])->name('doctor.messages.create');
        Route::get('/messages/create/{patient}', [MessageController::class, 'createMessage'])->name('doctor.messages.create.with-patient');
        Route::get('/messages/chat/{userId}', [MessageController::class, 'chatWithUser'])->name('doctor.messages.chat');
        Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('doctor.messages.send');
        Route::get('/notifications', [NotificationController::class, 'doctorNotifications'])->name('doctor.notifications');
        Route::get('/profile', [ProfileController::class, 'doctorProfile'])->name('doctor.profile');
        Route::get('/settings', [ProfileController::class, 'doctorSettings'])->name('doctor.settings');

        // Gestion des prescriptions
        Route::put('/prescriptions/{prescription}/status', [MedicalFilePrescriptionController::class, 'updatePrescriptionStatus'])->name('prescriptions.updateStatus');

        // Gestion des examens
        Route::put('/exams/{exam}/status', [ExamPrescriptionController::class, 'updateExamStatus'])->name('exams.updateStatus');
        Route::get('/exams/{exam}/results', [ExamPrescriptionController::class, 'getExamResult'])->name('exams.getResult');
        Route::post('/exams/{exam}/results', [ExamPrescriptionController::class, 'storeResult'])->name('exams.storeResult');

        // Gestion des dossiers médicaux
        Route::post('/medical-files/{id}/addnote', [MedicalFileController::class, 'addNote'])->name('medical-files.addNote');
        Route::post('/medical-files/{id}/addHistory', [MedicalFileController::class, 'addMedicalHistories'])->name('medical-files.addHistory');
        Route::post('/medical-files/{id}/addprescription', [MedicalFileController::class, 'addPrescription'])->name('medical-files.addPrescription');
        Route::post('/medical-files/{id}/addexam', [MedicalFileController::class, 'addExam'])->name('medical-files.addExam');
        Route::post('/medical-files/{id}/adddisease', [MedicalFileController::class, 'addDisease'])->name('medical-files.addDisease');
        Route::post('/medical-files/{id}/addordonnance', [MedicalFileController::class, 'addOrdonnance'])->name('medical-files.addOrdonnance');
    });

    // Routes pour les patients uniquement (sans middleware de rôle pour l'instant)
    Route::group(['prefix' => 'patient'], function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
        Route::get('/health-summary', [App\Http\Controllers\DashboardController::class, 'patientHealthSummary'])->name('patient.health-summary');
        Route::get('/medical-file', [App\Http\Controllers\DashboardController::class, 'patientMedicalFile'])->name('patient.medical-file');
        Route::get('/prescriptions', [PrescriptionController::class, 'patientPrescriptions'])->name('patient.prescriptions');
        Route::get('/exams', [ExamController::class, 'patientExams'])->name('patient.exams');
        Route::get('/vital-signs', [App\Http\Controllers\DashboardController::class, 'patientVitalSigns'])->name('patient.vital-signs');

        // Rendez-vous pour patients
        Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('patient.appointments');
        Route::get('/appointments/create', [AppointmentController::class, 'patientCreate'])->name('patient.appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'patientStore'])->name('patient.appointments.store');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'patientShow'])->name('patient.appointments.show');
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'patientEdit'])->name('patient.appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'patientUpdate'])->name('patient.appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'patientDestroy'])->name('patient.appointments.destroy');

        // Services pour patients
        Route::get('/services', [ServiceController::class, 'patientIndex'])->name('patient.services');
        Route::get('/services/{service}', [ServiceController::class, 'patientShow'])->name('patient.services.show');

        // Articles pour patients
        Route::get('/articles', [ArticleController::class, 'patientIndex'])->name('patient.articles');
        Route::get('/articles/{article}', [ArticleController::class, 'patientShow'])->name('patient.articles.show');

        // Profil et contact pour patients
        Route::get('/profile', [ProfileController::class, 'show'])->name('patient.profile');
        Route::get('/contact', function() {
            return view('contact');
        })->name('patient.contact');
    });

    // Routes pour les administrateurs uniquement (sans middleware de rôle pour l'instant)
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        // Gestion des utilisateurs
        Route::get('/users', [AuthController::class, 'getUsers'])->name('admin.users');
        Route::post('/users', [AuthController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}', [AuthController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [AuthController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AuthController::class, 'update'])->name('admin.users.update');
        Route::put('/users/{user}/role', [AuthController::class, 'updateRole'])->name('admin.users.role');
        Route::delete('/users/{user}', [AuthController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/services', [ServiceController::class, 'adminIndex'])->name('admin.services');
        Route::get('/services/{service}', [ServiceController::class, 'adminShow'])->name('admin.services.show');
        Route::get('/articles', [ArticleController::class, 'adminIndex'])->name('admin.articles');
        Route::get('/articles/{article}', [ArticleController::class, 'adminShow'])->name('admin.articles.show');
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments');
        Route::get('/statistics', [App\Http\Controllers\DashboardController::class, 'adminStatistics'])->name('admin.statistics');
        // Gestion des rôles et permissions (guard web uniquement)
        Route::get('/roles', [AuthController::class, 'getRoles'])->name('admin.roles');
        Route::post('/roles', [AuthController::class, 'storeRole'])->name('admin.roles.store');
        Route::get('/roles/{role}', [AuthController::class, 'showRole'])->name('admin.roles.show')
            ->where('role', '[0-9]+'); // S'assurer que seul l'ID numérique est accepté
        Route::get('/roles/{role}/edit', [AuthController::class, 'editRole'])->name('admin.roles.edit')
            ->where('role', '[0-9]+');
        Route::put('/roles/{role}', [AuthController::class, 'updateRolePermissions'])->name('admin.roles.update')
            ->where('role', '[0-9]+');
        Route::delete('/roles/{role}', [AuthController::class, 'destroyRole'])->name('admin.roles.destroy')
            ->where('role', '[0-9]+');
        
        // Gestion des permissions
        Route::get('/permissions', [AuthController::class, 'getPermissions'])->name('admin.permissions');
        Route::post('/permissions', [AuthController::class, 'storePermission'])->name('admin.permissions.store');
        Route::put('/permissions/{permission}', [AuthController::class, 'updatePermission'])->name('admin.permissions.update');
        Route::delete('/permissions/{permission}', [AuthController::class, 'destroyPermission'])->name('admin.permissions.destroy');
        
        // Assignation des permissions aux rôles
        Route::post('/roles/{role}/permissions', [AuthController::class, 'assignPermissionsToRole'])->name('admin.roles.permissions.assign');
        Route::delete('/roles/{role}/permissions/{permission}', [AuthController::class, 'revokePermissionFromRole'])->name('admin.roles.permissions.revoke');
        Route::get('/doctors', [AuthController::class, 'getDoctors'])->name('admin.doctors');
        Route::get('/doctors/create', [AuthController::class, 'createDoctor'])->name('admin.doctors.create');
        Route::post('/doctors', [AuthController::class, 'storeDoctor'])->name('admin.doctors.store');
        Route::get('/doctors/{doctor}', [AuthController::class, 'showDoctor'])->name('admin.doctors.show');
        Route::get('/doctors/{doctor}/edit', [AuthController::class, 'editDoctor'])->name('admin.doctors.edit');
        Route::put('/doctors/{doctor}', [AuthController::class, 'updateDoctor'])->name('admin.doctors.update');
        Route::delete('/doctors/{doctor}', [AuthController::class, 'destroyDoctor'])->name('admin.doctors.destroy');
        Route::get('/patients', [AuthController::class, 'getPatients'])->name('admin.patients');
        Route::get('/patients/{patient}', [AuthController::class, 'showPatient'])->name('admin.patients.show');
        Route::get('/patients/{patient}/edit', [AuthController::class, 'editPatient'])->name('admin.patients.edit');
        Route::put('/patients/{patient}', [AuthController::class, 'updatePatient'])->name('admin.patients.update');
        Route::delete('/patients/{patient}', [AuthController::class, 'destroyPatient'])->name('admin.patients.destroy');
        Route::get('/patients/{patient}/medical-file', [AuthController::class, 'showMedicalFile'])->name('admin.patients.medical-file');
        
        // Nouvelles routes pour les fonctionnalités manquantes
        // Secrétaires
        Route::get('/secretaries', [AuthController::class, 'getSecretaries'])->name('admin.secretaries');
        Route::get('/secretaries/{secretary}', [AuthController::class, 'showSecretary'])->name('admin.secretaries.show');
        Route::get('/secretaries/{secretary}/edit', [AuthController::class, 'editSecretary'])->name('admin.secretaries.edit');
        Route::put('/secretaries/{secretary}', [AuthController::class, 'updateSecretary'])->name('admin.secretaries.update');
        Route::post('/secretaries', [AuthController::class, 'storeSecretary'])->name('admin.secretaries.store');
        Route::delete('/secretaries/{secretary}', [AuthController::class, 'destroySecretary'])->name('admin.secretaries.destroy');
        
        // Comptabilité
        Route::get('/accounting', [App\Http\Controllers\DashboardController::class, 'accounting'])->name('admin.accounting');
        
        // Examens
        Route::get('/exams', [ExamController::class, 'adminIndex'])->name('admin.exams');
        Route::get('/exams/create', [ExamController::class, 'adminCreate'])->name('admin.exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('admin.exams.store');
        Route::get('/exams/{exam}', [ExamController::class, 'adminShow'])->name('admin.exams.show');
        Route::get('/exams/{exam}/edit', [ExamController::class, 'adminEdit'])->name('admin.exams.edit');
        Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('admin.exams.update');
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('admin.exams.destroy');
        
        // Résultats
        Route::get('/results', [ResultController::class, 'adminIndex'])->name('admin.results');
        
        // Lits
        Route::get('/beds', [App\Http\Controllers\BedController::class, 'index'])->name('admin.beds');
        Route::get('/beds/index', [App\Http\Controllers\BedController::class, 'index'])->name('admin.beds.index');
        Route::get('/beds/{bed}', [App\Http\Controllers\BedController::class, 'show'])->name('admin.beds.show');
        Route::get('/beds/{bed}/edit', [App\Http\Controllers\BedController::class, 'edit'])->name('admin.beds.edit');
        Route::put('/beds/{bed}', [App\Http\Controllers\BedController::class, 'update'])->name('admin.beds.update');
        Route::post('/beds', [App\Http\Controllers\BedController::class, 'store'])->name('admin.beds.store');
        Route::delete('/beds/{bed}', [App\Http\Controllers\BedController::class, 'destroy'])->name('admin.beds.destroy');
        
        // Actions supplémentaires pour les lits
        Route::post('/beds/{bed}/admit', [App\Http\Controllers\BedController::class, 'admitPatient'])->name('admin.beds.admit');
        Route::post('/beds/{bed}/discharge', [App\Http\Controllers\BedController::class, 'dischargePatient'])->name('admin.beds.discharge');
        Route::post('/beds/{bed}/maintenance', [App\Http\Controllers\BedController::class, 'setMaintenance'])->name('admin.beds.maintenance');
        Route::post('/beds/{bed}/available', [App\Http\Controllers\BedController::class, 'makeAvailable'])->name('admin.beds.available');
        
        // Pharmacie (Médicaments)
        Route::get('/pharmacy', [App\Http\Controllers\DashboardController::class, 'pharmacyStock'])->name('admin.pharmacy');
        Route::get('/pharmacy/{medicament}', [App\Http\Controllers\DashboardController::class, 'showMedicament'])->name('admin.pharmacy.show');
        Route::get('/pharmacy/{medicament}/edit', [App\Http\Controllers\DashboardController::class, 'editMedicament'])->name('admin.pharmacy.edit');
        Route::put('/pharmacy/{medicament}', [App\Http\Controllers\DashboardController::class, 'updateMedicament'])->name('admin.pharmacy.update');
        Route::post('/pharmacy', [App\Http\Controllers\DashboardController::class, 'storeMedicament'])->name('admin.pharmacy.store');
        Route::delete('/pharmacy/{medicament}', [App\Http\Controllers\DashboardController::class, 'destroyMedicament'])->name('admin.pharmacy.destroy');
        
        // Prescriptions (Soins médicaux)
        Route::get('/prescriptions', [App\Http\Controllers\DashboardController::class, 'prescriptionsManagement'])->name('admin.prescriptions');
        Route::get('/prescriptions/{prescription}', [App\Http\Controllers\DashboardController::class, 'showPrescription'])->name('admin.prescriptions.show');
        Route::get('/prescriptions/{prescription}/edit', [App\Http\Controllers\DashboardController::class, 'editPrescription'])->name('admin.prescriptions.edit');
        Route::put('/prescriptions/{prescription}', [App\Http\Controllers\DashboardController::class, 'updatePrescription'])->name('admin.prescriptions.update');
        Route::post('/prescriptions', [App\Http\Controllers\DashboardController::class, 'storePrescription'])->name('admin.prescriptions.store');
        Route::delete('/prescriptions/{prescription}', [App\Http\Controllers\DashboardController::class, 'destroyPrescription'])->name('admin.prescriptions.destroy');
        
        // Ordonnances (Ordonnances médicales avec médicaments)
        Route::get('/ordonnances', [App\Http\Controllers\DashboardController::class, 'ordonnancesManagement'])->name('admin.ordonnances');
        Route::get('/ordonnances/{ordonnance}', [App\Http\Controllers\DashboardController::class, 'showOrdonnance'])->name('admin.ordonnances.show');
        Route::get('/ordonnances/{ordonnance}/edit', [App\Http\Controllers\DashboardController::class, 'editOrdonnance'])->name('admin.ordonnances.edit');
        Route::put('/ordonnances/{ordonnance}', [App\Http\Controllers\DashboardController::class, 'updateOrdonnance'])->name('admin.ordonnances.update');
        Route::post('/ordonnances', [App\Http\Controllers\DashboardController::class, 'storeOrdonnance'])->name('admin.ordonnances.store');
        Route::delete('/ordonnances/{ordonnance}', [App\Http\Controllers\DashboardController::class, 'destroyOrdonnance'])->name('admin.ordonnances.destroy');
        
        Route::get('/categories', [ServiceController::class, 'getCategories'])->name('admin.categories');
        Route::get('/schedule', [AppointmentController::class, 'adminSchedule'])->name('admin.schedule');
        Route::get('/settings', [App\Http\Controllers\DashboardController::class, 'adminSettings'])->name('admin.settings');
        Route::get('/logs', [App\Http\Controllers\DashboardController::class, 'adminLogs'])->name('admin.logs');
        Route::get('/backup', [App\Http\Controllers\DashboardController::class, 'adminBackup'])->name('admin.backup');
        Route::get('/tickets', [TicketController::class, 'adminTickets'])->name('admin.tickets');
        Route::get('/faq', [App\Http\Controllers\DashboardController::class, 'adminFaq'])->name('admin.faq');

        // Gestion des services
        Route::get('/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');

        // Gestion des articles
        Route::get('/articles/create', [ArticleController::class, 'create'])->name('admin.articles.create');
        Route::post('/articles', [ArticleController::class, 'store'])->name('admin.articles.store');
        Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('admin.articles.edit');
        Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
        Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');
    });

    // Routes pour le secrétariat uniquement (sans middleware de rôle pour l'instant)
    Route::group(['prefix' => 'secretary'], function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'secretaryDashboard'])->name('secretary.dashboard');
        Route::get('/statistics', [App\Http\Controllers\DashboardController::class, 'secretaryStatistics'])->name('secretary.statistics');
        Route::get('/appointments', [AppointmentController::class, 'secretaryAppointments'])->name('secretary.appointments');
        Route::get('/appointments/today', [AppointmentController::class, 'secretaryTodayAppointments'])->name('secretary.appointments.today');
        Route::get('/appointments/week', [AppointmentController::class, 'secretaryWeekAppointments'])->name('secretary.appointments.week');
        Route::get('/appointments/create', [AppointmentController::class, 'secretaryCreateAppointment'])->name('secretary.appointments.create');
        Route::get('/schedule', [AppointmentController::class, 'secretarySchedule'])->name('secretary.schedule');
        Route::get('/patients', [App\Http\Controllers\DashboardController::class, 'secretaryPatients'])->name('secretary.patients');
        Route::get('/patients/new', [App\Http\Controllers\DashboardController::class, 'secretaryCreatePatient'])->name('secretary.patients.new');
        Route::get('/patients/search', [App\Http\Controllers\DashboardController::class, 'secretarySearchPatients'])->name('secretary.patients.search');
        Route::get('/medical-files', [MedicalFileController::class, 'secretaryMedicalFiles'])->name('secretary.medical-files');
        Route::get('/doctors', [App\Http\Controllers\DashboardController::class, 'secretaryDoctors'])->name('secretary.doctors');
        Route::get('/doctors/availability', [AvailabilityController::class, 'secretaryDoctorsAvailability'])->name('secretary.doctors.availability');
        Route::get('/doctors/schedule', [AppointmentController::class, 'secretaryDoctorsSchedule'])->name('secretary.doctors.schedule');
        Route::get('/services', [ServiceController::class, 'secretaryServices'])->name('secretary.services');
        Route::get('/services/categories', [ServiceController::class, 'secretaryCategories'])->name('secretary.services.categories');
        Route::get('/reception', [App\Http\Controllers\DashboardController::class, 'secretaryReception'])->name('secretary.reception');
        Route::get('/waiting-list', [App\Http\Controllers\DashboardController::class, 'secretaryWaitingList'])->name('secretary.waiting-list');
        Route::get('/check-in', [App\Http\Controllers\DashboardController::class, 'secretaryCheckIn'])->name('secretary.check-in');
        Route::get('/messages', [MessageController::class, 'secretaryMessages'])->name('secretary.messages');
        Route::get('/notifications', [NotificationController::class, 'secretaryNotifications'])->name('secretary.notifications');
        Route::get('/reminders', [App\Http\Controllers\DashboardController::class, 'secretaryReminders'])->name('secretary.reminders');
        Route::get('/reports', [App\Http\Controllers\DashboardController::class, 'secretaryReports'])->name('secretary.reports');
        Route::get('/billing', [App\Http\Controllers\DashboardController::class, 'secretaryBilling'])->name('secretary.billing');
        Route::get('/inventory', [App\Http\Controllers\DashboardController::class, 'secretaryInventory'])->name('secretary.inventory');
        Route::get('/profile', [ProfileController::class, 'secretaryProfile'])->name('secretary.profile');
        Route::get('/settings', [ProfileController::class, 'secretarySettings'])->name('secretary.settings');
    });
});

// Routes pour la récupération de mot de passe
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Route de fallback pour les pages 404
Route::fallback(function () {
    return view('errors.404');
});
