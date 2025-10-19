<?php declare(strict_types=1);

// Test script to verify dashboard data consistency
require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Bed;
use App\Models\Appointment;
use App\Models\MedicalFilePrescription;
use App\Models\Service;
use App\Models\VitalSign;

echo "=== Test des données du Dashboard Infirmière ===\n\n";

// Test des statistiques
echo "1. STATISTIQUES GÉNÉRALES:\n";
$totalPatients = User::whereHas('appointments')->count();
echo "   - Total patients: {$totalPatients}\n";

$hospitalizedPatients = Bed::where('status', 'occupe')->count();
echo "   - Patients hospitalisés: {$hospitalizedPatients}\n";

$todayAppointments = Appointment::whereDate('appointment_date', today())
    ->whereIn('status', ['confirmed', 'pending'])
    ->count();
echo "   - Rendez-vous aujourd'hui: {$todayAppointments}\n";

$pendingPrescriptions = MedicalFilePrescription::where('is_done', false)->count();
echo "   - Prescriptions en attente: {$pendingPrescriptions}\n\n";

// Test de l'occupation des lits
echo "2. OCCUPATION DES LITS:\n";
$totalBeds = Bed::count();
$occupiedBeds = Bed::where('status', 'occupe')->count();
$availableBeds = Bed::where('status', 'libre')->count();
$occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 2) : 0;

echo "   - Total lits: {$totalBeds}\n";
echo "   - Lits occupés: {$occupiedBeds}\n";
echo "   - Lits disponibles: {$availableBeds}\n";
echo "   - Taux d'occupation: {$occupancyRate}%\n\n";

// Test des statistiques de services
echo "3. STATISTIQUES DES SERVICES:\n";
$serviceStats = Service::withCount(['appointments' => function($query) {
    $query->whereDate('appointment_date', today());
}])->get();

foreach ($serviceStats as $service) {
    echo "   - {$service->name}: {$service->appointments_count} rendez-vous\n";
}
echo "\n";

// Test des signes vitaux
echo "4. SIGNS VITAUX:\n";
$totalVitalSigns = VitalSign::count();
$abnormalVitalSigns = VitalSign::where('recorded_at', '>=', now()->subHours(24))
    ->where(function($query) {
        $query->where('heart_rate', '>', 100)
              ->orWhere('heart_rate', '<', 60)
              ->orWhere('temperature', '>', 38.5)
              ->orWhere('temperature', '<', 36.0)
              ->orWhere('oxygen_saturation', '<', 95)
              ->orWhere('blood_pressure_systolic', '>', 140)
              ->orWhere('blood_pressure_systolic', '<', 90);
    })
    ->count();

echo "   - Total signes vitaux: {$totalVitalSigns}\n";
echo "   - Signes vitaux anormaux (24h): {$abnormalVitalSigns}\n\n";

// Test des prescriptions urgentes
echo "5. PRESCRIPTIONS URGENTES:\n";
$urgentPrescriptions = MedicalFilePrescription::where('is_done', false)
    ->where('created_at', '<=', now()->subHours(2))
    ->count();
echo "   - Prescriptions urgentes (>2h): {$urgentPrescriptions}\n\n";

echo "=== VÉRIFICATION DE LA COHÉRENCE ===\n";
echo "Les données ci-dessus devraient correspondre à celles affichées dans le dashboard.\n";
echo "Si elles ne correspondent pas, vérifiez les méthodes dans NurseController.\n\n";

echo "=== DONNÉES DÉTAILLÉES ===\n";
echo "Lits occupés:\n";
$occupiedBedsDetails = Bed::where('status', 'occupe')->with('medicalFile.user')->get();
foreach ($occupiedBedsDetails as $bed) {
    $patientName = $bed->medicalFile && $bed->medicalFile->user 
        ? $bed->medicalFile->user->first_name . ' ' . $bed->medicalFile->user->last_name
        : 'Pas de patient';
    echo "   - Lit {$bed->bed_number}: {$patientName}\n";
}

echo "\nPrescriptions en attente:\n";
$pendingPrescriptionsDetails = MedicalFilePrescription::where('is_done', false)
    ->with(['medicalFile.user', 'prescription'])
    ->get();
foreach ($pendingPrescriptionsDetails as $prescription) {
    $patientName = $prescription->medicalFile && $prescription->medicalFile->user 
        ? $prescription->medicalFile->user->first_name . ' ' . $prescription->medicalFile->user->last_name
        : 'Patient inconnu';
    $medication = $prescription->prescription ? $prescription->prescription->name : 'Médicament inconnu';
    echo "   - {$patientName}: {$medication}\n";
}

?>
