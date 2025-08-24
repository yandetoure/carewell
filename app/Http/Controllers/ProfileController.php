<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'day_of_birth' => 'required|date',
            'adress' => 'required|string|max:500',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:20|max:300',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'biographie' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        // Stocker la nouvelle photo
        $path = $request->file('photo')->store('users/photos', 'public');
        $user->update(['photo' => $path]);

        return redirect()->back()->with('success', 'Votre photo de profil a été mise à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
