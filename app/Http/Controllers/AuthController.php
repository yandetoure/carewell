<?php  declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Mail\WelcomeMail;
use App\Models\MedicalFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\MedicalFileMail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\RedirectToRoleDashboard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use RedirectToRoleDashboard;

    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'adress' => 'required|string|max:255',
            'phone_number' => 'required|regex:/^[0-9]{9}$/',
            'day_of_birth' => 'required',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|file|image|max:2048',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 400);
        }

        $validated = $validateUser->validated();

        if (is_numeric($validated['day_of_birth'])) {
            $age = (int)$validated['day_of_birth'];
            $validated['day_of_birth'] = now()->subYears($age)->format('Y-m-d');
        } elseif (strtotime($validated['day_of_birth']) === false) {
            return response()->json([
                'status' => false,
                'message' => 'Le champ day_of_birth doit être une date ou un âge valide.',
            ], 400);
        }

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('user_photos', 'public');
        }

        try {
            if (!Str::startsWith($validated['phone_number'], '+221')) {
                $validated['phone_number'] = '+221' . $validated['phone_number'];
            }

            $identification_number = $this->generateUniqueIdentificationNumber();

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'adress' => $validated['adress'],
                'phone_number' => $validated['phone_number'],
                'identification_number' => $identification_number,
                'day_of_birth' => $validated['day_of_birth'],
                'password' => Hash::make($validated['password']),
                'photo' => $path,
                'service_id' => $request->service_id,
            ]);

            $rolePatient = Role::firstWhere('name', 'Patient');
            if ($rolePatient) {
                $user->assignRole($rolePatient);
            }

            Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));

            Auth::login($user);

            $this->createMedicalRecord($user);

            return $this->redirectToRoleDashboard($user)->with('success', 'Compte créé avec succès ! Bienvenue sur CareWell.');

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'email' => 'required|email|string',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return $this->redirectToRoleDashboard($user)->with('success', 'Connexion réussie ! Bienvenue sur CareWell.');
            } else {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Le mot de passe est incorrect'],
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(){
        $userData = auth()->user();
        return response()->json([
            "status" => true,
            "message" => "Information de l'utilisateur",
            "data" => $userData,
            'id' => Auth::user()->id,
        ],200);
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->tokens()->delete();
            }
            auth()->logout();
            return redirect()->route('home')->with('success', 'Déconnexion réussie');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erreur lors de la déconnexion');
        }
    }

    private function createMedicalRecord(User $user): void
    {
        $existingMedicalFile = MedicalFile::where('user_id', $user->id)->first();
        if (!$existingMedicalFile) {
            MedicalFile::create([
                'user_id' => $user->id,
            ]);
            Mail::to($user->email)->send(new \App\Mail\MedicalFileMail($user));
        }
    }

    private function generateUniqueIdentificationNumber(): string
    {
        do {
            $identification_number = Str::random(10);
        } while (User::where('identification_number', $identification_number)->exists());

        return $identification_number;
    }

    public function sendWelcomeEmail($userId)
    {
        $user = User::with('medicalFile')->findOrFail($userId);
        Mail::to($user->email)->send(new WelcomeMail($user));
        return response()->json(['message' => 'E-mail de bienvenue envoyé avec succès.']);
    }

    public function updateProfile(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'adress' => 'nullable|string|max:255',
            'phone_number' => 'nullable|regex:/^[0-9]{9}$/',
            'day_of_birth' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 400);
        }

        try {
            $user = Auth::user();

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('user_photos', 'public');
                $user->photo = $path;
            }

            if ($request->has('first_name')) $user->first_name = $request->first_name;
            if ($request->has('last_name')) $user->last_name = $request->last_name;
            if ($request->has('email')) $user->email = $request->email;
            if ($request->has('adress')) $user->adress = $request->adress;
            if ($request->has('phone_number')) {
                $phone_number = $request->phone_number;
                if (!Str::startsWith($phone_number, '+221')) {
                    $phone_number = '+221' . $phone_number;
                }
                $user->phone_number = $phone_number;
            }
            if ($request->has('day_of_birth')) $user->day_of_birth = $request->day_of_birth;
            if ($request->has('password')) $user->password = Hash::make($request->password);

            $user->save();
            $this->createMedicalRecord($user);

            return response()->json([
                'status' => true,
                'message' => 'Profil mis à jour avec succès',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la mise à jour du profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUsers()
    {
        $users = User::with('roles')->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();
        $services = Service::all();
        return view('admin.users.index', compact('users', 'roles', 'services'));
    }

    public function store(Request $request)
    {
        $availableRoles = Role::pluck('name')->toArray();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:' . implode(',', $availableRoles),
            'service_id' => 'nullable|exists:services,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? '',
            'password' => Hash::make($validated['password']),
            'adress' => '', 
            'day_of_birth' => '1990-01-01', 
            'service_id' => $validated['service_id'] ?? null,
            'email_verified_at' => now(),
        ];

        if ($request->hasFile('photo')) {
            $userData['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user = User::create($userData);
        $user->assignRole($validated['role']);

        if ($validated['role'] === 'Patient') {
            $user->createMedicalFile();
        }

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $services = Service::all();
        return view('admin.users.edit', compact('user', 'services'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        $validated['service_id'] = $validated['service_id'] ?? null;
        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function updateRole(Request $request, User $user)
    {
        $availableRoles = Role::pluck('name')->toArray();
        $validated = $request->validate([
            'role' => 'required|string|in:' . implode(',', $availableRoles),
        ]);
        $user->syncRoles([$validated['role']]);
        return redirect()->route('admin.users')->with('success', 'Rôle de l\'utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // ==================== GESTION DES DOCTEURS ====================

    public function getDoctors()
    {
        $doctors = User::role('Doctor', 'web')
            ->with(['service', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalDoctors = User::role('Doctor', 'web')->count();
        $activeDoctors = User::role('Doctor', 'web')->where('status', 'active')->count();
        $newThisMonth = User::role('Doctor', 'web')->where('created_at', '>=', now()->startOfMonth())->count();
        $withServices = User::role('Doctor', 'web')->whereNotNull('service_id')->count();
        
        return view('admin.doctors.index', compact('doctors', 'totalDoctors', 'activeDoctors', 'newThisMonth', 'withServices'));
    }

    public function createDoctor()
    {
        $services = Service::all();
        return view('admin.doctors.create', compact('services'));
    }

    public function storeDoctor(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,pending',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $autoPassword = Str::random(12);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'service_id' => $validated['service_id'] ?? null,
            'password' => Hash::make($autoPassword),
            'photo' => $validated['photo'] ?? null,
            'status' => $validated['status'],
            'biographie' => $validated['description'] ?? null,
            'adress' => '', 
            'day_of_birth' => '1990-01-01', 
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Doctor');

        return redirect()->route('admin.doctors')->with('success', 'Médecin créé avec succès.');
    }

    public function showDoctor(User $doctor)
    {
        $doctor->load(['services', 'appointments']);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function editDoctor(User $doctor)
    {
        $services = Service::all();
        return view('admin.doctors.edit', compact('doctor', 'services'));
    }

    public function updateDoctor(Request $request, User $doctor)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'phone' => 'required|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,pending',
            'description' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'service_id' => $validated['service_id'] ?? null,
            'status' => $validated['status'],
            'biographie' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::delete('public/' . $doctor->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor->update($updateData);

        return redirect()->route('admin.doctors')->with('success', 'Médecin mis à jour avec succès.');
    }

    public function destroyDoctor(User $doctor)
    {
        if ($doctor->photo) {
            Storage::delete('public/' . $doctor->photo);
        }
        $doctor->delete();
        return redirect()->route('admin.doctors')->with('success', 'Médecin supprimé avec succès.');
    }

    // ==================== GESTION DES PATIENTS ====================

    public function getPatients()
    {
        $patients = User::role('Patient', 'web')
            ->with(['appointments', 'medicalFiles'])
            ->withCount(['appointments', 'medicalFiles'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalPatients = User::role('Patient', 'web')->count();
        $activePatients = User::role('Patient', 'web')->where('status', 'active')->count();
        $newThisMonth = User::role('Patient', 'web')->where('created_at', '>=', now()->startOfMonth())->count();
        $withAppointments = User::role('Patient', 'web')->whereHas('appointments')->count();
        
        return view('admin.patients.index', compact('patients', 'totalPatients', 'activePatients', 'newThisMonth', 'withAppointments'));
    }

    public function showMedicalFile(User $patient)
    {
        if (!$patient->hasRole('Patient')) {
            abort(404, 'Patient non trouvé');
        }

        $medicalFile = $patient->medicalFiles()->first();
        if (!$medicalFile) {
            $medicalFile = $patient->createMedicalFile();
        }

        $medicalFile->load(['medicalHistories', 'medicalprescription', 'medicalexam', 'note', 'medicaldisease']);

        $ordonnances = $patient->ordonnancesAsPatient()
            ->with(['medecin', 'medicaments'])
            ->orderBy('date_prescription', 'desc')
            ->get();

        $appointments = $patient->appointments()
            ->with(['service', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $diseases = $patient->medicalFiles()
            ->with(['medicaldisease.disease'])
            ->get()
            ->pluck('medicaldisease')
            ->flatten()
            ->pluck('disease')
            ->filter()
            ->unique('id');

        return view('admin.patients.medical-file', compact('patient', 'medicalFile', 'ordonnances', 'appointments', 'diseases'));
    }

    public function showPatient(User $patient)
    {
        if (!$patient->hasRole('Patient')) {
            abort(404, 'Patient non trouvé');
        }

        $patient->load(['appointments', 'medicalFiles']);
        $appointmentsCount = $patient->appointments()->count();
        $medicalFilesCount = $patient->medicalFiles()->count();
        $lastAppointment = $patient->appointments()->latest()->first();

        return view('admin.patients.show', compact('patient', 'appointmentsCount', 'medicalFilesCount', 'lastAppointment'));
    }

    public function editPatient(User $patient)
    {
        if (!$patient->hasRole('Patient')) {
            abort(404, 'Patient non trouvé');
        }
        return view('admin.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, User $patient)
    {
        if (!$patient->hasRole('Patient')) {
            abort(404, 'Patient non trouvé');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $patient->id,
            'phone' => 'nullable|string|max:20',
            'adress' => 'nullable|string|max:500',
            'day_of_birth' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'] ?? $patient->phone_number,
            'adress' => $validated['adress'] ?? $patient->adress,
            'day_of_birth' => $validated['day_of_birth'] ?? $patient->day_of_birth,
        ];

        if ($request->hasFile('photo')) {
            if ($patient->photo) {
                Storage::delete('public/' . $patient->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $patient->update($updateData);

        return redirect()->route('admin.patients.show', $patient)->with('success', 'Patient mis à jour avec succès.');
    }

    public function destroyPatient(User $patient)
    {
        if (!$patient->hasRole('Patient')) {
            abort(404, 'Patient non trouvé');
        }
        if ($patient->photo) {
            Storage::delete('public/' . $patient->photo);
        }
        $patient->delete();
        return redirect()->route('admin.patients')->with('success', 'Patient supprimé avec succès.');
    }

    public function getSecretaries()
    {
        $secretaries = User::role('Secretary', 'web')
            ->with(['appointments'])
            ->withCount(['appointments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalSecretaries = User::role('Secretary', 'web')->count();
        $activeSecretaries = User::role('Secretary', 'web')->where('status', 'active')->count();
        $newThisMonth = User::role('Secretary', 'web')->where('created_at', '>=', now()->startOfMonth())->count();
        $withAppointments = User::role('Secretary', 'web')->whereHas('appointments')->count();
        
        return view('admin.secretaries.index', compact('secretaries', 'totalSecretaries', 'activeSecretaries', 'newThisMonth', 'withAppointments'));
    }

    public function showSecretary(User $secretary)
    {
        if (!$secretary->hasRole('Secretary')) {
            abort(404, 'Secrétaire non trouvée');
        }
        $secretary->load(['appointments']);
        $secretary->loadCount(['appointments']);
        return view('admin.secretaries.show', compact('secretary'));
    }

    public function editSecretary(User $secretary)
    {
        if (!$secretary->hasRole('Secretary')) {
            abort(404, 'Secrétaire non trouvée');
        }
        return view('admin.secretaries.edit', compact('secretary'));
    }

    public function updateSecretary(Request $request, User $secretary)
    {
        if (!$secretary->hasRole('Secretary')) {
            abort(404, 'Secrétaire non trouvée');
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $secretary->id,
            'phone' => 'nullable|string|max:20',
            'adress' => 'nullable|string|max:500',
            'day_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'status' => 'required|string|in:active,inactive,suspended',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'] ?? $secretary->phone_number,
            'adress' => $validated['adress'] ?? $secretary->adress,
            'day_of_birth' => $validated['day_of_birth'] ?? $secretary->day_of_birth,
            'gender' => $validated['gender'] ?? $secretary->gender,
            'status' => $validated['status'],
        ];
        
        if ($request->hasFile('photo')) {
            if ($secretary->photo) {
                Storage::delete('public/' . $secretary->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('secretaries', 'public');
        }
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }
        
        $secretary->update($updateData);
        return redirect()->route('admin.secretaries.show', $secretary)->with('success', 'Secrétaire mise à jour avec succès.');
    }

    public function storeSecretary(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $userData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ];
        
        if ($request->hasFile('photo')) {
            $userData['photo'] = $request->file('photo')->store('secretaries', 'public');
        }
        
        $secretary = User::create($userData);
        $secretary->assignRole('Secretary');
        
        return redirect()->route('admin.secretaries')->with('success', 'Secrétaire créée avec succès.');
    }

    public function destroySecretary(User $secretary)
    {
        if (!$secretary->hasRole('Secretary')) {
            abort(404, 'Secrétaire non trouvée');
        }
        if ($secretary->photo) {
            Storage::delete('public/' . $secretary->photo);
        }
        $secretary->delete();
        return redirect()->route('admin.secretaries')->with('success', 'Secrétaire supprimée avec succès.');
    }

    // ==================== GESTION DES RÔLES ET PERMISSIONS ====================

    public function getRoles()
    {
        $roles = Role::with('permissions')
            ->where('guard_name', 'web')
            ->paginate(20);
        $permissions = Permission::where('guard_name', 'web')->get()->groupBy('group');
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['permissions'])) {
            $permissionNames = Permission::whereIn('id', $validated['permissions'])
                ->pluck('name')
                ->toArray();
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('admin.roles')->with('success', 'Rôle créé avec succès.');
    }

    public function showRole(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::where('guard_name', 'web')->get()->groupBy('group');
        $users = User::role($role->name, 'web')->paginate(10);
        return view('admin.roles.show', compact('role', 'permissions', 'users'));
    }

    public function editRole(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::where('guard_name', 'web')->get()->groupBy('group');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['permissions'])) {
            $permissionNames = Permission::whereIn('id', $validated['permissions'])
                ->pluck('name')
                ->toArray();
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('admin.roles.show', $role)->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroyRole(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles')->with('success', 'Rôle supprimé avec succès.');
    }

    public function getPermissions()
    {
        $permissions = Permission::where('guard_name', 'web')->paginate(20);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'group' => 'nullable|string|max:255',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'group' => $validated['group'] ?? 'Autre',
        ]);

        return redirect()->route('admin.permissions')->with('success', 'Permission créée avec succès.');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'group' => 'nullable|string|max:255',
        ]);

        $permission->update([
            'display_name' => $validated['display_name'],
            'group' => $validated['group'] ?? 'Autre',
        ]);

        return redirect()->route('admin.permissions')->with('success', 'Permission mise à jour avec succès.');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions')->with('success', 'Permission supprimée avec succès.');
    }

    public function assignPermissionsToRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->givePermissionTo($validated['permissions']);
        return redirect()->back()->with('success', 'Permissions assignées avec succès.');
    }

    public function revokePermissionFromRole(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission->name);
        return redirect()->back()->with('success', 'Permission révoquée avec succès.');
    }
}
