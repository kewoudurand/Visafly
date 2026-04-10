{{-- resources/views/users/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mon espace')
@section('meta_description', 'VisaFly accompagne les Camerounais pour leurs démarches d’immigration et d’études au Canada.')

@push('styles')
<style>
.stu-card{background:#fff;border-radius:14px;border:1px solid #eee;
          padding:20px;box-shadow:0 2px 12px rgba(27,58,107,.05);}
.stu-stat-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.stu-stat-lbl{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;}
.badge-consult{padding:3px 11px;border-radius:20px;font-size:11px;font-weight:600;}
.s-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.s-en_cours  {background:rgba(27,58,107,.1); color:#1B3A6B;}
.s-approuvee {background:rgba(28,200,138,.1);color:#0f6e56;}
.s-declinee  {background:rgba(226,75,74,.1); color:#a32d2d;}
.s-annulee   {background:#f0f0f0;color:#888;}
.s-terminee  {background:rgba(127,119,221,.12);color:#3C3489;}
.quick-btn{display:flex;align-items:center;gap:10px;padding:14px 16px;
           border-radius:12px;text-decoration:none;transition:all .2s;border:1px solid #eee;
           background:#fff;color:#1B3A6B;font-size:13px;font-weight:600;}
.quick-btn:hover{border-color:#F5A623;box-shadow:0 4px 14px rgba(245,166,35,.15);
                 transform:translateY(-1px);color:#1B3A6B;}
.quick-btn-icon{width:38px;height:38px;border-radius:10px;display:flex;
                align-items:center;justify-content:center;font-size:17px;flex-shrink:0;}
.epreuve-item{display:flex;align-items:center;justify-content:space-between;
              padding:14px 0;border-bottom:1px solid #f5f5f5;}
.epreuve-item:last-child{border-bottom:none;}
.epreuve-info{flex:1;}
.epreuve-titre{font-size:13px;font-weight:600;color:#333;margin-bottom:3px;}
.epreuve-date{font-size:11px;color:#888;}
.epreuve-score{text-align:right;}
.epreuve-score-val{font-size:1.4rem;font-weight:800;line-height:1;}
.epreuve-score-lbl{font-size:10px;color:#888;margin-top:2px;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.5rem;">
      Bonjour, {{ Auth::user()->first_name }} 👋
    </h2>
    <p class="text-muted mb-0" style="font-size:13px;">
      {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
    </p>
  </div>
  <a href="{{ route('consultations.create') }}"
     style="padding:10px 22px;background:#F5A623;color:#1B3A6B;border-radius:25px;
            font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;
            box-shadow:0 4px 14px rgba(245,166,35,.3);">
    <i class="bi bi-plus-circle me-2"></i>Nouvelle consultation
  </a>
</div>

{{-- Alertes session --}}
@if(session('success'))
  <div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
      style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
      style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.3);color:#a32d2d;">
    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
  </div>
@endif

{{-- ══ STATS ══ --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#1B3A6B;">{{ $stats['tests_passes'] ?? 0 }}</div>
      <div class="stu-stat-lbl">Épreuves terminées</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#1cc88a;">{{ $stats['score_moyen'] ?? 0 }}<span style="font-size:1rem;">%</span></div>
      <div class="stu-stat-lbl">Score moyen</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stu-card">
      <div class="stu-stat-num" style="color:#F5A623;">{{ $stats['consultations_total'] ?? 0 }}</div>
      <div class="stu-stat-lbl">Consultations</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stu-card" style="{{ ($stats['abonnement_actif'] ?? false) ? 'border-color:rgba(28,200,138,.3);' : '' }}">
      <div class="stu-stat-num" style="color:{{ ($stats['abonnement_actif'] ?? false) ? '#1cc88a' : '#ccc' }};">
        <i class="bi bi-patch-check{{ ($stats['abonnement_actif'] ?? false) ? '-fill' : '' }}" style="font-size:1.6rem;"></i>
      </div>
      <div class="stu-stat-lbl">
        {{ ($stats['abonnement_actif'] ?? false) ? 'Abonnement actif' : 'Aucun abonnement' }}
      </div>
    </div>
  </div>
</div>

<div class="row g-4">

  {{-- ══ Colonne gauche ══ --}}
  <div class="col-lg-7">

    {{-- Consultations récentes --}}
    <div class="stu-card mb-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
          <i class="bi bi-calendar-check me-2" style="color:#F5A623;"></i>Mes consultations
        </div>
        <a href="{{ route('consultations.create') }}"
           style="font-size:12px;color:#F5A623;font-weight:600;text-decoration:none;">
          + Nouvelle
        </a>
      </div>
      @forelse($consultations as $c)
      <div style="display:flex;align-items:center;justify-content:space-between;
                  padding:12px 0;border-bottom:1px solid #f5f5f5;">
        <div style="flex:1;min-width:0;">
          <div style="font-size:13px;font-weight:600;color:#1B3A6B;
                      white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            {{ $c->objet ?? ($c->project_type ?? 'Consultation') }}
          </div>
          <div style="font-size:11px;color:#888;margin-top:2px;">
            Soumise le {{ $c->created_at->format('d/m/Y') }}
            @if(isset($c->date_confirmee) && $c->date_confirmee)
              · RDV : {{ \Carbon\Carbon::parse($c->date_confirmee)->format('d/m/Y à H:i') }}
            @endif
          </div>
        </div>
        <div class="ms-3 flex-shrink-0">
          @php
            $statut = $c->statut ?? ($c->status == 1 ? 'approuvee' : 'en_attente');
            $labels = [
              'en_attente'=>'En attente',
              'en_cours'=>'En cours',
              'approuvee'=>'Approuvée',
              'declinee'=>'Déclinée',
              'annulee'=>'Annulée',
              'terminee'=>'Terminée'
            ];
          @endphp
          <span class="badge-consult s-{{ $statut }}">
            {{ $labels[$statut] ?? ucfirst($statut) }}
          </span>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:24px 0;color:#aaa;">
        <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        <span style="font-size:13px;">Aucune consultation pour le moment.</span>
        <div style="margin-top:10px;">
          <a href="{{ route('consultations.create') }}"
             style="font-size:13px;color:#F5A623;font-weight:600;text-decoration:none;">
            Soumettre une demande →
          </a>
        </div>
      </div>
      @endforelse
    </div>

    {{-- ✅ CORRIGÉ : Épreuves TCF récentes --}}
    <div class="stu-card">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="font-size:14px;font-weight:700;color:#1B3A6B;">
          <i class="bi bi-journal-check me-2" style="color:#F5A623;"></i>Mes épreuves TCF/TEF
        </div>
        <a href="{{ route('langues.index') }}"
           style="font-size:12px;color:#F5A623;font-weight:600;text-decoration:none;">
          Toutes les séries →
        </a>
      </div>

      @forelse($passages as $p)
      <div class="epreuve-item">
        <div class="epreuve-info">
          <div class="epreuve-titre">
            {{ optional($p->serie)->nom ?? 'Série' }} 
            @if($p->discipline)
              — {{ $p->discipline->nom }}
            @endif
          </div>
          <div class="epreuve-date">{{ $p->created_at->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="epreuve-score">
          @if($p->statut === 'termine' && $p->score !== null)
            <div class="epreuve-score-val" style="color:{{ $p->scoreColor() }};">
              {{ $p->scoreLabel() }}
            </div>
            <div class="epreuve-score-lbl">Score: {{ $p->niveauAtteint() }}</div>
          @else
            <span style="font-size:11px;padding:2px 8px;border-radius:8px;
                         background:rgba(245,166,35,.1);color:#633806;">En cours</span>
          @endif
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:24px 0;color:#aaa;">
        <i class="bi bi-journal" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        <span style="font-size:13px;">Vous n'avez pas encore passé d'épreuve.</span>
        <div style="margin-top:10px;">
          <a href="{{ route('langues.index') }}"
             style="font-size:13px;color:#F5A623;font-weight:600;text-decoration:none;">
            Commencer une épreuve →
          </a>
        </div>
      </div>
      @endforelse
    </div>

  </div>

  {{-- ══ Colonne droite ══ --}}
  <div class="col-lg-5">

    {{-- ✅ CORRIGÉ : Abonnement --}}
    <div class="stu-card mb-4"
         style="{{ ($abonnement && $abonnement->isActif()) ? 'background:#1B3A6B;' : '' }}">
      <div style="font-size:11px;font-weight:700;
                  color:{{ ($abonnement && $abonnement->isActif()) ? 'rgba(255,255,255,.7)' : '#888' }};
                  margin-bottom:12px;text-transform:uppercase;letter-spacing:.6px;">
        Abonnement
      </div>
      @if($abonnement && $abonnement->isActif())
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div style="width:40px;height:40px;border-radius:10px;
                      background:rgba(245,166,35,.2);display:flex;
                      align-items:center;justify-content:center;">
            <i class="bi bi-patch-check-fill" style="color:#F5A623;font-size:20px;"></i>
          </div>
          <div>
            <div style="font-size:15px;font-weight:800;color:#F5A623;">
              {{ $abonnement->plan->nom ?? 'Plan' }}
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.6);">
              Expire le {{ $abonnement->fin_at->format('d/m/Y') }}
              ({{ $abonnement->joursRestants() }} jours restants)
            </div>
          </div>
        </div>
        <div style="background:rgba(255,255,255,.1);border-radius:8px;
                    padding:10px 12px;font-size:12px;color:rgba(255,255,255,.7);">
          <i class="bi bi-check-circle-fill me-2" style="color:#1cc88a;"></i>
          Accès illimité à toutes les séries TCF/TEF
        </div>
        @if($abonnement->estBientotExpire())
        <div style="margin-top:10px;background:rgba(245,166,35,.2);border-radius:8px;
                    padding:10px 12px;font-size:11px;color:rgba(255,255,255,.8);">
          <i class="bi bi-exclamation-circle me-2"></i>
          Votre abonnement expire bientôt !
        </div>
        @endif
      @else
        <div style="font-size:13px;color:#888;margin-bottom:14px;line-height:1.6;">
          Passez à la version premium pour accéder à toutes les séries d'entraînement.
        </div>
        <a href="{{ route('abonnement.index') }}"
           style="display:block;text-align:center;padding:11px;background:#1B3A6B;
                  color:#fff;border-radius:25px;font-size:13px;font-weight:600;
                  text-decoration:none;">
          <i class="bi bi-lightning-fill me-2"></i>Voir les forfaits
        </a>
      @endif
    </div>

    {{-- Accès rapides --}}
    <div class="stu-card mb-4">
      <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;
                  letter-spacing:.6px;margin-bottom:14px;">Accès rapides</div>
      <div style="display:flex;flex-direction:column;gap:8px;">
        <a href="{{ route('langues.index') }}" class="quick-btn">
          <div class="quick-btn-icon" style="background:rgba(27,58,107,.08);">
            <i class="bi bi-book" style="color:#1B3A6B;"></i>
          </div>
          <div>
            <div>Passer une épreuve TCF</div>
            <div style="font-size:11px;color:#888;font-weight:400;">Séries disponibles</div>
          </div>
          <i class="bi bi-chevron-right ms-auto" style="font-size:11px;color:#ccc;"></i>
        </a>
        <a href="{{ route('consultations.create') }}" class="quick-btn">
          <div class="quick-btn-icon" style="background:rgba(245,166,35,.1);">
            <i class="bi bi-calendar-plus" style="color:#F5A623;"></i>
          </div>
          <div>
            <div>Demander une consultation</div>
            <div style="font-size:11px;color:#888;font-weight:400;">Expert immigration</div>
          </div>
          <i class="bi bi-chevron-right ms-auto" style="font-size:11px;color:#ccc;"></i>
        </a>
        <a href="{{ route('abonnement.index') }}" class="quick-btn">
          <div class="quick-btn-icon" style="background:rgba(28,200,138,.08);">
            <i class="bi bi-credit-card" style="color:#1cc88a;"></i>
          </div>
          <div>
            <div>Gérer mon abonnement</div>
            <div style="font-size:11px;color:#888;font-weight:400;">Forfaits TCF/TEF</div>
          </div>
          <i class="bi bi-chevron-right ms-auto" style="font-size:11px;color:#ccc;"></i>
        </a>
      </div>
    </div>

    {{-- ══ Profil rapide + bouton modifier ══ --}}
    <div class="stu-card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.6px;">
          Mon profil
        </div>
        <a href="{{ route('profil.edit') }}"
           style="display:inline-flex;align-items:center;gap:5px;
                  padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;
                  background:rgba(27,58,107,.08);color:#1B3A6B;text-decoration:none;
                  border:1px solid rgba(27,58,107,.15);transition:all .2s;"
           onmouseover="this.style.background='#1B3A6B';this.style.color='#fff';"
           onmouseout="this.style.background='rgba(27,58,107,.08)';this.style.color='#1B3A6B';">
          <i class="bi bi-pencil-square"></i> Modifier
        </a>
      </div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <div style="width:46px;height:46px;border-radius:50%;background:#1B3A6B;
                    display:flex;align-items:center;justify-content:center;
                    font-size:16px;font-weight:700;color:#F5A623;flex-shrink:0;
                    overflow:hidden;border:2px solid #F5A623;">
          @if(Auth::user()->avatar)
            <img src="{{ asset('storage/'.Auth::user()->avatar) }}"
                 alt="{{ Auth::user()->name }}"
                 style="width:100%;height:100%;object-fit:cover;">
          @else
            @php $parts = explode(' ', trim(Auth::user()->first_name)); @endphp
            {{ strtoupper(substr($parts[0],0,1)) }}{{ isset($parts[1]) ? strtoupper(substr($parts[1],0,1)) : '' }}
          @endif
        </div>
        <div>
          <div style="font-weight:700;color:#1B3A6B;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
          <div style="font-size:12px;color:#888;">{{ Auth::user()->email }}</div>
        </div>
      </div>
      <div style="font-size:12px;color:#888;display:flex;flex-direction:column;gap:6px;">
        @if(Auth::user()->phone)
        <div><i class="bi bi-telephone me-2" style="color:#F5A623;"></i>{{ Auth::user()->phone }}</div>
        @endif
        @if(Auth::user()->country)
        <div><i class="bi bi-geo-alt me-2" style="color:#F5A623;"></i>{{ Auth::user()->country }}</div>
        @endif
        <div>
          <i class="bi bi-calendar me-2" style="color:#F5A623;"></i>
          Membre depuis {{ Auth::user()->created_at->format('M Y') }}
        </div>
      </div>
    </div>

  </div>
</div>
@endsection