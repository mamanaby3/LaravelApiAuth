<?php

namespace App\Http\Controllers;

use App\Models\Medecins;
use Illuminate\Http\Request;
use App\Models\Specialite;

class SpecialiteController extends Controller
{
    public function index(Request $request)
    {
        // Validation de la requête pour s'assurer que le nom est présent
        $specialites = Specialite::all();
        return response()->json($specialites);
    }

    // Méthode pour rechercher des spécialités par nom
    public function search(Request $request)
    {
        $name = $request->input('name');

        // Trouver les spécialités qui correspondent au nom
        $specialites = Specialite::where('nom', 'LIKE', "%$name%")->get();

        $medecins = Medecins::whereIn('specialite_id', $specialites->pluck('id'))->get();

        return response()->json($medecins);
    }
}

