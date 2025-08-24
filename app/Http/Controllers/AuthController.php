<?php  declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\WelcomeMail;
use App\Models\MedicalFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\MedicalFileMail;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
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
    
            // Création automatique d'un dossier médical pour l'utilisateur
            $this->createMedicalRecord($user);
    
            // Rediriger vers le dashboard approprié selon le rôle
            return redirect()->route('dashboard')->with('success', 'Compte créé avec succès ! Bienvenue sur CareWell.');
    
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
                return redirect()->route('dashboard')->with('success', 'Connexion réussie ! Bienvenue sur CareWell.');
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
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number, // Inclure le numéro de téléphone
                'status' => $user->status, // Inclure le statut du compte
                'roles' => $user->getRoleNames(), // Récupérer les noms des rôles avec Spatie
            ];
        });

    return response()->json(['data' => $users]); // Assurez-vous que les utilisateurs sont dans 'data'
}


public function destroy(string $id)
{
    // Vérifier si l'article existe
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

    // Supprimer l'image associée si elle existe
    if ($user->photo) {
        Storage::disk('public')->delete($user->photo);
    }

    // Supprimer l'utilisateur
    $user->delete();

    return response()->json([
        'status' => true,
        'message' => 'Utilissateur supprimé avec succès'
    ], 200);
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

