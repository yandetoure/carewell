<?php  declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Services;
use App\Mail\WelcomeMail;
use App\Models\MedicalFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\MedicalFileMail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\RedirectToRoleDashboard;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use RedirectToRoleDashboard;
    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function register(Request $request)
    {
        // Validation des données
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

        // Convertir l'âge en date de naissance si nécessaire
        if (is_numeric($validated['day_of_birth'])) {
            // Si c'est un nombre, on considère que c'est un âge
            $age = (int)$validated['day_of_birth'];
            $validated['day_of_birth'] = now()->subYears($age)->format('Y-m-d');
        } elseif (strtotime($validated['day_of_birth']) === false) {
            // Si ce n'est pas une date valide, renvoyer une erreur
            return response()->json([
                'status' => false,
                'message' => 'Le champ day_of_birth doit être une date ou un âge valide.',
            ], 400);
        }

        // Gestion du fichier photo
        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('user_photos', 'public'); // Stockage dans le dossier 'storage/app/public/service_photos'
        }

        try {
            // Vérification et ajout de l'indicatif téléphonique si nécessaire
            if (!Str::startsWith($validated['phone_number'], '+221')) {
                $validated['phone_number'] = '+221' . $validated['phone_number'];
            }

            // Génération du numéro d'identification unique
            $identification_number = $this->generateUniqueIdentificationNumber();

            // Création de l'utilisateur si la validation passe
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'adress' => $validated['adress'],
                'phone_number' => $validated['phone_number'],
                'identification_number' => $identification_number,
                'day_of_birth' => $validated['day_of_birth'], // Stockage de la date de naissance
                'password' => Hash::make($validated['password']),
                'photo' => $path,
                'service_id' => $request->service_id, // Ajout du service_id
            ]);

            // Assigner le rôle 'patient' par défaut
            $rolePatient = Role::firstWhere('name', 'Patient');
            if ($rolePatient) {
                $user->assignRole($rolePatient);
            }

            // Envoi d'un email de bienvenue
            Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));

            // Authentification de l'utilisateur après l'inscription
            Auth::login($user);

            // Création du token
            $token = $user->createToken("API TOKEN")->plainTextToken;


            // Récupération de l'utilisateur authentifié
            $user = User::where('email', $request->email)->first();

            // Récupérer les rôles de l'utilisateur
            $roles = $user->getRoleNames(); // Méthode fournie par Spatie


            // Création automatique d'un dossier médical pour l'utilisateur
            $this->createMedicalRecord($user);

            // Rediriger vers le dashboard approprié selon le rôle
            return $this->redirectToRoleDashboard($user)->with('success', 'Compte créé avec succès ! Bienvenue sur CareWell.');

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Connexion d'un utilisateur.
     */
    public function login(Request $request)
    {
        try {
            // Validation des données
            $validator = validator($request->all(), [
                'email' => 'required|email|string',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $credentials = $request->only('email', 'password');

            // Authentification par session (pour l'interface web)
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Récupérer les rôles de l'utilisateur
                $roles = $user->getRoleNames();

                // Debug: Vérifier l'état de l'authentification
                \Log::info('Utilisateur connecté: ' . $user->email);
                \Log::info('Rôles: ' . $roles->implode(', '));
                \Log::info('Session ID: ' . session()->getId());

                // Créer un token Sanctum pour l'API si nécessaire
                $access_token = $user->createToken('auth-token')->plainTextToken;

                // Debug: Vérifier l'état de l'authentification
                \Log::info('Utilisateur connecté: ' . $user->email);
                \Log::info('Rôles: ' . $roles->implode(', '));
                \Log::info('Session ID: ' . session()->getId());

                // Créer un token Sanctum pour l'API si nécessaire
                $access_token = $user->createToken('auth-token')->plainTextToken;

                // Rediriger vers le dashboard approprié selon le rôle
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


    /**
     * Obtenir le profil de l'utilisateur connecté.
     */
    public function profile(){

        $userData = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Information de l'utilisateur",
            "data" => $userData,
            'id' => Auth::user()->id,
        ],200);
    }


    /**
     * Déconnexion de l'utilisateur.
     */
    public function logout()
    {
        try {
            // Récupération de l'utilisateur connecté
            $user = Auth::user();

            // Suppression de tous les tokens de l'utilisateur
            if ($user) {
                $user->tokens()->delete();
            }

            // Déconnexion de l'utilisateur
            auth()->logout();

            // Rediriger vers la page d'accueil
            return redirect()->route('home')->with('success', 'Déconnexion réussie');

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erreur lors de la déconnexion');
        }
    }

       /**
     * Création d'un dossier médical pour l'utilisateur.
     */
/**
 * Création d'un dossier médical pour l'utilisateur.
 */
private function createMedicalRecord(User $user): void
{
    // Vérifiez si l'utilisateur a déjà un dossier médical
    $existingMedicalFile = MedicalFile::where('user_id', $user->id)->first();

    // Si aucun dossier médical n'existe, créez-en un nouveau
    if (!$existingMedicalFile) {
        MedicalFile::create([
            'user_id' => $user->id,
            // L'identification_number sera généré automatiquement par le modèle
        ]);

        // Envoi de l'email de notification
        Mail::to($user->email)->send(new \App\Mail\MedicalFileMail($user));
    }
}

        /**
     * Génère un numéro d'identification unique.
     */
    private function generateUniqueIdentificationNumber(): string
    {
        do {
            // Générer un identifiant aléatoire
            $identification_number = Str::random(10);
        } while (User::where('identification_number', $identification_number)->exists());

        return $identification_number;
    }

    public function sendWelcomeEmail($userId)
{
    // Récupérer l'utilisateur avec son dossier médical
    $user = User::with('medicalFile')->findOrFail($userId);

    // Envoyer l'e-mail
    Mail::to($user->email)->send(new WelcomeMail($user));

    return response()->json(['message' => 'E-mail de bienvenue envoyé avec succès.']);
}

public function updateProfile(Request $request)
{
    // Validation des données d'entrée
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
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Si une photo est uploadée, la stocker et mettre à jour le chemin
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('user_photos', 'public');
            $user->photo = $path;
        }

        // Mettre à jour les champs si fournis
        if ($request->has('first_name')) {
            $user->first_name = $request->first_name;
        }
        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('adress')) {
            $user->adress = $request->adress;
        }
        if ($request->has('phone_number')) {
            // Ajouter l'indicatif +221 si nécessaire
            $phone_number = $request->phone_number;
            if (!Str::startsWith($phone_number, '+221')) {
                $phone_number = '+221' . $phone_number;
            }
            $user->phone_number = $phone_number;
        }
        if ($request->has('day_of_birth')) {
            $user->day_of_birth = $request->day_of_birth;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        // Enregistrer les modifications
        $user->save();

        // Création automatique d'un dossier médical pour l'utilisateur
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
    $users = User::with('roles')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    return view('admin.users.index', compact('users'));
}


public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone_number' => 'nullable|string|max:20',
        'password' => 'required|string|min:8',
        'role' => 'required|string|in:Patient,Doctor,Secretary,Admin',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Créer le nom complet
    $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

    // Ajouter les champs requis
    $userData = [
        'name' => $fullName,
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'phone_number' => $validated['phone_number'] ?? '',
        'phone' => $validated['phone_number'] ?? '', // Dupliquer pour compatibilité
        'password' => Hash::make($validated['password']),
        'adress' => '', // Champ requis
        'day_of_birth' => '1990-01-01', // Valeur par défaut
        'email_verified_at' => now(),
    ];

    if ($request->hasFile('photo')) {
        $userData['photo'] = $request->file('photo')->store('users', 'public');
    }

    $user = User::create($userData);
    $user->assignRole($validated['role']);

    // Créer automatiquement le dossier médical si c'est un patient
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
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone_number' => 'nullable|string|max:20',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }
        $validated['photo'] = $request->file('photo')->store('users', 'public');
    }

    $user->update($validated);

    return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
}

