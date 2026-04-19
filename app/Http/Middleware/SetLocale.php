<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App; // TRÈS IMPORTANT
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // On vérifie si la session "locale" existe
        if (Session::has('locale')) {
            // On force Laravel à utiliser cette langue pour cette requête
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}