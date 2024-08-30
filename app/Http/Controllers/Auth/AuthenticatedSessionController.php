<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides.'
            ], 401);
        }

        $user = Auth::user();

        // Récupère le rôle de l'utilisateur
        $role = $user->role;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'role' => $role, // Ajoute le rôle à la réponse
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}
