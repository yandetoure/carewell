<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

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
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    // Examens
    Route::get('/exams', [ExamController::class, 'index'])->name('exams');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');

    // Résultats
    Route::get('/results', [ResultController::class, 'index'])->name('results');
    Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');

    // Notes médicales
    Route::get('/notes', [NoteController::class, 'index'])->name('notes');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');

    // Maladies
    Route::get('/diseases', [DiseaseController::class, 'index'])->name('diseases');
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
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability');
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
        Route::get('/appointments/today', [AppointmentController::class, 'doctorTodayAppointments'])->name('doctor.appointments.today');
        Route::get('/appointments/week', [AppointmentController::class, 'doctorWeekAppointments'])->name('doctor.appointments.week');
        Route::get('/patients', [AppointmentController::class, 'getPatientsWithAppointments'])->name('doctor.patients');
        Route::get('/patients/new', [AppointmentController::class, 'createPatient'])->name('doctor.patients.new');
        Route::get('/stats', [AppointmentController::class, 'doctorStats'])->name('doctor.stats');
        Route::get('/statistics', [AppointmentController::class, 'doctorStatistics'])->name('doctor.statistics');
        Route::get('/prescriptions', [MedicalFilePrescriptionController::class, 'getPrescriptionsByService'])->name('doctor.prescriptions');
        Route::get('/exams', [ExamPrescriptionController::class, 'getExamByService'])->name('doctor.exams');
        Route::get('/results', [ResultController::class, 'doctorResults'])->name('doctor.results');
        Route::get('/consultations', [AppointmentController::class, 'doctorConsultations'])->name('doctor.consultations');
        Route::get('/medical-history', [MedicalFileController::class, 'doctorMedicalHistory'])->name('doctor.medical-history');
        Route::get('/notes', [NoteController::class, 'doctorNotes'])->name('doctor.notes');
        Route::get('/follow-up', [AppointmentController::class, 'doctorFollowUp'])->name('doctor.follow-up');
        Route::get('/messages', [MessageController::class, 'doctorMessages'])->name('doctor.messages');
        Route::get('/notifications', [NotificationController::class, 'doctorNotifications'])->name('doctor.notifications');
        Route::get('/profile', [ProfileController::class, 'doctorProfile'])->name('doctor.profile');
        Route::get('/settings', [ProfileController::class, 'doctorSettings'])->name('doctor.settings');

        // Gestion des prescriptions
        Route::put('/prescriptions/{prescription}/status', [MedicalFilePrescriptionController::class, 'updatePrescriptionStatus'])->name('prescriptions.updateStatus');

        // Gestion des examens
        Route::put('/exams/{exam}/status', [ExamPrescriptionController::class, 'updateExamStatus'])->name('exams.updateStatus');
        Route::post('/exams/{exam}/results', [ExamPrescriptionController::class, 'storeResult'])->name('exams.storeResult');

        // Gestion des dossiers médicaux
        Route::post('/medical-files/{medicalFile}/addnote', [MedicalFileController::class, 'addNote'])->name('medical-files.addNote');
        Route::post('/medical-files/{medicalFile}/addHistory', [MedicalFileController::class, 'addMedicalHistories'])->name('medical-files.addHistory');
        Route::post('/medical-files/{medicalFile}/addprescription', [MedicalFileController::class, 'addPrescription'])->name('medical-files.addPrescription');
        Route::post('/medical-files/{medicalFile}/addexam', [MedicalFileController::class, 'addExam'])->name('medical-files.addExam');
        Route::post('/medical-files/{medicalFile}/adddisease', [MedicalFileController::class, 'addDisease'])->name('medical-files.addDisease');
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
        Route::get('/users', [AuthController::class, 'getUsers'])->name('admin.users');
        Route::get('/services', [ServiceController::class, 'adminIndex'])->name('admin.services');
        Route::get('/articles', [ArticleController::class, 'adminIndex'])->name('admin.articles');
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments');
        Route::get('/statistics', [App\Http\Controllers\DashboardController::class, 'adminStatistics'])->name('admin.statistics');
        Route::get('/roles', [AuthController::class, 'getRoles'])->name('admin.roles');
        Route::get('/doctors', [AuthController::class, 'getDoctors'])->name('admin.doctors');
        Route::get('/patients', [AuthController::class, 'getPatients'])->name('admin.patients');
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
