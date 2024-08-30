<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Medecins;
use App\Models\Patient;
use App\Models\Secretaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tel' => ['required', 'string', 'max:15'],
            'role' => ['required', 'string', 'in:medecin,secretaire,patient'],
            'specialite_id' => 'nullable|integer',
            'dateNaissance' => ['nullable', 'date'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tel' => $request->tel,
            'role' => $request->role,
        ]);

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

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur enregistré et connecté avec succès.',
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ], 201);
    }
}
