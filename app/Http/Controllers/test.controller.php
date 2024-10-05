<?php 

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
            // 'service_id' => 'required_if:role,Doctor|exists:services,id', // Validation pour le champ service_id
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
    public function login(Request $request){

        // Validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!$token){

            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in succcessfully",
            "token" => $token,
            //"expires_in" => auth()->factory()->getTTL() * 60
        ]);

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

public function updateProfile(Request $request)
{
    // Validation des données d'entrée
    $validateUser = Validator::make($request->all(), [
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'email' => 'nullable|string|email|max:255|unique:users,email,' . Auth::id(),
        'adress' => 'nullable|string|max:255',
        'phone_number' => 'nullable|regex:/^[0-9]{9}$/', // Assurez-vous que le numéro ne contient que 9 chiffres sans indicatif
        'day_of_birth' => 'nullable|date',
        'password' => 'nullable|string|min:8|confirmed', // Optionnel, mais doit être confirmé si fourni
        'photo' => 'nullable|file|image|max:2048', // Limite de 2 Mo pour les images
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
        // $user->save();

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


}

