{{-- resources/views/admin/consultations/show.blade.php --}}
@extends('layouts.dashboard')
@php use Illuminate\Support\Str; use Illuminate\Support\Facades\Storage; @endphp
@section('title', 'Dossier – ' . $consultation->client_name)

@push('styles')
<style>
/* ── Base ─────────────────────────────── */
.card-section{background:#fff;border:1px solid #eee;border-radius:12px;margin-bottom:20px;overflow:hidden;}
.card-header-vf{padding:14px 20px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;}
.card-header-vf h6{margin:0;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#1B3A6B;}
.card-body-vf{padding:20px;}

/* ── Pipeline ────────────────────────── */
.pipeline-track{display:flex;align-items:flex-start;gap:0;overflow-x:auto;padding-bottom:8px;}
.etape-wrap{display:flex;flex-direction:column;align-items:center;min-width:110px;position:relative;}
.etape-wrap:not(:last-child)::after{
    content:'';position:absolute;top:18px;left:calc(50% + 18px);
    width:calc(100% - 36px);height:2px;
    background:#e8e8e8;z-index:0;
}
.etape-wrap.valide:not(:last-child)::after{background:linear-gradient(90deg,#1cc88a,#e8e8e8);}
.etape-circle{
    width:36px;height:36px;border-radius:50%;border:2px solid #e8e8e8;
    display:flex;align-items:center;justify-content:center;
    font-size:13px;font-weight:700;background:#fff;position:relative;z-index:1;
    transition:all .2s;
}
.etape-circle.valide{border-color:#1cc88a;background:#e8ffee;color:#0f6e56;}
.etape-circle.en_cours{border-color:#F5A623;background:rgba(245,166,35,.1);color:#7a4500;animation:pulse-gold 2s infinite;}
.etape-circle.en_attente{border-color:#e8e8e8;color:#aaa;}
.etape-circle.bloque{border-color:#E24B4A;background:rgba(226,75,74,.08);color:#a32d2d;}
@keyframes pulse-gold{0%,100%{box-shadow:0 0 0 0 rgba(245,166,35,.4);}50%{box-shadow:0 0 0 6px rgba(245,166,35,0);}}
.etape-label{font-size:10px;text-align:center;margin-top:6px;color:#666;line-height:1.3;max-width:90px;}
.etape-label strong{display:block;font-size:9px;color:#888;margin-top:2px;}

/* ── Paiements ───────────────────────── */
.ptable th{font-size:10px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.5px;padding:8px 12px;border:none;background:#f8f9fb;}
.ptable td{font-size:12px;padding:10px 12px;border-bottom:1px solid #f5f5f5;vertical-align:middle;}
.bpay{padding:3px 9px;border-radius:12px;font-size:10px;font-weight:700;}
.bpay-recu{background:rgba(28,200,138,.1);color:#0f6e56;}
.bpay-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.bpay-annule{background:#f0f0f0;color:#888;}
.progress-pay{height:8px;border-radius:4px;background:#eee;overflow:hidden;}
.progress-pay-bar{height:100%;border-radius:4px;background:linear-gradient(90deg,#1B3A6B,#F5A623);transition:width .5s;}

/* ── Consultant card ─────────────────── */
.consul-avatar{width:48px;height:48px;border-radius:50%;background:#1B3A6B;
    display:flex;align-items:center;justify-content:center;font-size:16px;
    font-weight:800;color:#F5A623;flex-shrink:0;}

/* ── Formulaires inline ──────────────── */
.fi{border:1.5px solid #e8e8e8;border-radius:8px;padding:7px 11px;font-size:12px;
    outline:none;transition:border .2s;width:100%;}
.fi:focus{border-color:#F5A623;}
.btn-vf{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;
    border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all .15s;}
.btn-primary-vf{background:#1B3A6B;color:#fff;}
.btn-primary-vf:hover{background:#16305a;color:#fff;}
.btn-danger-vf{background:rgba(226,75,74,.1);color:#a32d2d;border:1px solid rgba(226,75,74,.2);}
.btn-danger-vf:hover{background:rgba(226,75,74,.2);}
.btn-ghost-vf{background:rgba(27,58,107,.06);color:#1B3A6B;border:1px solid rgba(27,58,107,.1);}
.btn-ghost-vf:hover{background:rgba(27,58,107,.12);}

/* ── Nav onglets ─────────────────────── */
.vnav{display:flex;gap:4px;border-bottom:2px solid #f0f0f0;margin-bottom:20px;}
.vnav-item{padding:9px 16px;font-size:12px;font-weight:600;color:#888;
    border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;
    transition:all .15s;border-radius:6px 6px 0 0;}
.vnav-item.active{color:#1B3A6B;border-bottom-color:#1B3A6B;background:rgba(27,58,107,.04);}
.tab-pane{display:none;}.tab-pane.active{display:block;}
</style>
@endpush

@section('content')

{{-- ── En-tête dossier ────────────────────────────────────── --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <a href="{{ route('admin.consultations.index') }}"
           style="font-size:12px;color:#888;text-decoration:none;">
            ← Toutes les consultations
        </a>
        <h2 class="fw-bold mb-1 mt-1" style="color:#1B3A6B;font-size:1.35rem;">
            {{ $consultation->client_name }}
            @if($consultation->urgent)
                <span style="background:rgba(226,75,74,.1);color:#a32d2d;font-size:11px;
                      font-weight:700;padding:2px 8px;border-radius:6px;vertical-align:middle;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgent
                </span>
            @endif
        </h2>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="bs bs-{{ $consultation->statut }}">{{ $consultation->statutLabel() }}</span>
            <span style="font-size:12px;color:#888;">{{ $consultation->projetLabel() }}</span>
            <span style="font-size:12px;color:#aaa;">•</span>
            <span style="font-size:12px;color:#888;">{{ $consultation->destination_country ?? '—' }}</span>
            <span style="font-size:12px;color:#aaa;">•</span>
            <span style="font-size:12px;color:#aaa;">Créé le {{ $consultation->created_at->format('d/m/Y') }}</span>
        </div>
    </div>

    {{-- Actions rapides header --}}
    <div class="d-flex gap-2 flex-wrap">
        @if($consultation->statut === 'en_attente')
        <form method="POST" action="{{ route('admin.consultations.en-cours', $consultation) }}">
            @csrf
            <button class="btn-vf btn-ghost-vf">
                <i class="bi bi-hourglass-split"></i> En cours
            </button>
        </form>
        @endif

        @if(in_array($consultation->statut, ['en_attente','en_cours']))
        <button class="btn-vf btn-primary-vf" onclick="showTab('tab-approuver')">
            <i class="bi bi-check-circle"></i> Approuver
        </button>
        @endif

        <form method="POST" action="{{ route('admin.consultations.toggle-urgent', $consultation) }}">
            @csrf
            <button class="btn-vf btn-ghost-vf"
                    style="{{ $consultation->urgent ? 'color:#E24B4A;' : '' }}">
                <i class="bi bi-exclamation-triangle{{ $consultation->urgent ? '-fill' : '' }}"></i>
                {{ $consultation->urgent ? 'Retirer urgence' : 'Marquer urgent' }}
            </button>
        </form>

        @if($consultation->statut === 'approuvee')
        <form method="POST" action="{{ route('admin.consultations.terminer', $consultation) }}">
            @csrf
            <button class="btn-vf" style="background:rgba(127,119,221,.1);color:#3C3489;">
                <i class="bi bi-flag-fill"></i> Terminer
            </button>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;font-size:13px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="alert rounded-3 mb-3"
     style="background:rgba(226,75,74,.08);border:1px solid rgba(226,75,74,.2);color:#a32d2d;font-size:13px;">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    {{ $errors->first() }}
</div>
@endif

{{-- ── Navigation onglets ──────────────────────────────────── --}}
<div class="vnav" id="mainNav">
    <div class="vnav-item active" onclick="showTab('tab-pipeline')">
        <i class="bi bi-diagram-3 me-1"></i>Pipeline
    </div>
    <div class="vnav-item" onclick="showTab('tab-consultant')">
        <i class="bi bi-person-badge me-1"></i>Consultant
    </div>
    <div class="vnav-item" onclick="showTab('tab-paiements')">
        <i class="bi bi-cash-stack me-1"></i>Paiements
        @php $totalPaye_ = $consultation->paiements->where('statut','recu')->sum('montant'); @endphp
        @if($consultation->montant_total > 0)
            <span style="background:#1B3A6B;color:#fff;border-radius:10px;
                  padding:1px 6px;font-size:9px;margin-left:4px;">
                {{ $pourcentagePaye }}%
            </span>
        @endif
    </div>
    <div class="vnav-item" onclick="showTab('tab-details')">
        <i class="bi bi-person-lines-fill me-1"></i>Infos client
    </div>
    @if(in_array($consultation->statut, ['en_attente','en_cours']))
    <div class="vnav-item" onclick="showTab('tab-approuver')">
        <i class="bi bi-check-circle me-1"></i>Approuver / Décliner
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════
     ONGLET 1 — PIPELINE
═══════════════════════════════════════════════════════════ --}}
<div class="tab-pane active" id="tab-pipeline">
    <div class="card-section">
        <div class="card-header-vf">
            <h6><i class="bi bi-diagram-3 me-2"></i>Évolution de la procédure</h6>
            @php
                $done  = $consultation->pipelineEtapes->where('statut','valide')->count();
                $total = $consultation->pipelineEtapes->count();
                $pctPipe = $total > 0 ? round($done / $total * 100) : 0;
            @endphp
            <span style="font-size:12px;color:#888;">{{ $done }} / {{ $total }} étapes &nbsp;·&nbsp; {{ $pctPipe }}%</span>
        </div>
        <div class="card-body-vf">

            {{-- Barre progression globale --}}
            <div class="progress-pay mb-4" style="height:6px;">
                <div class="progress-pay-bar" style="width:{{ $pctPipe }}%"></div>
            </div>

            @if($consultation->pipelineEtapes->isEmpty())
                <p class="text-center text-muted" style="font-size:13px;">Aucune étape de pipeline trouvée.</p>
            @else

            {{-- Track visuel --}}
            <div class="pipeline-track mb-4">
                @foreach($consultation->pipelineEtapes as $etape)
                <div class="etape-wrap {{ $etape->statut }}">
                    <div class="etape-circle {{ $etape->statut }}">
                        @if($etape->statut === 'valide')
                            <i class="bi bi-check-lg"></i>
                        @elseif($etape->statut === 'en_cours')
                            <i class="bi bi-hourglass-split"></i>
                        @elseif($etape->statut === 'bloque')
                            <i class="bi bi-x-lg"></i>
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>
                    <div class="etape-label">
                        {{ $etape->titre }}
                        <strong>{{ \Carbon\Carbon::parse($etape->updated_at)->locale('fr')->diffForHumans() }}</strong>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Détail étapes --}}
            <div class="d-flex flex-column gap-3">
            @foreach($consultation->pipelineEtapes as $etape)
            <div class="p-3 rounded-3" style="
                background: {{ $etape->statut === 'valide' ? 'rgba(28,200,138,.04)' :
                              ($etape->statut === 'en_cours' ? 'rgba(245,166,35,.04)' : '#fafafa') }};
                border: 1px solid {{ $etape->statut === 'valide' ? 'rgba(28,200,138,.15)' :
                                     ($etape->statut === 'en_cours' ? 'rgba(245,166,35,.2)' : '#f0f0f0') }};
            ">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span style="font-size:11px;font-weight:700;color:#888;">Étape {{ $etape->ordre }}</span>
                            @php
                                $sc = match($etape->statut) {
                                    'valide'     => 'color:#0f6e56;background:rgba(28,200,138,.1)',
                                    'en_cours'   => 'color:#7a4500;background:rgba(245,166,35,.1)',
                                    'bloque'     => 'color:#a32d2d;background:rgba(226,75,74,.1)',
                                    default      => 'color:#888;background:#f0f0f0',
                                };
                                $sl = match($etape->statut) {
                                    'valide'     => 'Validée',
                                    'en_cours'   => 'En cours',
                                    'bloque'     => 'Bloquée',
                                    'en_attente' => 'En attente',
                                    default      => ucfirst($etape->statut),
                                };
                            @endphp
                            <span style="padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;{{ $sc }}">
                                {{ $sl }}
                            </span>
                        </div>
                        <div style="font-weight:700;color:#1B3A6B;font-size:14px;">{{ $etape->titre }}</div>
                        @if($etape->description)
                            <div style="font-size:12px;color:#888;margin-top:3px;">{{ $etape->description }}</div>
                        @endif
                    </div>
                    <div style="font-size:11px;color:#aaa;text-align:right;">
                        Mise à jour {{ $etape->updated_at->format('d/m/Y') }}
                    </div>
                </div>

{{-- ══════════════════════════════════════════════════════════
     REMPLACEMENT COMPLET du bloc "Documents requis / associés"
     dans show.blade.php (onglet PIPELINE, dans la boucle @foreach pipelineEtapes)

     LOGIQUE :
     - documents_requis = ['passeport', 'diplome', ...] (types demandés)
     - On cherche dans $consultation->documents par (etape_index + type)
     - Le nom du fichier uploadé n'a aucune importance
══════════════════════════════════════════════════════════ --}}

{{-- Documents requis --}}
@if(!empty($etape->documents_requis))
<div class="mt-3 pt-3" style="border-top:1px dashed #eee;">
    <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;
                letter-spacing:.6px;margin-bottom:10px;">
        <i class="bi bi-paperclip me-1"></i>Documents requis pour cette étape
    </div>

    <div class="d-flex flex-column gap-2">
    @foreach((array)$etape->documents_requis as $typeDoc)
    @php
        /*
         * Recherche du document uploadé par le client
         * Clé : consultation_id (déjà dans le scope) + etape_index = etape.ordre + type = $typeDoc
         * Le nom du fichier (name) est ignoré — on se base uniquement sur le type et l'étape
         */
        $docUploade = $consultation->documents
            ->where('etape_index', $etape->ordre)
            ->where('type', $typeDoc)
            ->first();

        // Statut du document
        $docStatut = $docUploade?->status ?? null;

        // Style de la ligne selon statut
        $rowStyle = match($docStatut) {
            'valide'     => 'background:rgba(28,200,138,.05);border:1px solid rgba(28,200,138,.2);',
            'rejete'     => 'background:rgba(226,75,74,.04);border:1px solid rgba(226,75,74,.15);',
            'en_attente' => 'background:rgba(245,166,35,.04);border:1px solid rgba(245,166,35,.2);',
            default      => 'background:#f8f9fb;border:1px solid #eee;', // pas encore uploadé
        };

        // Icône et couleur du badge statut
        [$badgeTxt, $badgeStyle, $badgeIcon] = match($docStatut) {
            'valide'     => ['Validé',      'color:#0f6e56;background:rgba(28,200,138,.1)',  'bi-check-circle-fill'],
            'rejete'     => ['Rejeté',      'color:#a32d2d;background:rgba(226,75,74,.1)',   'bi-x-circle-fill'],
            'en_attente' => ['Reçu · En attente', 'color:#7a4500;background:rgba(245,166,35,.1)', 'bi-hourglass-split'],
            default      => ['Non soumis',  'color:#999;background:#f0f0f0',                 'bi-dash-circle'],
        };

        // Infos fichier si uploadé
        $fileUrl = $docUploade ? Storage::url($docUploade->file_path) : null;
        $fileName = $docUploade?->name ?? null;
        $fileExt  = $fileName ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : '';
        $isImage  = in_array($fileExt, ['jpg','jpeg','png','gif','webp','svg']);
        $isPdf    = $fileExt === 'pdf';

        $modalId = 'docModal_etape' . $etape->id . '_type' . Str::slug($typeDoc);
    @endphp

    <div class="d-flex align-items-center justify-content-between gap-3 p-2 rounded-3 flex-wrap"
         style="{{ $rowStyle }}">

        {{-- Colonne gauche : nom du type demandé --}}
        <div class="d-flex align-items-center gap-2" style="min-width:160px;">
            <i class="bi bi-file-earmark-text" style="color:#1B3A6B;font-size:15px;flex-shrink:0;"></i>
            <div>
                <div style="font-size:12px;font-weight:700;color:#1B3A6B;text-transform:capitalize;">
                    {{ str_replace('_', ' ', $typeDoc) }}
                </div>
                @if($fileName)
                <div style="font-size:10px;color:#aaa;" title="{{ $fileName }}">
                    {{ Str::limit($fileName, 30) }}
                </div>
                @endif
            </div>
        </div>

        {{-- Badge statut --}}
        <span style="padding:3px 9px;border-radius:12px;font-size:10px;font-weight:700;
                     white-space:nowrap;{{ $badgeStyle }}">
            <i class="bi {{ $badgeIcon }} me-1"></i>{{ $badgeTxt }}
        </span>

        {{-- Actions : voir + télécharger si fichier présent --}}
        @if($docUploade && $fileUrl)
        <div class="d-flex gap-1">
            <button type="button"
                    onclick="openDocModal('{{ $modalId }}')"
                    style="padding:4px 10px;background:#fff;color:#1B3A6B;border-radius:6px;
                           font-size:11px;border:1px solid rgba(27,58,107,.2);cursor:pointer;
                           display:inline-flex;align-items:center;gap:4px;"
                    title="Visualiser">
                <i class="bi bi-eye"></i> Voir
            </button>
            <a href="{{ $fileUrl }}" download="{{ $fileName }}"
               style="padding:4px 10px;background:#1B3A6B;color:#fff;border-radius:6px;
                      font-size:11px;text-decoration:none;
                      display:inline-flex;align-items:center;gap:4px;"
               title="Télécharger">
                <i class="bi bi-download"></i>
            </a>
        </div>
        @else
        <span style="font-size:11px;color:#ccc;font-style:italic;">En attente d'upload client</span>
        @endif

        {{-- Commentaire si rejeté --}}
        @if($docStatut === 'rejete' && $docUploade?->comment)
        <div class="w-100 mt-1 ps-1" style="font-size:11px;color:#a32d2d;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Motif : {{ $docUploade->comment }}
        </div>
        @endif
    </div>

    {{-- ── MODAL VISUALISATION ──────────────────────────── --}}
    @if($docUploade && $fileUrl)
    <div id="{{ $modalId }}"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);
                z-index:10000;align-items:center;justify-content:center;padding:20px;"
         onclick="if(event.target===this) closeDocModal('{{ $modalId }}')">
        <div style="background:#fff;border-radius:14px;overflow:hidden;
                    max-width:920px;width:100%;max-height:92vh;
                    display:flex;flex-direction:column;box-shadow:0 30px 80px rgba(0,0,0,.35);">

            {{-- Header --}}
            <div style="padding:14px 20px;border-bottom:1px solid #eee;background:#f8f9fb;
                        display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-{{ $isPdf ? 'file-earmark-pdf' : ($isImage ? 'file-earmark-image' : 'file-earmark') }}"
                       style="color:#1B3A6B;font-size:18px;"></i>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#1B3A6B;">
                            {{ str_replace('_', ' ', $typeDoc) }}
                        </div>
                        <div style="font-size:10px;color:#aaa;">
                            Fichier : {{ $fileName }}
                            @if($fileExt) · {{ strtoupper($fileExt) }} @endif
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <a href="{{ $fileUrl }}" download="{{ $fileName }}"
                       style="padding:5px 12px;background:#1B3A6B;color:#fff;border-radius:7px;
                              font-size:11px;font-weight:600;text-decoration:none;
                              display:inline-flex;align-items:center;gap:4px;">
                        <i class="bi bi-download"></i> Télécharger
                    </a>
                    <a href="{{ $fileUrl }}" target="_blank"
                       style="padding:5px 12px;background:rgba(27,58,107,.08);color:#1B3A6B;
                              border-radius:7px;font-size:11px;font-weight:600;text-decoration:none;
                              display:inline-flex;align-items:center;gap:4px;">
                        <i class="bi bi-box-arrow-up-right"></i> Ouvrir
                    </a>
                    <button onclick="closeDocModal('{{ $modalId }}')"
                            style="background:none;border:1px solid #e8e8e8;border-radius:7px;
                                   width:30px;height:30px;cursor:pointer;font-size:16px;color:#888;
                                   display:flex;align-items:center;justify-content:center;">✕</button>
                </div>
            </div>

            {{-- Corps viewer --}}
            <div style="flex:1;overflow:auto;background:#e8e8e8;
                        display:flex;align-items:center;justify-content:center;min-height:320px;">
                @if($isImage)
                    <img src="{{ $fileUrl }}"
                         alt="{{ $typeDoc }}"
                         style="max-width:100%;max-height:75vh;object-fit:contain;display:block;"
                         onerror="this.outerHTML='<div style=\'text-align:center;padding:40px;color:#aaa\'><i class=\'bi bi-image\' style=\'font-size:40px;display:block;margin-bottom:10px\'></i>Image introuvable</div>'">

                @elseif($isPdf)
                    <iframe src="{{ $fileUrl }}#toolbar=1&navpanes=0&scrollbar=1"
                            style="width:100%;height:75vh;border:none;display:block;">
                    </iframe>

                @else
                    <div style="text-align:center;padding:60px 40px;">
                        <i class="bi bi-file-earmark-text"
                           style="font-size:56px;color:#1B3A6B;opacity:.25;display:block;margin-bottom:16px;"></i>
                        <div style="font-size:14px;font-weight:600;color:#555;margin-bottom:6px;">
                            {{ $fileName }}
                        </div>
                        <div style="font-size:12px;color:#aaa;margin-bottom:20px;">
                            Ce format ne peut pas être prévisualisé dans le navigateur.
                        </div>
                        <a href="{{ $fileUrl }}" download="{{ $fileName }}"
                           style="padding:9px 22px;background:#1B3A6B;color:#fff;border-radius:8px;
                                  font-size:13px;font-weight:600;text-decoration:none;
                                  display:inline-flex;align-items:center;gap:6px;">
                            <i class="bi bi-download"></i> Télécharger
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
    @endif
    {{-- ── FIN MODAL ──────────────────────────────────────── --}}

    @endforeach
    </div>
</div>
@endif


                {{-- Note étape --}}
                @if($etape->note)
                <div class="mt-2" style="font-size:12px;color:#555;background:#fff;
                     padding:8px 12px;border-radius:6px;border-left:3px solid #F5A623;">
                    {{ $etape->note }}
                </div>
                @endif
            </div>
            @endforeach
            </div>

            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ONGLET 2 — CONSULTANT
═══════════════════════════════════════════════════════════ --}}
<div class="tab-pane" id="tab-consultant">
    <div class="card-section">
        <div class="card-header-vf">
            <h6><i class="bi bi-person-badge me-2"></i>Consultant assigné</h6>
        </div>
        <div class="card-body-vf">

            {{-- Consultant actuel --}}
            @if($consultation->consultant)
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4"
                 style="background:#f8f9fb;border:1px solid #eee;">
                <div class="consul-avatar">
                    {{ strtoupper(substr($consultation->consultant->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:700;color:#1B3A6B;font-size:15px;">
                        {{ $consultation->consultant->name }}
                    </div>
                    <div style="font-size:12px;color:#888;">{{ $consultation->consultant->email }}</div>
                    @foreach($consultation->consultant->getRoleNames() as $role)
                    <span style="font-size:10px;background:rgba(27,58,107,.08);color:#1B3A6B;
                          padding:2px 7px;border-radius:6px;font-weight:600;">
                        {{ ucfirst($role) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @else
            <div class="p-4 text-center rounded-3 mb-4" style="background:#fffbf0;border:1px dashed #F5A623;">
                <i class="bi bi-person-dash" style="font-size:28px;color:#F5A623;display:block;margin-bottom:8px;"></i>
                <div style="font-size:13px;color:#888;">Aucun consultant assigné à ce dossier</div>
            </div>
            @endif

            {{-- Formulaire assignation / changement --}}
            <form method="POST" action="{{ route('admin.consultations.assigner', $consultation) }}">
                @csrf
                <div class="mb-3">
                    <label style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;
                           letter-spacing:.5px;margin-bottom:6px;display:block;">
                        {{ $consultation->consultant ? 'Changer de consultant' : 'Assigner un consultant' }}
                    </label>
                    <select name="consultant_id" class="fi" required>
                        <option value="">— Sélectionner un consultant —</option>
                        @foreach($consultants as $con)
                        <option value="{{ $con->id }}"
                            {{ $consultation->consultant_id == $con->id ? 'selected' : '' }}>
                            {{ $con->name }} ({{ $con->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-vf btn-primary-vf">
                    <i class="bi bi-person-check"></i>
                    {{ $consultation->consultant ? 'Changer le consultant' : 'Assigner' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Note admin --}}
    <div class="card-section">
        <div class="card-header-vf">
            <h6><i class="bi bi-sticky me-2"></i>Note interne</h6>
        </div>
        <div class="card-body-vf">
            <form method="POST" action="{{ route('admin.consultations.note', $consultation) }}">
                @csrf
                <textarea name="note_admin" class="fi" rows="4"
                    placeholder="Note visible uniquement par l'équipe admin...">{{ $consultation->note_admin }}</textarea>
                <button type="submit" class="btn-vf btn-primary-vf mt-2">
                    <i class="bi bi-save"></i> Enregistrer la note
                </button>
            </form>
        </div>
    </div>

    {{-- Lien visio --}}
    @if(in_array($consultation->canal, ['video', null]) || $consultation->canal === 'video')
    <div class="card-section">
        <div class="card-header-vf">
            <h6><i class="bi bi-camera-video me-2"></i>Lien visioconférence</h6>
        </div>
        <div class="card-body-vf">
            @if($consultation->lien_visio)
            <div class="mb-3">
                <a href="{{ $consultation->lien_visio }}" target="_blank"
                   style="font-size:13px;color:#1B3A6B;word-break:break-all;">
                    <i class="bi bi-link-45deg me-1"></i>{{ $consultation->lien_visio }}
                </a>
            </div>
            @endif
            <form method="POST" action="{{ route('admin.consultations.lien-visio', $consultation) }}">
                @csrf
                <div class="d-flex gap-2">
                    <input type="url" name="lien_visio" class="fi"
                           value="{{ $consultation->lien_visio }}"
                           placeholder="https://meet.google.com/...">
                    <button type="submit" class="btn-vf btn-primary-vf" style="white-space:nowrap;">
                        <i class="bi bi-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════
     ONGLET 3 — PAIEMENTS
═══════════════════════════════════════════════════════════ --}}
<div class="tab-pane" id="tab-paiements">

    {{-- Résumé financier --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card-section p-0">
                <div class="card-body-vf text-center">
                    <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">
                        Montant total
                    </div>
                    <div style="font-size:1.6rem;font-weight:800;color:#1B3A6B;">
                        {{ $consultation->montant_total
                            ? number_format($consultation->montant_total, 0, ',', ' ') . ' ' . ($consultation->devise ?? 'XAF')
                            : '—' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-section p-0">
                <div class="card-body-vf text-center">
                    <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">
                        Total encaissé
                    </div>
                    <div style="font-size:1.6rem;font-weight:800;color:#1cc88a;">
                        {{ number_format($totalPaye, 0, ',', ' ') }} {{ $consultation->devise ?? 'XAF' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-section p-0">
                <div class="card-body-vf text-center">
                    <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">
                        Reste à payer
                    </div>
                    <div style="font-size:1.6rem;font-weight:800;color:{{ $resteAPayer > 0 ? '#E24B4A' : '#1cc88a' }};">
                        {{ number_format($resteAPayer, 0, ',', ' ') }} {{ $consultation->devise ?? 'XAF' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barre progression paiement --}}
    @if($consultation->montant_total > 0)
    <div class="mb-4">
        <div class="d-flex justify-content-between mb-1">
            <span style="font-size:12px;color:#555;">Progression du paiement</span>
            <span style="font-size:12px;font-weight:700;color:#1B3A6B;">{{ $pourcentagePaye }}%</span>
        </div>
        <div class="progress-pay">
            <div class="progress-pay-bar" style="width:{{ $pourcentagePaye }}%"></div>
        </div>
    </div>
    @endif

    {{-- Modifier le montant total --}}
    <div class="card-section mb-4">
        <div class="card-header-vf">
            <h6><i class="bi bi-pencil-square me-2"></i>
                {{ $consultation->montant_total ? 'Modifier le montant total' : 'Définir le montant total' }}
            </h6>
        </div>
        <div class="card-body-vf">
            <form method="POST" action="{{ route('admin.consultations.montant', $consultation) }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Montant (FCFA ou autre)</label>
                        <input type="number" name="montant_total" class="fi"
                               value="{{ $consultation->montant_total }}"
                               placeholder="ex: 250000" min="0"  required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Devise</label>
                        <select name="devise" class="fi">
                            @foreach(['XAF'=>'XAF (FCFA)','EUR'=>'EUR (€)','USD'=>'USD ($)','GBP'=>'GBP (£)'] as $v=>$l)
                            <option value="{{ $v }}" {{ ($consultation->devise ?? 'XAF') == $v ? 'selected' : '' }}>
                                {{ $l }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn-vf btn-primary-vf w-100">
                            <i class="bi bi-save"></i> Enregistrer le montant
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ajouter une tranche --}}
    <div class="card-section mb-4">
        <div class="card-header-vf">
            <h6><i class="bi bi-plus-circle me-2"></i>Ajouter une tranche de paiement</h6>
        </div>
        <div class="card-body-vf">
            <form method="POST" action="{{ route('admin.consultations.paiements.store', $consultation) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Montant *</label>
                        <input type="number" name="montant" class="fi" required min="1" placeholder="ex: 50000">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Devise</label>
                        <select name="devise" class="fi">
                            @foreach(['XAF'=>'XAF','EUR'=>'EUR','USD'=>'USD','GBP'=>'GBP'] as $v=>$l)
                            <option value="{{ $v }}" {{ ($consultation->devise ?? 'XAF') == $v ? 'selected' : '' }}>
                                {{ $l }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Mode *</label>
                        <select name="mode" class="fi" required>
                            <option value="especes">Espèces</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="virement">Virement</option>
                            <option value="carte">Carte</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Statut *</label>
                        <select name="statut" class="fi" required>
                            <option value="recu">Reçu</option>
                            <option value="en_attente">En attente</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Date *</label>
                        <input type="date" name="date_paiement" class="fi" required
                               value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Référence / N° reçu</label>
                        <input type="text" name="reference" class="fi" placeholder="ex: VF-2024-001">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Note</label>
                        <input type="text" name="note" class="fi" placeholder="Commentaire optionnel...">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn-vf btn-primary-vf">
                        <i class="bi bi-plus-circle"></i> Enregistrer la tranche
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Historique des tranches --}}
    <div class="card-section">
        <div class="card-header-vf">
            <h6><i class="bi bi-list-check me-2"></i>Historique des paiements</h6>
            <span style="font-size:12px;color:#888;">{{ $consultation->paiements->count() }} tranche(s)</span>
        </div>
        @if($consultation->paiements->isEmpty())
        <div class="card-body-vf text-center" style="color:#aaa;padding:40px;">
            <i class="bi bi-cash" style="font-size:28px;display:block;margin-bottom:8px;"></i>
            Aucune tranche enregistrée
        </div>
        @else
        <div class="table-responsive">
            <table class="table ptable mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Référence</th>
                        <th>Statut</th>
                        <th>Enregistré par</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($consultation->paiements as $p)
                <tr>
                    <td style="white-space:nowrap;">{{ $p->date_paiement->format('d/m/Y') }}</td>
                    <td style="font-weight:700;color:#1B3A6B;">{{ $p->montantFormate() }}</td>
                    <td>{{ $p->modeLabel() }}</td>
                    <td style="color:#888;">{{ $p->reference ?? '—' }}</td>
                    <td>
                        <span class="bpay bpay-{{ $p->statut }}">{{ $p->statutLabel() }}</span>
                    </td>
                    <td style="color:#888;">
                        
                        {{ $p->enregistrePar?->first_name ?? '—' }}
                    </td>
                    <td style="color:#888;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                        title="{{ $p->note }}">
                        {{ $p->note ?? '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            {{-- Bouton modifier (ouvre modal) --}}
                            <button class="btn-vf btn-ghost-vf" style="padding:4px 8px;"
                                    onclick="openEditPaiement({{ $p->id }},
                                        {{ $p->montant }}, '{{ $p->devise }}',
                                        '{{ $p->mode }}', '{{ $p->statut }}',
                                        '{{ $p->date_paiement->format('Y-m-d') }}',
                                        '{{ $p->reference }}', '{{ addslashes($p->note) }}')"
                                    title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </button>
                            {{-- Supprimer --}}
                            <form method="POST"
                                  action="{{ route('admin.consultations.paiements.destroy', [$consultation, $p]) }}"
                                  onsubmit="return confirm('Supprimer cette tranche ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-vf btn-danger-vf" style="padding:4px 8px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ONGLET 4 — INFOS CLIENT
═══════════════════════════════════════════════════════════ --}}
<div class="tab-pane" id="tab-details">
    <div class="card-section">
        <div class="card-header-vf">
            <h6><i class="bi bi-person-lines-fill me-2"></i>Informations client</h6>
        </div>
        <div class="card-body-vf">
            <div class="row g-3">
                @php
                    $infos = [
                        'Nom complet'     => $consultation->client_name,
                        'Email'           => $consultation->client_email,
                        'Téléphone'       => $consultation->phone,
                        'Nationalité'     => $consultation->nationality,
                        'Pays destination'=> $consultation->destination_country,
                        'Type de projet'  => $consultation->projetLabel(),
                        'Date départ souh.'=> $consultation->departure_date,
                        'Canal préféré'   => $consultation->canalLabel(),
                        'Date confirmée'  => $consultation->date_confirmee?->format('d/m/Y H:i'),
                        'Durée (min)'     => $consultation->duree_minutes,
                    ];
                @endphp
                @foreach($infos as $label => $val)
                <div class="col-md-6">
                    <div style="font-size:10px;font-weight:700;color:#aaa;text-transform:uppercase;
                         letter-spacing:.5px;margin-bottom:3px;">{{ $label }}</div>
                    <div style="font-size:13px;color:#333;">{{ $val ?? '—' }}</div>
                </div>
                @endforeach
            </div>

            @if($consultation->note_admin)
            <div class="mt-4 p-3 rounded-3"
                 style="background:#fffbf0;border:1px solid rgba(245,166,35,.2);">
                <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;margin-bottom:6px;">
                    Note admin
                </div>
                <div style="font-size:13px;color:#555;">{{ $consultation->note_admin }}</div>
            </div>
            @endif

            @if($consultation->motif_declin)
            <div class="mt-3 p-3 rounded-3"
                 style="background:rgba(226,75,74,.04);border:1px solid rgba(226,75,74,.15);">
                <div style="font-size:10px;font-weight:700;color:#a32d2d;text-transform:uppercase;margin-bottom:6px;">
                    Motif de déclin
                </div>
                <div style="font-size:13px;color:#555;">{{ $consultation->motif_declin }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ONGLET 5 — APPROUVER / DÉCLINER
═══════════════════════════════════════════════════════════ --}}
@if(in_array($consultation->statut, ['en_attente','en_cours']))
<div class="tab-pane" id="tab-approuver">
    <div class="row g-4">
        {{-- Approuver --}}
        <div class="col-md-7">
            <div class="card-section">
                <div class="card-header-vf" style="border-left:3px solid #1cc88a;">
                    <h6><i class="bi bi-check-circle me-2" style="color:#1cc88a;"></i>Approuver la consultation</h6>
                </div>
                <div class="card-body-vf">
                    <form method="POST" action="{{ route('admin.consultations.approuver', $consultation) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Date & heure confirmées *</label>
                                <input type="datetime-local" name="date_confirmee" class="fi"
                                       value="{{ $consultation->date_confirmee?->format('Y-m-d\TH:i') }}"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Durée (minutes) *</label>
                                <input type="number" name="duree_minutes" class="fi"
                                       value="{{ $consultation->duree_minutes ?? 60 }}"
                                       min="15" max="240" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Canal *</label>
                                <select name="canal" class="fi" required>
                                    @foreach(['video'=>'Vidéoconférence','telephone'=>'Téléphone','presentiel'=>'Présentiel'] as $v=>$l)
                                    <option value="{{ $v }}" {{ $consultation->canal == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Consultant assigné</label>
                                <select name="consultant_id" class="fi">
                                    <option value="">— Moi-même —</option>
                                    @foreach($consultants as $con)
                                    <option value="{{ $con->id }}"
                                        {{ $consultation->consultant_id == $con->id ? 'selected' : '' }}>
                                        {{ $con->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Lien visioconférence</label>
                                <input type="url" name="lien_visio" class="fi"
                                       value="{{ $consultation->lien_visio }}"
                                       placeholder="https://meet.google.com/...">
                            </div>
                            <div class="col-12">
                                <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Note pour le client</label>
                                <textarea name="note_admin" class="fi" rows="3"
                                    placeholder="Instructions, préparatifs, documents à apporter...">{{ $consultation->note_admin }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn-vf mt-3"
                                style="background:#1cc88a;color:#fff;">
                            <i class="bi bi-check-circle"></i> Approuver la consultation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Décliner --}}
        <div class="col-md-5">
            <div class="card-section">
                <div class="card-header-vf" style="border-left:3px solid #E24B4A;">
                    <h6><i class="bi bi-x-circle me-2" style="color:#E24B4A;"></i>Décliner la consultation</h6>
                </div>
                <div class="card-body-vf">
                    <form method="POST" action="{{ route('admin.consultations.decliner', $consultation) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Motif de déclin *</label>
                            <textarea name="motif_declin" class="fi" rows="5"
                                      placeholder="Expliquez pourquoi cette consultation est déclinée (min. 10 caractères)..."
                                      required minlength="10"></textarea>
                        </div>
                        <button type="submit" class="btn-vf btn-danger-vf"
                                onclick="return confirm('Confirmer le déclin de cette consultation ?')">
                            <i class="bi bi-x-circle"></i> Décliner
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════
     MODAL — Modifier une tranche de paiement
═══════════════════════════════════════════════════════════ --}}
<div id="modalEditPaiement" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:28px;width:90%;max-width:560px;
                max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.15);">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0" style="font-size:14px;font-weight:700;color:#1B3A6B;">
                <i class="bi bi-pencil-square me-2"></i>Modifier la tranche
            </h6>
            <button onclick="closeEditPaiement()"
                    style="background:none;border:none;font-size:18px;color:#aaa;cursor:pointer;">✕</button>
        </div>
        <form id="editPaiementForm" method="POST" action="">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Montant *</label>
                    <input type="number" name="montant" id="ep_montant" class="fi" required min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Devise</label>
                    <select name="devise" id="ep_devise" class="fi">
                        <option value="XAF">XAF</option>
                        <option value="EUR">EUR</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Mode</label>
                    <select name="mode" id="ep_mode" class="fi">
                        <option value="especes">Espèces</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="virement">Virement</option>
                        <option value="carte">Carte</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Statut</label>
                    <select name="statut" id="ep_statut" class="fi">
                        <option value="recu">Reçu</option>
                        <option value="en_attente">En attente</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Date</label>
                    <input type="date" name="date_paiement" id="ep_date" class="fi" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Référence</label>
                    <input type="text" name="reference" id="ep_ref" class="fi">
                </div>
                <div class="col-12">
                    <label class="form-label" style="font-size:11px;font-weight:700;color:#888;">Note</label>
                    <input type="text" name="note" id="ep_note" class="fi">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn-vf btn-primary-vf">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
                <button type="button" onclick="closeEditPaiement()" class="btn-vf btn-ghost-vf">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// ── Navigation onglets ────────────────────────────────────
function showTab(tabId) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.vnav-item').forEach(n => n.classList.remove('active'));
    const pane = document.getElementById(tabId);
    if (pane) pane.classList.add('active');
    // Activer nav item correspondant
    const navMap = {
        'tab-pipeline':   0,
        'tab-consultant': 1,
        'tab-paiements':  2,
        'tab-details':    3,
        'tab-approuver':  4,
    };
    const items = document.querySelectorAll('.vnav-item');
    if (items[navMap[tabId]]) items[navMap[tabId]].classList.add('active');
}

// Ouvrir sur ancre URL
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#','');
    if (hash) showTab('tab-' + hash);
});

// ── Modal modification tranche ───────────────────────────
function openEditPaiement(id, montant, devise, mode, statut, date, ref, note) {
    const baseUrl = '{{ route('admin.consultations.paiements.update', [$consultation, '__ID__']) }}';
    document.getElementById('editPaiementForm').action = baseUrl.replace('__ID__', id);
    document.getElementById('ep_montant').value = montant;
    document.getElementById('ep_devise').value  = devise;
    document.getElementById('ep_mode').value    = mode;
    document.getElementById('ep_statut').value  = statut;
    document.getElementById('ep_date').value    = date;
    document.getElementById('ep_ref').value     = ref;
    document.getElementById('ep_note').value    = note;
    document.getElementById('modalEditPaiement').style.display = 'flex';
}

function closeEditPaiement() {
    document.getElementById('modalEditPaiement').style.display = 'none';
}

// Fermer modal au clic dehors
document.getElementById('modalEditPaiement').addEventListener('click', function(e) {
    if (e.target === this) closeEditPaiement();
});

function openDocModal(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
 
function closeDocModal(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
    // Vérifier si d'autres modals sont encore ouverts
    const anyOpen = [...document.querySelectorAll('[id^="docModal_"]')]
        .some(m => m.style.display === 'flex');
    if (!anyOpen) document.body.style.overflow = '';
}
 
// Fermeture avec Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="docModal_"]').forEach(m => {
            m.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});
</script>
@endpush

@endsection