{{-- resources/views/admin/consultations/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Consultations')

@push('styles')
<style>
.stat-card{background:#fff;border-radius:12px;border:1px solid #eee;padding:18px 20px;box-shadow:0 2px 8px rgba(27,58,107,.05);}
.stat-num{font-size:2rem;font-weight:800;line-height:1;margin-bottom:4px;}
.stat-lbl{font-size:10px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.7px;}
.bs{padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;}
.bs-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.bs-en_cours  {background:rgba(27,58,107,.1); color:#1B3A6B;}
.bs-approuvee {background:rgba(28,200,138,.1);color:#0f6e56;}
.bs-declinee  {background:rgba(226,75,74,.1); color:#a32d2d;}
.bs-annulee   {background:#f0f0f0;color:#888;}
.bs-terminee  {background:rgba(127,119,221,.12);color:#3C3489;}
.vtab th{font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;border:none;padding:11px 14px;background:#f8f9fb;}
.vtab td{padding:13px 14px;vertical-align:middle;border-bottom:1px solid #f5f5f5;font-size:13px;}
.vtab tr:hover td{background:rgba(27,58,107,.015);}
.ba{display:inline-flex;align-items:center;justify-content:center;width:29px;height:29px;border-radius:7px;border:1px solid #e8e8e8;background:#fff;color:#666;font-size:12px;text-decoration:none;cursor:pointer;transition:all .15s;}
.ba:hover{border-color:#1B3A6B;color:#1B3A6B;}
.ba.d:hover{border-color:#E24B4A;color:#E24B4A;}
.urgbadge{padding:2px 7px;border-radius:6px;font-size:10px;font-weight:700;background:rgba(226,75,74,.1);color:#a32d2d;}
.fi{border:1.5px solid #e8e8e8;border-radius:8px;padding:7px 11px;font-size:12px;outline:none;transition:border .2s;width:100%;}
.fi:focus{border-color:#F5A623;}
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Consultations</h2>
        <p class="text-muted mb-0" style="font-size:12px;">{{ $consultations->total() }} dossier(s) au total</p>
    </div>
    <a href="{{ route('admin.consultations.export') }}"
       class="btn rounded-pill px-4 fw-semibold"
       style="background:rgba(27,58,107,.08);color:#1B3A6B;font-size:12px;border:none;">
        <i class="bi bi-download me-2"></i>Exporter CSV
    </a>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;font-size:13px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['num'=>$stats['total'],      'lbl'=>'Total',       'color'=>'#1B3A6B'],
        ['num'=>$stats['en_attente'], 'lbl'=>'En attente',  'color'=>'#F5A623'],
        ['num'=>$stats['approuvees'], 'lbl'=>'Approuvées',  'color'=>'#1cc88a'],
        ['num'=>$stats['terminees'],  'lbl'=>'Terminées',   'color'=>'#7F77DD'],
        ['num'=>$stats['ce_mois'],    'lbl'=>'Ce mois',     'color'=>'#888'],
    ] as $s)
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-num" style="color:{{ $s['color'] }};">{{ $s['num'] }}</div>
            <div class="stat-lbl">{{ $s['lbl'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filtres --}}
<div class="p-3 rounded-3 mb-4" style="background:#fff;border:1px solid #eee;">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <input type="text" name="search" class="fi"
                   value="{{ request('search') }}"
                   placeholder="Nom, email, téléphone, pays...">
        </div>
        <div class="col-md-2">
            <select name="statut" class="fi">
                <option value="">Tous statuts</option>
                @foreach(['en_attente'=>'En attente','en_cours'=>'En cours','approuvee'=>'Approuvée','declinee'=>'Déclinée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('statut')==$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="projet" class="fi">
                <option value="">Tous projets</option>
                @foreach(['etudes'=>'Études','travail'=>'Travail','immigration'=>'Immigration','visa'=>'Visa','bourse'=>'Bourse','regroupement'=>'Regroupement'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('projet')==$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="urgent" class="fi">
                <option value="">Toutes</option>
                <option value="1" {{ request('urgent')?'selected':'' }}>Urgentes uniquement</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn rounded-pill fw-semibold flex-fill"
                    style="background:#1B3A6B;color:#fff;font-size:12px;">
                <i class="bi bi-search me-1"></i>Filtrer
            </button>
            <a href="{{ route('admin.consultations.index') }}"
               class="btn rounded-pill" style="border:1px solid #ddd;color:#666;font-size:12px;">✕</a>
        </div>
    </form>
</div>

{{-- Tableau --}}
<div class="rounded-3 overflow-hidden" style="background:#fff;border:1px solid #eee;">
    <table class="table vtab mb-0">
        <thead>
            <tr>
                <th>Client</th>
                <th>Projet / Destination</th>
                <th>Contact</th>
                <th>Date souh.</th>
                <th>Statut</th>
                <th>Consultant</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($consultations as $c)
        <tr>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:34px;height:34px;border-radius:50%;background:#1B3A6B;
                                display:flex;align-items:center;justify-content:center;
                                font-size:12px;font-weight:700;color:#F5A623;flex-shrink:0;">
                        {{ strtoupper(substr($c->client_name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;color:#1B3A6B;font-size:13px;">{{ $c->client_name }}</div>
                        @if($c->urgent)
                            <span class="urgbadge"><i class="bi bi-exclamation-triangle-fill me-1"></i>Urgent</span>
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div style="font-size:13px;font-weight:600;color:#333;">{{ $c->projetLabel() }}</div>
                <div style="font-size:11px;color:#888;">{{ $c->destination_country ?? '—' }}</div>
            </td>
            <td>
                <div style="font-size:12px;color:#555;">{{ $c->client_email }}</div>
                <div style="font-size:11px;color:#888;">{{ $c->phone ?? '—' }}</div>
            </td>
            <td style="font-size:12px;color:#666;">
                {{ $c->departure_date ?? '—' }}
                @if($c->date_confirmee)
                    <div style="font-size:11px;color:#1cc88a;font-weight:600;">
                        ✓ {{ $c->date_confirmee->format('d/m/Y H:i') }}
                    </div>
                @endif
            </td>
            <td>
                <span class="bs bs-{{ $c->statut }}">{{ $c->statutLabel() }}</span>
            </td>
            <td style="font-size:12px;color:#666;">
                {{ $c->consultant?->name ?? '—' }}
            </td>
            <td>
                <div class="d-flex gap-1 justify-content-end">
                    <a href="{{ route('admin.consultations.show', $c) }}"
                       class="ba" title="Voir le dossier complet">
                        <i class="bi bi-eye"></i>
                    </a>
                    @if($c->peutEtreTraitee())
                    <a href="{{ route('admin.consultations.show', $c) }}#approuver"
                       class="ba" title="Approuver"
                       style="border-color:rgba(28,200,138,.3);color:#1cc88a;">
                        <i class="bi bi-check-lg"></i>
                    </a>
                    @endif
                    <form method="POST" action="{{ route('admin.consultations.toggle-urgent', $c) }}">
                        @csrf
                        <button type="submit" class="ba" title="{{ $c->urgent ? 'Retirer urgence' : 'Marquer urgent' }}"
                                style="{{ $c->urgent ? 'border-color:rgba(226,75,74,.4);color:#E24B4A;' : '' }}">
                            <i class="bi bi-exclamation-triangle{{ $c->urgent ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                    @role('super-admin')
                    <form method="POST" action="{{ route('admin.consultations.destroy', $c) }}"
                          onsubmit="return confirm('Supprimer ce dossier ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="ba d"><i class="bi bi-trash"></i></button>
                    </form>
                    @endrole
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-5" style="color:#aaa;">
                <i class="bi bi-calendar-x" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                Aucune consultation trouvée
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($consultations->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $consultations->links() }}</div>
@endif

@endsection