public function updateRole(Request $request, User $user)
{
    $validated = $request->validate([
        'role' => 'required|string|in:patient,doctor,secretary,admin',
    ]);

    $user->syncRoles([$validated['role']]);

    return redirect()->route('admin.users')->with('success', 'Rôle de l\'utilisateur mis à jour avec succès.');
}

public function destroy(User $user)
{
    // Empêcher la suppression de soi-même
    if ($user->id === Auth::id()) {
        return redirect()->route('admin.users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    }

    // Supprimer l'image associée si elle existe
    if ($user->photo) {
        Storage::delete('public/' . $user->photo);
    }

    // Supprimer l'utilisateur
    $user->delete();

    return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
}

// ==================== GESTION DES DOCTEURS ====================

public function getDoctors()
{
    $doctors = User::role('Doctor', 'web')
        ->with(['services', 'appointments'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    // Statistiques rapides
    $totalDoctors = User::role('Doctor', 'web')->count();
    $activeDoctors = User::role('Doctor', 'web')->where('status', 'active')->count();
    $newThisMonth = User::role('Doctor', 'web')->where('created_at', '>=', now()->startOfMonth())->count();
    $withServices = User::role('Doctor', 'web')->whereHas('services')->count();
    
    return view('admin.doctors.index', compact('doctors', 'totalDoctors', 'activeDoctors', 'newThisMonth', 'withServices'));
}

public function createDoctor()
{
    return view('admin.doctors.create');
}

public function storeDoctor(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:20',
        'specialty' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:active,inactive,pending',
        'description' => 'nullable|string|max:1000',
    ]);

    // Gestion de la photo
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('doctors', 'public');
    }

    // Générer un mot de passe automatique
    $autoPassword = Str::random(12);

    // Créer le nom complet
    $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

    // Créer l'utilisateur
    $user = User::create([
        'name' => $fullName,
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'phone_number' => $validated['phone'], // Dupliquer pour compatibilité
        'password' => Hash::make($autoPassword),
        'photo' => $validated['photo'] ?? null,
        'specialty' => $validated['specialty'] ?? null,
        'status' => $validated['status'],
        'description' => $validated['description'] ?? null,
        'adress' => '', // Champ requis
        'day_of_birth' => '1990-01-01', // Valeur par défaut
        'email_verified_at' => now(),
    ]);

    // Assigner le rôle Doctor
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
    return view('admin.doctors.edit', compact('doctor'));
}

public function updateDoctor(Request $request, User $doctor)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
        'phone' => 'required|string|max:20',
        'specialty' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:active,inactive,pending',
        'description' => 'nullable|string|max:1000',
    ]);

    // Gestion de la photo
    if ($request->hasFile('photo')) {
        if ($doctor->photo) {
            Storage::delete('public/' . $doctor->photo);
        }
        $validated['photo'] = $request->file('photo')->store('doctors', 'public');
    }

    // Créer le nom complet
    $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

    $doctor->update([
        'name' => $fullName,
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'phone_number' => $validated['phone'],
        'photo' => $validated['photo'] ?? $doctor->photo,
        'specialty' => $validated['specialty'] ?? null,
        'status' => $validated['status'],
        'description' => $validated['description'] ?? null,
    ]);

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
    
    // Statistiques rapides
    $totalPatients = User::role('Patient', 'web')->count();
    $activePatients = User::role('Patient', 'web')->where('status', 'active')->count();
    $newThisMonth = User::role('Patient', 'web')->where('created_at', '>=', now()->startOfMonth())->count();
    $withAppointments = User::role('Patient', 'web')->whereHas('appointments')->count();
    
    return view('admin.patients.index', compact('patients', 'totalPatients', 'activePatients', 'newThisMonth', 'withAppointments'));
}

