<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Contract;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class B2BPortalController extends Controller
{
    /**
     * Affiche le portail B2B avec la liste des véhicules et des contrats.
     */
    public function index()
    {
        $vehicles = Schema::hasTable('vehicles') ? Vehicle::all() : collect();
        $contracts = Schema::hasTable('contracts') ? Contract::all() : collect();

        return view('b2b.portal', compact('vehicles', 'contracts'));
    }

    /**
     * Enregistre une nouvelle demande de devis LLD soumise via le formulaire.
     */
    public function storeQuote(Request $request)
    {
        if (!Schema::hasTable('quote_requests')) {
            return redirect()->back()->withErrors([
                'database' => 'La table quote_requests est indisponible. Veuillez lancer les migrations avant de soumettre un devis.',
            ])->withInput();
        }

        // Validation des données reçues du formulaire
        $validated = $request->validate([
            'company_name'     => 'required|string|max:255',
            'contact_email'    => 'required|email|max:255',
            'vehicle_category' => 'required|string|max:255',
            'quantity'         => 'required|integer|min:1',
            'duration_months'  => 'required|integer|min:1',
            'annual_km'        => 'nullable|integer|min:0',
            'options'          => 'nullable|array',
        ]);

        // Enregistrement en base de données
        QuoteRequest::create([
            'company_name'     => $validated['company_name'],
            'contact_email'    => $validated['contact_email'],
            'vehicle_category' => $validated['vehicle_category'],
            'quantity'         => $validated['quantity'],
            'duration_months'  => $validated['duration_months'],
            'annual_km'        => $validated['annual_km'] ?? null,
            'options'          => $validated['options'] ?? null,
            'status'           => 'En étude',
        ]);

        // Redirection vers la page avec un message de succès
        return redirect()->route('b2b.portal')->with('success', 'Votre demande de devis a bien été transmise à l’équipe Alt Motors !');
    }
}