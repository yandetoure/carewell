<?php declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalFileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicalFilePrescriptionController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('services', ServiceController::class);
Route::apiResource('articles', ArticleController::class);
Route::apiResource('examens', ExamController::class);
Route::apiResource('users', AuthController::class);
Route::apiResource('prescriptions', PrescriptionController::class);
Route::apiResource('medicalfileprescriptions', MedicalFilePrescriptionController::class);
Route::apiResource('medicalfiles', MedicalFileController::class);
Route::apiResource('tickets', TicketController::class);

Route::put('/profile/update', [AuthController::class, 'updateProfile']);


Route::apiResource('results', ResultController::class);

// Route pour afficher les disponibilités d'un médecin pour un service donné
// Route::get('doctors/{doctorId}/services/{serviceId}/availabilities', [AvailabilityController::class, 'show']);
Route::apiResource('availabilities', AvailabilityController::class);
Route::apiResource('user', AuthController::class);

Route::apiResource('notes', NoteController::class);


Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('appointments', AppointmentController::class);

});

Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/update', [AuthController::class, 'updateProfile']);
Route::post('/add-user', [AuthController::class, 'registerUser']);



// Route::middleware('auth:api')->get('/services/{id}/details', [ServiceController::class, 'showDetails']);
// Route::middleware('auth:api')->patch('/services/{id}', [ServiceController::class, 'update']);
// Route::middleware('auth:api')->delete('/services/{id}', [ServiceController::class, 'destroy']);

// Route::middleware('auth:api')->get('/services/search/{query}', [ServiceController::class, 'search']);

// Route::middleware('auth:api')->get('/medical-records/{id}', [AuthController::class, 'getMedicalRecord']);
// Route::middleware('auth:api')->post('/medical-records/{id}/upload', [AuthController::class, 'uploadMedicalRecord']);
// Route::middleware('auth:api')->get('/medical-records/{id}/download', [AuthController::class, 'downloadMedicalRecord']);
// Route::middleware('auth:api')->get('/medical-records/{id}/delete', [AuthController::class, 'deleteMedicalRecord']);
// Route::middleware('auth:api')->get('/profile', [AuthController::class, 'profile']);
// Route::middleware('auth:api')->post('/refresh-token', [AuthController::class, 'refresh']);


