<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VehicleController extends Controller {
    // Afficher la page de suivi de flotte
    public function index() {
        $vehicles = Schema::hasTable('vehicles') ? Vehicle::all() : collect();
        return view('fleet.live-tracking', compact('vehicles'));
    }

    // API pour mettre à jour la position GPS d'un véhicule en temps réel
    public function updateCoordinates(Request $request, $id) {
        if (!Schema::hasTable('vehicles')) {
            return response()->json(['success' => false, 'message' => 'La flotte n\'est pas disponible.'], 503);
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'last_seen_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Position mise à jour avec succès']);
    }
}
