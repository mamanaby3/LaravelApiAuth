<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Medecins;
use App\Models\Secretaire;
use App\Models\Patient;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string|in:medecin,secretaire,patient',
            'specialite_id' => 'nullable|exists:specialites,id', // only for 'medecin'
            'dateNaissance' => 'nullable|date', // only for 'patient'
        ]);

        try {
            // Création de l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);

            // Création de l'entrée dans la table correspondante selon le rôle
            switch ($request->role) {
                case 'medecin':
                    Medecins::create([
                        'user_id' => $user->id,
                        'specialite_id' => $request->specialite_id,
                    ]);
                    break;

                case 'secretaire':
                    Secretaire::create([
                        'user_id' => $user->id,
                    ]);
                    break;

                case 'patient':
                    Patient::create([
                        'user_id' => $user->id,
                        'dateNaissance' => $request->dateNaissance,
                    ]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validation = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides.'
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}
