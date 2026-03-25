<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ConsultationController extends Controller
{
    /**
     * Affiche le formulaire de consultation (GET).
     */
    public function create()
    {
        [$nationalities, $countries] = Cache::remember('countries_data', now()->addHours(24), function () {

            try {
                // Timeout réduit à 5 secondes
                $response = Http::timeout(5)->get('https://restcountries.com/v3.1/all?fields=name,demonyms');

                if ($response->successful()) {
                    $data = $response->json();

                    $nationalities = collect($data)
                        ->map(fn($c) => $c['demonyms']['fra']['m']
                                    ?? $c['demonyms']['eng']['m']
                                    ?? $c['name']['common']
                                    ?? null)
                        ->filter()->unique()->sort()->values();

                    $countries = collect($data)
                        ->map(fn($c) => $c['name']['common'] ?? null)
                        ->filter()->unique()->sort()->values();

                    return [$nationalities, $countries];
                }
            } catch (\Exception $e) {
                // L'API est indisponible → fallback liste locale
            }

            // ── Fallback local ──
            return [
                collect(['Camerounais', 'Français', 'Canadien', 'Allemand',
                        'Belge', 'Portugais', 'Sénégalais', 'Ivoirien',
                        'Congolais', 'Malien', 'Burkinabè'])->sort()->values(),
                collect(['Cameroun', 'France', 'Canada', 'Allemagne',
                        'Belgique', 'Portugal', 'Sénégal', 'Côte d\'Ivoire',
                        'Congo', 'Mali', 'Burkina Faso'])->sort()->values(),
            ];
        });

        return view('users.consultation', compact('nationalities', 'countries'));
    }
        /**
     * Enregistre la consultation (POST).
     */
    public function store(Request $request)
    {
        
        $data = $request->validate([
            'full_name' => 'required|string',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string',
            'residence_country' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string|email',
            'profession' => 'nullable|string',

            'project_type' => 'nullable|string',
            'destination_country' => 'nullable|string',

            'visa_history' => 'nullable|boolean',
            'visa_history_details' => 'nullable|string',

            'last_degree' => 'nullable|string',
            'graduation_year' => 'nullable|string',
            'field_of_study' => 'nullable|string',
            'language_level' => 'nullable|string',
            'work_experience' => 'nullable|string',

            'passport_valid' => 'nullable|boolean',
            'documents_available' => 'nullable|boolean',
            'admission_or_contract' => 'nullable|boolean',
            'financial_proof' => 'nullable|boolean',

            'budget' => 'nullable|string',
            'departure_date' => 'nullable|string',
            'referral_source' => 'nullable|string',
            'message' => 'nullable|string',

            'need_consultation' => 'nullable|boolean',
        ]);

        //dd($request->all());

        Consultation::create($data);

        return redirect('/')->with('success', 'Votre demande de consultation a été enregistrée.');
    }


    /**
     * Liste les consultations en back-office.
     */
    public function index()
    {
        $consultations = Consultation::latest()->get();
        return view('admin.dashboard', compact('consultations'));
    }
}

