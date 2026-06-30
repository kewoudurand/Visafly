{{-- resources/views/client/suivi.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Suivi de mon dossier')

@push('styles')
<style>
.suivi-card { background:#fff; border-radius:14px; border:1px solid #eee; padding:24px; box-shadow:0 2px 12px rgba(27,58,107,.05); }
.timeline { position:relative; padding-left:30px; }
.timeline::before { content:''; position:absolute; left:10px; top:0; bottom:0; width:2px; background:#eee; }
.tl-item { position:relative; margin-bottom:20px; }
.tl-dot { position:absolute; left:-26px; top:4px; width:14px; height:14px; border-radius:50%; background:#eee; border:2px solid #fff; box-shadow:0 0 0 2px #eee; }
.tl-dot.active  { background:#1B3A6B; box-shadow:0 0 0 2px #1B3A6B; }
.tl-dot.done    { background:#1cc88a; box-shadow:0 0 0 2px #1cc88a; }
.tl-dot.warning { background:#F5A623; box-shadow:0 0 0 2px #F5A623; }
.tl-dot.danger  { background:#e24b4a; box-shadow:0 0 0 2px #e24b4a; }

.badge-statut { padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700; display:inline-block; }
.s-en_attente { background:rgba(245,166,35,.12); color:#633806; }
.s-en_cours   { background:rgba(27,58,107,.1);   color:#1B3A6B; }
.s-approuvee  { background:rgba(28,200,138,.1);  color:#0f6e56; }
.s-declinee   { background:rgba(226,75,74,.1);   color:#a32d2d; }
.s-annulee    { background:#f0f0f0; color:#888; }
.s-terminee   { background:rgba(127,119,221,.12);color:#3C3489; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.5rem;">
      📋 Suivi de mon dossier
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      Bonjour {{ Auth::user()->first_name }} — retrouvez ici l'évolution de vos consultations.
    </p>
  </div>
</div>

@if($consultations->isEmpty())
  <div class="suivi-card text-center py-5">
    <i class="bi bi-folder-x" style="font-size:40px;color:#ccc;display:block;margin-bottom:12px;"></i>
    <p class="text-muted" style="font-size:14px;">Aucune consultation enregistrée pour votre compte.</p>
    <p class="text-muted" style="font-size:13px;">Contactez nos bureaux pour ouvrir un dossier.</p>
  </div>
@else
  @foreach($consultations as $c)
  <div class="suivi-card mb-4">
    {{-- En-tête de la consultation --}}
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
      <div>
        <div style="font-size:15px;font-weight:800;color:#1B3A6B;">
          {{ $c->objet ?? $c->project_type ?? 'Consultation #' . $c->id }}
        </div>
        <div style="font-size:12px;color:#888;margin-top:2px;">
          Ouverte le {{ $c->created_at->format('d/m/Y à H:i') }}
          @if($c->consultant)
            · <i class="bi bi-person-badge me-1"></i>Consultant : <strong>{{ $c->consultant->first_name }} {{ $c->consultant->last_name }}</strong>
          @endif
        </div>
      </div>
      @php
        $labels = [
          'en_attente'=>'⏳ En attente',
          'en_cours'  =>'⚙️ En cours',
          'approuvee' =>'✅ Approuvée',
          'declinee'  =>'❌ Déclinée',
          'annulee'   =>'🚫 Annulée',
          'terminee'  =>'🏁 Terminée',
        ];
        $statut = $c->statut ?? 'en_attente';
      @endphp
      <span class="badge-statut s-{{ $statut }}">{{ $labels[$statut] ?? $statut }}</span>
    </div>

    {{-- Timeline de progression --}}
    <div class="timeline mt-3">

      @php
        $etapes = [
          'en_attente' => ['label' => 'Dossier reçu', 'desc'  => 'Votre demande a bien été enregistrée et est en attente d\'examen.'],
          'en_cours'   => ['label' => 'Analyse en cours', 'desc'  => 'Votre consultant examine actuellement votre dossier.'],
          'approuvee'  => ['label' => 'Dossier approuvé', 'desc'  => 'Votre dossier est validé. Un entretien ou livrable est planifié.'],
          'terminee'   => ['label' => 'Consultation clôturée', 'desc'  => 'Votre consultation est terminée. Merci de votre confiance.'],
        ];

        $ordre = ['en_attente', 'en_cours', 'approuvee', 'terminee'];
        $indexActuel = array_search($statut, $ordre);
      @endphp

      @foreach($ordre as $i => $etapeKey)
        @php
          if ($i < $indexActuel) {
            $dotClass = 'done';
          } elseif ($i === $indexActuel) {
            $dotClass = 'active';
          } else {
            $dotClass = '';
          }
          $etape = $etapes[$etapeKey];
        @endphp
        <div class="tl-item">
          <div class="tl-dot {{ $dotClass }}"></div>
          <div style="font-size:13px;font-weight:{{ $dotClass === 'active' ? '700' : '500' }};color:{{ $dotClass === 'active' ? '#1B3A6B' : ($dotClass === 'done' ? '#1cc88a' : '#aaa') }};">
            {{ $etape['label'] }}
          </div>
          @if($dotClass === 'active')
            <div style="font-size:12px;color:#666;margin-top:2px;">{{ $etape['desc'] }}</div>
          @endif
        </div>
      @endforeach

      {{-- Cas particuliers : déclinée / annulée --}}
      @if(in_array($statut, ['declinee', 'annulee']))
        <div class="tl-item">
          <div class="tl-dot danger"></div>
          <div style="font-size:13px;font-weight:700;color:#e24b4a;">
            {{ $statut === 'declinee' ? 'Dossier décliné' : 'Consultation annulée' }}
          </div>
          <div style="font-size:12px;color:#999;margin-top:2px;">
            Contactez notre équipe pour plus d'informations.
          </div>
        </div>
      @endif
    </div>

    {{-- Récap du projet --}}
    <div class="mt-3 pt-3" style="border-top:1px solid #f5f5f5;">
      <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
        Récapitulatif du projet
      </div>
      <div class="row g-2" style="font-size:12px;color:#555;">
        <div class="col-6 col-md-3">
          <span style="color:#1B3A6B;font-weight:600;">Objectif</span><br>
          {{ $c->project_type ?? '—' }}
        </div>
        <div class="col-6 col-md-3">
          <span style="color:#1B3A6B;font-weight:600;">Destination</span><br>
          {{ $c->destination_country ?? '—' }}
        </div>
        <div class="col-6 col-md-3">
          <span style="color:#1B3A6B;font-weight:600;">Nationalité</span><br>
          {{ $c->nationality ?? '—' }}
        </div>
        <div class="col-6 col-md-3">
          <span style="color:#1B3A6B;font-weight:600;">Diplôme</span><br>
          {{ $c->last_degree ?? '—' }}
        </div>
      </div>
    </div>

    @if($c->date_confirmee)
    <div class="mt-3 p-3 rounded-3 d-flex align-items-center gap-2"
         style="background:rgba(27,58,107,.05);border:1px solid rgba(27,58,107,.1);">
      <i class="bi bi-calendar-check" style="color:#F5A623;font-size:16px;"></i>
      <div style="font-size:13px;">
        <strong style="color:#1B3A6B;">Rendez-vous confirmé :</strong>
        {{ \Carbon\Carbon::parse($c->date_confirmee)->locale('fr')->isoFormat('dddd D MMMM YYYY [à] HH[h]mm') }}
      </div>
    </div>
    @endif
  </div>
  @endforeach
@endif

@endsection