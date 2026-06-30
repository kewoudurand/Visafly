{{-- resources/views/consultation/merci.blade.php --}}
@extends('layouts.app')
@section('title', 'Demande envoyée — VisaFly')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem;">
  <div style="max-width:560px;width:100%;text-align:center;">

    {{-- Icône succès animée --}}
    <div style="width:90px;height:90px;border-radius:50%;background:rgba(28,200,138,.1);
                border:2px solid rgba(28,200,138,.3);display:flex;align-items:center;
                justify-content:center;margin:0 auto 28px;
                animation:popIn .5s cubic-bezier(.68,-.55,.27,1.55) both;">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="12" r="12" fill="rgba(28,200,138,.15)"/>
        <path d="M6 12.5l4 4 8-8" stroke="#1cc88a" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    {{-- Titre --}}
    <h1 style="font-size:1.8rem;font-weight:800;color:#1B3A6B;margin-bottom:10px;">
      Demande envoyée !
    </h1>
    <p style="font-size:15px;color:#666;line-height:1.7;margin-bottom:32px;">
      Votre demande de consultation a bien été reçue.<br>
      Notre équipe va l'examiner et vous contactera dans les
      <strong style="color:#1B3A6B;">24 à 48 heures</strong> pour confirmer votre rendez-vous.
    </p>

    {{-- Carte récap --}}
    <div style="background:#fff;border:1px solid rgba(27,58,107,.1);border-radius:16px;
                padding:24px;margin-bottom:32px;text-align:left;
                box-shadow:0 4px 20px rgba(27,58,107,.06);">
      <div style="font-size:11px;font-weight:700;color:#F5A623;text-transform:uppercase;
                  letter-spacing:.8px;margin-bottom:16px;">Ce qui se passe ensuite</div>
      @foreach([
        ['bi-envelope-check','Confirmation par email','Vous recevrez un email de confirmation avec les détails.'],
        ['bi-calendar-check','Rendez-vous confirmé','Notre consultant vous proposera une date et un créneau.'],
        ['bi-camera-video','Consultation','Échangez avec notre expert par vidéo, téléphone ou en présentiel.'],
      ] as [$icon, $titre, $desc])
      <div style="display:flex;gap:14px;margin-bottom:16px;align-items:flex-start;">
        <div style="width:36px;height:36px;border-radius:9px;background:rgba(27,58,107,.07);
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="bi {{ $icon }}" style="color:#1B3A6B;font-size:16px;"></i>
        </div>
        <div>
          <div style="font-size:13px;font-weight:700;color:#1B3A6B;">{{ $titre }}</div>
          <div style="font-size:12px;color:#888;margin-top:2px;">{{ $desc }}</div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Boutons --}}
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
      @auth
        <a href="{{ route('dashboard.index') }}"
           style="padding:11px 28px;background:#1B3A6B;color:#fff;border-radius:25px;
                  font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;">
          <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
        </a>
      @endauth
      <a href="{{ url('/') }}"
         style="padding:11px 28px;border:1.5px solid #1B3A6B;color:#1B3A6B;border-radius:25px;
                font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;">
        <i class="bi bi-house me-2"></i>Retour à l'accueil
      </a>
    </div>

  </div>
</div>

<style>
@keyframes popIn {
  0%   { transform: scale(0); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}
</style>
@endsection