public function showMedicalFile(User $patient)
{
    // Vérifier que l'utilisateur est bien un patient
    if (!$patient->hasRole('Patient')) {
        abort(404, 'Patient non trouvé');
    }

    // Charger le dossier médical du patient
    $medicalFile = $patient->medicalFiles()->first();
    
    // Si aucun dossier médical n'existe, en créer un
    if (!$medicalFile) {
        $medicalFile = $patient->createMedicalFile();
    }

    // Charger les relations nécessaires
    $medicalFile->load(['medicalHistories', 'medicalprescription', 'medicalexam', 'note', 'medicaldisease']);

    return view('admin.patients.medical-file', compact('patient', 'medicalFile'));
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
        $role->syncPermissions($validated['permissions']);
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
        $role->syncPermissions($validated['permissions']);
    }

    return redirect()->route('admin.roles.show', $role)->with('success', 'Rôle mis à jour avec succès.');
}

public function destroyRole(Role $role)
{
    // Empêcher la suppression des rôles système
    $systemRoles = ['Admin', 'Doctor', 'Secretary', 'Patient'];
    if (in_array($role->name, $systemRoles)) {
        return redirect()->route('admin.roles')->with('error', 'Ce rôle système ne peut pas être supprimé.');
    }

    // Vérifier s'il y a des utilisateurs avec ce rôle
    $usersCount = User::role($role->name, 'web')->count();
    if ($usersCount > 0) {
        return redirect()->route('admin.roles')->with('error', "Ce rôle ne peut pas être supprimé car $usersCount utilisateur(s) l'utilisent encore.");
    }

    $role->delete();

    return redirect()->route('admin.roles')->with('success', 'Rôle supprimé avec succès.');
}

public function getPermissions()
{
    $permissions = Permission::with('roles')
        ->where('guard_name', 'web')
        ->paginate(50);
    
    return view('admin.permissions.index', compact('permissions'));
}

public function storePermission(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:permissions,name',
        'display_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'group' => 'required|string|max:100',
    ]);

    Permission::create($validated);

    return redirect()->route('admin.permissions')->with('success', 'Permission créée avec succès.');
}

