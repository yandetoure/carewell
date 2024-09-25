<?php declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ExamController;
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


