<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanAbonnement;
use App\Models\LangueAbonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AbonnementPlanController extends Controller
{
    private function check(): void
    {
        abort_unless(auth()->user()->can('manage users'), 403,
            'Accès refusé.');
    }

    // ════════════════════════════════════════
    //  INDEX
    // ════════════════════════════════════════
    public function index()
    {
        $this->check();

        $plans = PlanAbonnement::withCount([
            'abonnements as total_abonnements',
            'abonnements as abonnements_actifs' => fn($q) =>
                $q->where('actif', true)->where('fin_at', '>=', now()),
        ])->orderBy('ordre')->get();

        $stats = [
            'total_abonnes'  => LangueAbonnement::where('actif', true)
                                             ->where('fin_at', '>=', now())->count(),
            'revenus_mois'   => LangueAbonnement::whereMonth('created_at', now()->month)
                                             ->whereYear('created_at', now()->year)
                                             ->where('statut_paiement', 'confirme')
                                             ->sum('montant'),
            'revenus_total'  => LangueAbonnement::where('statut_paiement', 'confirme')
                                             ->sum('montant'),
        ];

        return view('admin.abonnements.plans.index', compact('plans', 'stats'));
    }

    // ════════════════════════════════════════
    //  CREATE
    // ════════════════════════════════════════
    public function create()
    {
        $this->check();
        $icones = $this->getIcones();
        return view('admin.abonnements.plans.create', compact('icones'));
    }

    // ════════════════════════════════════════
    //  STORE ✅ CORRIGÉ AVEC TRY-CATCH
    // ════════════════════════════════════════
    public function store(Request $request)
    {
        $this->check();

        try {
            // ✅ Validation robuste
            $validated = $request->validate([
                'nom'              => 'required|string|max:100',
                'code'             => 'required|string|max:50|unique:plans_abonnements,code',
                'couleur'          => 'required|string|max:20',
                'icone'            => 'required|string|max:100',
                'description'      => 'nullable|string|max:255',
                'prix'             => 'required|numeric|min:0|max:9999999',
                'devise'           => 'required|string|max:10|in:XAF,EUR,USD,CAD',
                'duree_jours'      => 'required|integer|min:1|max:3650',
                'populaire'        => 'nullable|boolean',
                'points'           => 'nullable|array|max:20',
                'points.*.texte'   => 'required_with:points|string|max:255',
                'points.*.icone'   => 'required_with:points|string|max:100',
                'points.*.couleur' => 'nullable|string|max:20',
            ], [
                'nom.required'          => 'Le nom du plan est obligatoire.',
                'code.required'         => 'Le code est obligatoire.',
                'code.unique'           => 'Ce code de plan est déjà utilisé.',
                'prix.required'         => 'Le prix est obligatoire.',
                'prix.numeric'          => 'Le prix doit être un nombre.',
                'prix.max'              => 'Le prix est trop élevé.',
                'devise.required'       => 'La devise est obligatoire.',
                'duree_jours.required'  => 'La durée est obligatoire.',
                'duree_jours.integer'   => 'La durée doit être un nombre entier.',
                'duree_jours.min'       => 'La durée minimum est 1 jour.',
            ]);

            // ✅ Construire et créer le plan
            $plan = PlanAbonnement::create([
                'nom'         => $validated['nom'],
                'code'        => Str::slug($validated['code']),
                'couleur'     => $validated['couleur'],
                'icone'       => $validated['icone'],
                'description' => $validated['description'] ?? null,
                'prix'        => (float) $validated['prix'],
                'devise'      => $validated['devise'],
                'duree_jours' => (int) $validated['duree_jours'],
                'populaire'   => (bool) ($validated['populaire'] ?? false),
                'actif'       => true,
                'ordre'       => (PlanAbonnement::max('ordre') ?? 0) + 1,
                'points'      => $this->buildPoints($request),
            ]);

            return redirect()->route('admin.abonnements.plans.index')
                ->with('success', "✅ Plan « {$plan->nom} » créé avec succès !");

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Les erreurs de validation sont automatiquement redirigées
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Log l'erreur complète
            Log::error('Erreur création plan', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', '❌ Erreur lors de la création du plan : ' . $e->getMessage())
                ->withInput();
        }
    }

    // ════════════════════════════════════════
    //  EDIT
    // ════════════════════════════════════════
    public function edit(PlanAbonnement $plan)
    {
        $this->check();
        $icones = $this->getIcones();
        return view('admin.abonnements.plans.edit', compact('plan', 'icones'));
    }

    // ════════════════════════════════════════
    //  UPDATE ✅ CORRIGÉ
    // ════════════════════════════════════════
    public function update(Request $request, PlanAbonnement $plan)
    {
        $this->check();

        try {
            $validated = $request->validate([
                'nom'              => 'required|string|max:100',
                'couleur'          => 'required|string|max:20',
                'icone'            => 'required|string|max:100',
                'description'      => 'nullable|string|max:255',
                'prix'             => 'required|numeric|min:0|max:9999999',
                'devise'           => 'required|string|max:10|in:XAF,EUR,USD,CAD',
                'duree_jours'      => 'required|integer|min:1|max:3650',
                'populaire'        => 'nullable|boolean',
                'actif'            => 'nullable|boolean',
                'points'           => 'nullable|array|max:20',
                'points.*.texte'   => 'required_with:points|string|max:255',
                'points.*.icone'   => 'required_with:points|string|max:100',
                'points.*.couleur' => 'nullable|string|max:20',
            ]);

            $plan->update([
                'nom'         => $validated['nom'],
                'couleur'     => $validated['couleur'],
                'icone'       => $validated['icone'],
                'description' => $validated['description'] ?? null,
                'prix'        => (float) $validated['prix'],
                'devise'      => $validated['devise'],
                'duree_jours' => (int) $validated['duree_jours'],
                'populaire'   => (bool) ($validated['populaire'] ?? false),
                'actif'       => (bool) ($validated['actif'] ?? true),
                'points'      => $this->buildPoints($request),
            ]);

            return back()->with('success', '✅ Plan mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour plan', [
                'plan_id' => $plan->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', '❌ Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    // ════════════════════════════════════════
    //  DESTROY
    // ════════════════════════════════════════
    public function destroy(PlanAbonnement $plan)
    {
        $this->check();

        // Vérifier qu'il n'y a pas d'abonnements actifs
        if ($plan->abonnements()->where('actif', true)->exists()) {
            return back()->with('error',
                '❌ Impossible de supprimer : ce plan a des abonnements actifs.'
            );
        }

        $nom = $plan->nom;
        $plan->delete();

        return back()->with('success', "✅ Plan « {$nom} » supprimé.");
    }

    // ════════════════════════════════════════
    //  TOGGLE actif/inactif
    // ════════════════════════════════════════
    public function toggle(PlanAbonnement $plan)
    {
        $this->check();
        $plan->update(['actif' => !$plan->actif]);
        
        return back()->with('success',
            $plan->fresh()->actif
                ? "✅ Plan activé."
                : "⚠️ Plan désactivé."
        );
    }

    // ════════════════════════════════════════
    //  PRIVATE HELPERS
    // ════════════════════════════════════════
    private function buildPoints(Request $request): array
    {
        $points = [];
        if ($request->filled('points') && is_array($request->points)) {
            foreach ($request->points as $p) {
                $texte = trim($p['texte'] ?? '');
                if (!empty($texte)) {
                    $points[] = [
                        'icone'   => $p['icone']   ?? 'bi-check-circle-fill',
                        'couleur' => $p['couleur'] ?? '#1cc88a',
                        'texte'   => $texte,
                    ];
                }
            }
        }
        return $points;
    }

    public function getIcones(): array
    {
        return [
            'Validation' => [
                ['class' => 'bi-check-circle-fill',    'label' => 'Check vert'],
                ['class' => 'bi-check-circle',         'label' => 'Check vide'],
                ['class' => 'bi-check-square-fill',    'label' => 'Carré check'],
                ['class' => 'bi-x-circle-fill',        'label' => 'Croix rouge'],
                ['class' => 'bi-x-square-fill',        'label' => 'Carré croix'],
                ['class' => 'bi-dash-circle-fill',     'label' => 'Tiret'],
            ],
            'Étoiles & Récompenses' => [
                ['class' => 'bi-star-fill',            'label' => 'Étoile'],
                ['class' => 'bi-star-half',            'label' => 'Demi-étoile'],
                ['class' => 'bi-trophy-fill',          'label' => 'Trophée'],
                ['class' => 'bi-award-fill',           'label' => 'Médaille'],
                ['class' => 'bi-gem',                  'label' => 'Diamant'],
                ['class' => 'bi-crown-fill',           'label' => 'Couronne'],
            ],
            'Flèches & Puces' => [
                ['class' => 'bi-arrow-right-circle-fill','label' => 'Flèche cercle'],
                ['class' => 'bi-caret-right-fill',     'label' => 'Caret'],
                ['class' => 'bi-chevron-right',        'label' => 'Chevron'],
                ['class' => 'bi-dot',                  'label' => 'Point'],
                ['class' => 'bi-circle-fill',          'label' => 'Cercle'],
                ['class' => 'bi-square-fill',          'label' => 'Carré'],
                ['class' => 'bi-diamond-fill',         'label' => 'Losange'],
            ],
            'Éclairs & Énergie' => [
                ['class' => 'bi-lightning-charge-fill','label' => 'Éclair'],
                ['class' => 'bi-fire',                 'label' => 'Feu'],
                ['class' => 'bi-rocket-fill',          'label' => 'Fusée'],
                ['class' => 'bi-lightning-fill',       'label' => 'Lightning'],
            ],
            'Général' => [
                ['class' => 'bi-shield-fill-check',    'label' => 'Bouclier'],
                ['class' => 'bi-infinity',             'label' => 'Infini'],
                ['class' => 'bi-lock-fill',            'label' => 'Cadenas'],
                ['class' => 'bi-unlock-fill',          'label' => 'Déverrouillé'],
                ['class' => 'bi-gift-fill',            'label' => 'Cadeau'],
                ['class' => 'bi-heart-fill',           'label' => 'Cœur'],
                ['class' => 'bi-person-check-fill',    'label' => 'Personne check'],
                ['class' => 'bi-headset',              'label' => 'Support'],
            ],
        ];
    }
}