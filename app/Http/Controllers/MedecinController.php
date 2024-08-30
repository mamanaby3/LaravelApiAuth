<?php

namespace App\Http\Controllers;

use App\Models\Medecins;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MedecinController extends Controller
{private function getSpecialiteIdByName($name)
{
    $specialite = Specialite::where('nom', $name)->first();
    return $specialite ? $specialite->id : null;
}

    public function index(Request $request)
    {
        try {
            $request->validate([
                'nom' => 'sometimes|string',
                'specialite' => 'sometimes|string',
            ]);

            $query = Medecins::query()->with(['user.specialite']);

            if ($request->has('nom')) {
                $nom = $request->input('nom');
                Log::info('Searching for nom: ' . $nom);
                $query->whereHas('user', function($q) use ($nom) {
                    $q->where('name', 'LIKE', '%' . $nom . '%');
                });
            }

            if ($request->has('specialite')) {
                $specialiteName = $request->input('specialite');
                Log::info('Searching for specialite: ' . $specialiteName);
                $specialiteId = $this->getSpecialiteIdByName($specialiteName);

                if ($specialiteId) {
                    $query->whereHas('user', function($q) use ($specialiteId) {
                        $q->where('specialite_id', $specialiteId);
                    });
                } else {
                    // Optionally handle the case where the specialite is not found
                    Log::info('Specialite not found: ' . $specialiteName);
                    return response()->json([], 404);
                }
            }

            $medecins = $query->get();

            Log::info('Medecins retrieved:', $medecins->toArray());

            return response()->json($medecins);
        } catch (\Exception $e) {
            Log::error('Error retrieving medecins: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des médecins'], 500);
        }
    }
}
