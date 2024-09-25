<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Mail\WelcomeMail;
use App\Mail\MedicalFileMail;

use App\Models\MedicalFile;

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
            'phone_number' => 'required|regex:/^[0-9]{9}$/', // Suppression de l'indicatif dans la regex
            'day_of_birth' => 'required', // Peut être soit un âge soit une date
            'password' => 'required|string|min:8',
            'photo' => 'nullable|file|image|max:2048', // Limite de 2 Mo pour les images
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
    
            // Retourner une réponse JSON avec un token
            return response()->json([
                'status' => true,
                'message' => 'Le compte a été créé avec succès',
                'token' => $token
            ], 201);
    
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
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            // Retourner les erreurs de validation si présentes
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            // Tentative de connexion
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => "L'email ou le mot de passe ne correspond pas",
                ], 401);
            }



            // Récupération de l'utilisateur authentifié
            $user = User::where('email', $request->email)->first();

            // Création automatique d'un dossier médical pour l'utilisateur
            $this->createMedicalRecord($user);
            
                    // Envoi de l'email de notification
        Mail::to($user->email)->send(new \App\Mail\MedicalfileMail($user));

            // Création du token
            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Vous êtes connecté',
                'token' => $token,
                'user' => $user
            ], 200);

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
    public function profile()
    {
        $userData = Auth::user();
        return response()->json([
            'status' => true,
            'message' => "Page d'information",
            'data' => $userData,
            'id' => Auth::user()->id,
        ], 200);
    }


    /**
     * Déconnexion de l'utilisateur.
     */
    public function logout()
    {
        Auth::user()->tokens()->delete(); // Utiliser 'Auth' avec une majuscule
        return response()->json([
        'status' => true,
        'message' => "Vous êtes déconnecté",
        'data' => [],
        ], 200);
    }


    /**
     * Obtenir tous les utilisateurs.
     */
    public function getUsers()
    {
        $users = User::all();
        return response()->json(['data' => $users]);
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

//     public function sendWelcomeEmail($userId)
// {
//     // Récupérer l'utilisateur avec son dossier médical
//     $user = User::with('medicalFile')->findOrFail($userId);

//     // Envoyer l'e-mail
//     Mail::to($user->email)->send(new WelcomeMail($user));

//     return response()->json(['message' => 'E-mail de bienvenue envoyé avec succès.']);
// }
}