public function updatePermission(Request $request, Permission $permission)
{
    $validated = $request->validate([
        'display_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'group' => 'required|string|max:100',
    ]);

    $permission->update($validated);

    return redirect()->route('admin.permissions')->with('success', 'Permission mise à jour avec succès.');
}

public function destroyPermission(Permission $permission)
{
    // Vérifier si la permission est utilisée
    if ($permission->roles()->count() > 0) {
        return redirect()->route('admin.permissions')->with('error', 'Cette permission ne peut pas être supprimée car elle est assignée à des rôles.');
    }

    $permission->delete();

    return redirect()->route('admin.permissions')->with('success', 'Permission supprimée avec succès.');
}

public function assignPermissionsToRole(Request $request, Role $role)
{
    $validated = $request->validate([
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role->syncPermissions($validated['permissions']);

    return redirect()->route('admin.roles.show', $role)->with('success', 'Permissions assignées avec succès.');
}

public function revokePermissionFromRole(Role $role, Permission $permission)
{
    $role->revokePermissionTo($permission);

    return redirect()->route('admin.roles.show', $role)->with('success', 'Permission révoquée avec succès.');
}


    // public function getUserRole(Request $request)
    // {
    //     // Récupérer l'utilisateur connecté
    //     $user = Auth::user();

    //     // Récupérer le rôle de l'utilisateur
    //     $roles = $user->getRoleNames(); // Cela retourne un tableau de rôles

    //     // Retourner le premier rôle trouvé
    //     return response()->json([
    //         'role' => $roles->first(),
    //     ], 200);
    // }




    public function registerUser(Request $request)
{
    // Validation des données
    $validateUser = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'adress' => 'required|string|max:255',
        'phone_number' => 'required|regex:/^[0-9]{9}$/',
        'day_of_birth' => 'required',
        'password' => 'required|string|min:8',
        'role' => 'required|in:Admin,Doctor,Secretaire,Accountant',
        'photo' => 'nullable|file|image|max:2048',
        // 'grade_id' => 'required|exists:grades,id',
        'service_id' => 'required_if:role,Doctor|exists:services,id',

    ]);

    if ($validateUser->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validateUser->errors()
        ], 400);
    }

    $validated = $validateUser->validated();


            // Gestion du fichier photo
            // $path = null;
            // if ($request->hasFile('photo')) {
            //     $path = $request->file('photo')->store('user_photos', 'public'); // Stockage dans le dossier 'storage/app/public/service_photos'
            // }

            // try {
            //     // Vérification et ajout de l'indicatif téléphonique si nécessaire
            //     if (!Str::startsWith($validated['phone_number'], '+221')) {
            //         $validated['phone_number'] = '+221' . $validated['phone_number'];
            //     }

        // Génération du numéro d'identification unique
        $identification_number = $this->generateUniqueIdentificationNumber();



    try {
        // Création de l'utilisateur
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'adress' => $validated['adress'],
            'phone_number' => '+221' . $validated['phone_number'],
            'day_of_birth' => $validated['day_of_birth'],
            'password' => Hash::make($validated['password']),
            'photo' => $path ?? null,
            // 'grade_id' => $request->grade_id,
            'service_id' => $request->service_id,

        ]);

        // Assigner le rôle selon l'entrée du formulaire
        $role = Role::firstWhere('name', $validated['role']);
        if ($role) {
            $user->assignRole($role);
        }

        // Créer automatiquement le dossier médical si c'est un patient
        if ($validated['role'] === 'Patient') {
            $this->createMedicalRecord($user);
        }

        // Envoyer l'email de bienvenue
        Mail::to($user->email)->send(new WelcomeMail($user));

        // Auth::login($user);

        $token = $user->createToken("API TOKEN")->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Le compte a été créé avec succès',
            'token' => $token,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Erreur lors de la création de l\'utilisateur',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getUserStatistics()
{
    try {
        // Récupérer tous les rôles existants dans Spatie
        $roles = Role::all();
        $statistics = [];

        foreach ($roles as $role) {
            // Compter le nombre d'utilisateurs pour chaque rôle
            $count = User::role($role->name)->count();
            $statistics[$role->name] = $count;
        }

        return response()->json([
            'status' => true,
            'message' => 'Statistiques des utilisateurs récupérées avec succès',
            'data' => $statistics
        ], 200);

    } catch (\Exception $e) {
        Log::error('Erreur statistiques: ' . $e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Erreur lors de la récupération des statistiques',
            'error' => $e->getMessage()
        ], 500);
    }

}

}

