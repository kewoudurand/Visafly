<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLanguageSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Paramètre: 'language' ou 'course' ou 'lesson'
        $languageId = $request->route('language') 
                   ?? $request->route('course')?->langue_id
                   ?? $request->route('lesson')?->course->langue_id;

        if (!$languageId) {
            return $next($request);
        }

        // Vérifier l'abonnement
        $subscription = $user->langueAbonnements()
            ->where('langue_id', $languageId)
            ->where('actif', true)
            ->where(function($q) {
                $q->whereNull('fin_at')
                  ->orWhere('fin_at', '>', now());
            })
            ->first();

        if (!$subscription) {
            return redirect('/langues')->with('error', 'Abonnement expiré ou non disponible');
        }

        return $next($request);
    }
}