{{-- resources/views/admin/courses/student-detail.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Progression — '.($user->first_name ?? ''))

@push('styles')
<style>
    /* Tes styles existants conservés */
    .lang-tab{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;
              border:1.5px solid #e8e8e8;background:#fff;font-size:12px;font-weight:700;
              cursor:pointer;transition:all .2s;color:#666;}
    .lang-tab.active{color:#fff;border-color:transparent;}

    .course-row{display:flex;align-items:center;padding:12px 14px;background:#f8f9fb;
                 border-radius:10px;border:1px solid #eee;margin-bottom:6px;gap:12px;}
    .course-row:hover{background:#fff;border-color:#ddd;}

    .sp{padding:3px 9px;border-radius:10px;font-size:11px;font-weight:700;}
    .sp-good{background:rgba(28,200,138,.1);color:#0f6e56;}
    .sp-mid{background:rgba(245,166,35,.12);color:#633806;}
    .sp-bad{background:rgba(226,75,74,.08);color:#a32d2d;}
    .sp-none{background:#f0f0f0;color:#888;}

    .kpi-sm{background:#f8f9fb;border-radius:12px;padding:16px;text-align:center;}
    .kpi-sm-n{font-size:1.8rem;font-weight:800;line-height:1;margin-bottom:4px;}
    .kpi-sm-l{font-size:10px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.5px;}

    .prog-bar{height:8px;border-radius:4px;background:#f0f0f0;overflow:hidden;margin-top:6px;}
    .prog-bar-fill{height:100%;border-radius:4px;transition:width .6s;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.student-progress.index') }}" 
       style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.3rem;">
            Parcours de {{ $user->first_name }} {{ $user->last_name }}
        </h2>
        <p class="text-muted mb-0" style="font-size:12px;">{{ $user->email }}</p>
    </div>
    <a href="{{ route('admin.users.show', $user) }}" 
       style="margin-left:auto;display:inline-flex;align-items:center;gap:5px;padding:8px 16px;
              border:1.5px solid #1B3A6B;color:#1B3A6B;border-radius:20px;
              font-size:12px;font-weight:600;text-decoration:none;">
        <i class="bi bi-person-circle"></i>Profil complet
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        {{-- Carte utilisateur --}}
        <div style="background:#1B3A6B;border-radius:14px;padding:20px;margin-bottom:16px;color:#fff;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:50px;height:50px;border-radius:50%;background:rgba(245,166,35,.2);
                            border:2px solid #F5A623;display:flex;align-items:center;justify-content:center;
                            font-size:18px;font-weight:800;color:#F5A623;flex-shrink:0;">
                    {{ strtoupper(substr($user->first_name ?? '?', 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:16px;font-weight:800;">{{ $user->first_name }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,.6);">Inscrit en {{ $user->created_at->format('M Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Stats globales --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #eee;padding:18px;">
            <div style="font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:14px;">Résumé VisaFly</div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="kpi-sm">
                        <div class="kpi-sm-n">{{ $stats['cours_entames'] }}</div>
                        <div class="kpi-sm-l">Cours</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="kpi-sm">
                        <div class="kpi-sm-n" style="color:#1cc88a;">{{ $stats['cours_termines'] }}</div>
                        <div class="kpi-sm-l">Finis</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="kpi-sm">
                        <div class="kpi-sm-n" style="color:#F5A623;">{{ number_format($stats['moyenne_generale'], 1) }}/20</div>
                        <div class="kpi-sm-l">Note Moyenne</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="kpi-sm">
                        <div class="kpi-sm-n" style="color:#7F77DD;">{{ $stats['temps_total_h'] }} h</div>
                        <div class="kpi-sm-l">Temps d'apprentissage</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Liste des cours avec progression --}}
        <h5 style="color:#1B3A6B;font-weight:800;margin-bottom:16px;">Détails par cours</h5>

        @forelse($progressionParCours as $item)
            @php
                $sc = $item['score'];
                $cls = $sc >= 15 ? 'sp-good' : ($sc >= 10 ? 'sp-mid' : 'sp-bad');
            @endphp
            <div class="course-row">
                <div style="width:40px;height:40px;border-radius:10px;background:#1B3A6B;
                            display:flex;align-items:center;justify-content:center;color:#fff;">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:14px;font-weight:700;color:#1B3A6B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $item['titre'] }}
                    </div>
                    <div style="font-size:11px;color:#888;">
                        Langue : <strong>{{ $item['langue'] }}</strong> · 
                        Dernier accès : {{ \Carbon\Carbon::parse($item['derniere_activite'])->format('d/m/Y') }}
                    </div>
                    <div class="prog-bar">
                        <div class="prog-bar-fill" style="width:{{ $item['pourcentage'] }}%; background:#1cc88a;"></div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <span class="sp {{ $cls }}">{{ $item['score'] }}/20</span>
                    <div style="font-size:11px;font-weight:800;color:#1B3A6B;margin-top:4px;">
                        {{ $item['pourcentage'] }}%
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:60px 20px;background:#f8f9fb;border-radius:14px;border:1.5px dashed #ddd;">
                <i class="bi bi-emoji-smile" style="font-size:36px;color:#ccc;display:block;margin-bottom:12px;"></i>
                <div style="font-size:13px;color:#888;">Aucun cours n'a été commencé pour le moment.</div>
            </div>
        @endforelse
    </div>
</div>

@endsection