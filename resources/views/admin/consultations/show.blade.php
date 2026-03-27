{{-- resources/views/admin/consultations/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Dossier consultation #'.$consultation->id)

@push('styles')
<style>
.dc{background:#fff;border-radius:12px;border:1px solid #eee;padding:20px;margin-bottom:14px;}
.dc-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:14px;display:flex;align-items:center;gap:7px;border-bottom:1px solid #f5f5f5;padding-bottom:10px;}
.ir{display:flex;align-items:flex-start;gap:10px;padding:9px 0;border-bottom:1px solid #f8f8f8;}
.ir:last-child{border-bottom:none;}
.il{font-size:11px;font-weight:600;color:#aaa;text-transform:uppercase;letter-spacing:.5px;min-width:130px;flex-shrink:0;padding-top:1px;}
.iv{font-size:13px;color:#333;line-height:1.5;}
.bs{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:5px;}
.bs-en_attente{background:rgba(245,166,35,.12);color:#633806;}
.bs-en_cours  {background:rgba(27,58,107,.1); color:#1B3A6B;}
.bs-approuvee {background:rgba(28,200,138,.1);color:#0f6e56;}
.bs-declinee  {background:rgba(226,75,74,.1); color:#a32d2d;}
.bs-annulee   {background:#f0f0f0;color:#888;}
.bs-terminee  {background:rgba(127,119,221,.12);color:#3C3489;}
.vi{border:1.5px solid #e8e8e8;border-radius:9px;padding:10px 13px;font-size:13px;width:100%;outline:none;transition:all .2s;}
.vi:focus{border-color:#F5A623;box-shadow:0 0 0 3px rgba(245,166,35,.1);}
.btn-ok{background:#1cc88a;color:#fff;border:none;border-radius:20px;padding:9px 22px;font-size:13px;font-weight:600;cursor:pointer;}
.btn-ok:hover{background:#17a876;}
.btn-no{background:transparent;color:#E24B4A;border:1.5px solid #E24B4A;border-radius:20px;padding:9px 22px;font-size:13px;font-weight:600;cursor:pointer;}
.btn-no:hover{background:#E24B4A;color:#fff;}
.btn-bl{background:#1B3A6B;color:#fff;border:none;border-radius:20px;padding:9px 22px;font-size:13px;font-weight:600;cursor:pointer;}
.btn-bl:hover{background:#152d54;}
.check-row{display:flex;align-items:center;gap:8px;font-size:13px;color:#444;padding:5px 0;}
.check-ok{color:#1cc88a;font-size:16px;}
.check-no{color:#ddd;font-size:16px;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('admin.consultations.index') }}"
       style="width:36px;height:36px;border-radius:8px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;color:#1B3A6B;text-decoration:none;flex-shrink:0;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-fill">
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.25rem;">
            Dossier #{{ $consultation->id }} — {{ $consultation->client_name }}
            @if($consultation->urgent)
                <span style="font-size:11px;padding:3px 10px;border-radius:10px;
                             background:rgba(226,75,74,.1);color:#a32d2d;margin-left:6px;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Urgent
                </span>
            @endif
        </h2>
        <p class="text-muted mb-0" style="font-size:12px;">
            Soumis le {{ $consultation->created_at->format('d/m/Y à H:i') }}
        </p>
    </div>
    <span class="bs bs-{{ $consultation->statut }}">{{ $consultation->statutLabel() }}</span>
</div>

@if(session('success'))
<div class="alert rounded-3 d-flex gap-2 align-items-center mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;font-size:13px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="row g-4">

{{-- ══════════ COLONNE GAUCHE — DOSSIER COMPLET ══════════ --}}
<div class="col-lg-6">

    {{-- Identité --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-person-circle"></i> Identité du demandeur</div>
        <div class="ir"><span class="il">Nom complet</span><span class="iv fw-semibold">{{ $consultation->full_name }}</span></div>
        <div class="ir"><span class="il">Date naissance</span><span class="iv">{{ $consultation->birth_date?->format('d/m/Y') ?? '—' }}</span></div>
        <div class="ir"><span class="il">Nationalité</span><span class="iv">{{ $consultation->nationality ?? '—' }}</span></div>
        <div class="ir"><span class="il">Résidence</span><span class="iv">{{ $consultation->residence_country ?? '—' }}</span></div>
        <div class="ir"><span class="il">Téléphone</span>
            <span class="iv">
                {{ $consultation->phone ?? '—' }}
                @if($consultation->phone)
                <a href="tel:{{ $consultation->phone }}" style="color:#F5A623;font-size:11px;margin-left:6px;">
                    <i class="bi bi-telephone"></i>
                </a>
                @endif
            </span>
        </div>
        <div class="ir"><span class="il">Email</span>
            <span class="iv">
                {{ $consultation->client_email }}
                <a href="mailto:{{ $consultation->client_email }}" style="color:#F5A623;font-size:11px;margin-left:6px;">
                    <i class="bi bi-envelope"></i>
                </a>
            </span>
        </div>
        <div class="ir"><span class="il">Profession</span><span class="iv">{{ $consultation->profession ?? '—' }}</span></div>
    </div>

    {{-- Projet --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-briefcase"></i> Projet d'immigration</div>
        <div class="ir"><span class="il">Type de projet</span><span class="iv fw-semibold">{{ $consultation->projetLabel() }}</span></div>
        <div class="ir"><span class="il">Destination</span><span class="iv">{{ $consultation->destination_country ?? '—' }}</span></div>
        <div class="ir"><span class="il">Date départ</span><span class="iv">{{ $consultation->departure_date ?? '—' }}</span></div>
        <div class="ir"><span class="il">Budget</span><span class="iv">{{ $consultation->budget ?? '—' }}</span></div>
        <div class="ir"><span class="il">Comment connu</span><span class="iv">{{ $consultation->referral_source ?? '—' }}</span></div>
        @if($consultation->message)
        <div class="ir">
            <span class="il">Message</span>
            <span class="iv" style="font-style:italic;color:#555;">{{ $consultation->message }}</span>
        </div>
        @endif
    </div>

    {{-- Formation --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-mortarboard"></i> Formation & Expérience</div>
        <div class="ir"><span class="il">Dernier diplôme</span><span class="iv">{{ $consultation->last_degree ?? '—' }}</span></div>
        <div class="ir"><span class="il">Année obtention</span><span class="iv">{{ $consultation->graduation_year ?? '—' }}</span></div>
        <div class="ir"><span class="il">Domaine étude</span><span class="iv">{{ $consultation->field_of_study ?? '—' }}</span></div>
        <div class="ir"><span class="il">Niveau langue</span>
            <span class="iv">
                @if($consultation->language_level)
                    <span style="padding:2px 10px;border-radius:10px;background:rgba(27,58,107,.08);color:#1B3A6B;font-weight:600;">
                        {{ strtoupper($consultation->language_level) }}
                    </span>
                @else —
                @endif
            </span>
        </div>
        @if($consultation->work_experience)
        <div class="ir"><span class="il">Expérience</span><span class="iv">{{ $consultation->work_experience }}</span></div>
        @endif
    </div>

    {{-- Documents / checklist --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-folder-check"></i> Checklist documents</div>
        <div class="check-row">
            <i class="bi bi-{{ $consultation->passport_valid ? 'check-circle-fill check-ok' : 'circle check-no' }}"></i>
            Passeport valide
        </div>
        <div class="check-row">
            <i class="bi bi-{{ $consultation->documents_available ? 'check-circle-fill check-ok' : 'circle check-no' }}"></i>
            Documents disponibles
        </div>
        <div class="check-row">
            <i class="bi bi-{{ $consultation->admission_or_contract ? 'check-circle-fill check-ok' : 'circle check-no' }}"></i>
            Lettre d'admission / contrat de travail
        </div>
        <div class="check-row">
            <i class="bi bi-{{ $consultation->financial_proof ? 'check-circle-fill check-ok' : 'circle check-no' }}"></i>
            Preuve financière
        </div>
        <div class="check-row">
            <i class="bi bi-{{ $consultation->visa_history ? 'info-circle-fill' : 'dash-circle check-no' }}"
               style="{{ $consultation->visa_history ? 'color:#F5A623;' : '' }}"></i>
            Historique visa
            @if($consultation->visa_history && $consultation->visa_history_details)
                <span style="font-size:11px;color:#888;margin-left:4px;">— {{ $consultation->visa_history_details }}</span>
            @endif
        </div>
        <div class="check-row">
            <i class="bi bi-{{ $consultation->need_consultation ? 'check-circle-fill check-ok' : 'circle check-no' }}"></i>
            Souhaite une consultation individuelle
        </div>
    </div>

</div>

{{-- ══════════ COLONNE DROITE — ACTIONS ADMIN ══════════ --}}
<div class="col-lg-6">

    {{-- Actions rapides --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-lightning-charge"></i> Actions rapides</div>
        <div class="d-flex flex-wrap gap-2">

            @if($consultation->statut === 'en_attente')
            <form method="POST" action="{{ route('admin.consultations.en-cours', $consultation) }}">
                @csrf
                <button type="submit" class="btn rounded-pill fw-semibold"
                        style="background:rgba(27,58,107,.1);color:#1B3A6B;border:none;font-size:12px;padding:7px 14px;">
                    <i class="bi bi-hourglass-split me-1"></i>Passer en examen
                </button>
            </form>
            @endif

            @if($consultation->statut === 'approuvee')
            <form method="POST" action="{{ route('admin.consultations.terminer', $consultation) }}">
                @csrf
                <button type="submit" class="btn rounded-pill fw-semibold"
                        style="background:rgba(127,119,221,.15);color:#3C3489;border:none;font-size:12px;padding:7px 14px;">
                    <i class="bi bi-check-all me-1"></i>Marquer terminée
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('admin.consultations.toggle-urgent', $consultation) }}">
                @csrf
                <button type="submit" class="btn rounded-pill fw-semibold"
                        style="{{ $consultation->urgent
                            ? 'background:rgba(226,75,74,.1);color:#a32d2d;'
                            : 'background:rgba(245,166,35,.1);color:#633806;' }}
                        border:none;font-size:12px;padding:7px 14px;">
                    <i class="bi bi-exclamation-triangle{{ $consultation->urgent ? '-fill' : '' }} me-1"></i>
                    {{ $consultation->urgent ? 'Retirer urgence' : 'Marquer urgente' }}
                </button>
            </form>

            @role('super-admin')
            <form method="POST" action="{{ route('admin.consultations.destroy', $consultation) }}"
                  onsubmit="return confirm('Supprimer définitivement ce dossier ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn rounded-pill fw-semibold"
                        style="border:1px solid rgba(226,75,74,.3);color:#a32d2d;background:transparent;font-size:12px;padding:7px 14px;">
                    <i class="bi bi-trash me-1"></i>Supprimer
                </button>
            </form>
            @endrole
        </div>
    </div>

    {{-- ── APPROUVER ── --}}
    @if($consultation->peutEtreTraitee())
    <div class="dc" id="approuver" style="border-color:rgba(28,200,138,.3);">
        <div class="dc-title" style="color:#0f6e56;"><i class="bi bi-check-circle"></i> Approuver la consultation</div>
        <form method="POST" action="{{ route('admin.consultations.approuver', $consultation) }}">
            @csrf
            <div class="row g-3">
                <div class="col-6">
                    <label style="font-size:11px;font-weight:600;color:#1B3A6B;display:block;margin-bottom:4px;">Date & heure *</label>
                    <input type="datetime-local" name="date_confirmee" class="vi" required
                           value="{{ $consultation->date_confirmee?->format('Y-m-d\TH:i') }}">
                </div>
                <div class="col-6">
                    <label style="font-size:11px;font-weight:600;color:#1B3A6B;display:block;margin-bottom:4px;">Durée (min) *</label>
                    <input type="number" name="duree_minutes" class="vi" value="{{ $consultation->duree_minutes ?? 60 }}" min="15" max="240" required>
                </div>
                <div class="col-6">
                    <label style="font-size:11px;font-weight:600;color:#1B3A6B;display:block;margin-bottom:4px;">Canal *</label>
                    <select name="canal" class="vi">
                        <option value="video"      {{ $consultation->canal=='video'?'selected':'' }}>Vidéoconférence</option>
                        <option value="telephone"  {{ $consultation->canal=='telephone'?'selected':'' }}>Téléphone</option>
                        <option value="presentiel" {{ $consultation->canal=='presentiel'?'selected':'' }}>Présentiel</option>
                    </select>
                </div>
                <div class="col-6">
                    <label style="font-size:11px;font-weight:600;color:#1B3A6B;display:block;margin-bottom:4px;">Consultant assigné</label>
                    <select name="consultant_id" class="vi">
                        <option value="">— Moi-même —</option>
                        @foreach($consultants as $ct)
                        <option value="{{ $ct->id }}" {{ $consultation->consultant_id==$ct->id?'selected':'' }}>
                            {{ $ct->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label style="font-size:11px;font-weight:600;color:#1B3A6B;display:block;margin-bottom:4px;">Lien visioconférence</label>
                    <input type="url" name="lien_visio" class="vi"
                           value="{{ $consultation->lien_visio }}"
                           placeholder="https://meet.google.com/...">
                </div>
                <div class="col-12">
                    <label style="font-size:11px;font-weight:600;color:#1B3A6B;display:block;margin-bottom:4px;">Message au client</label>
                    <textarea name="note_admin" class="vi" rows="2"
                              placeholder="Instructions, préparation requise...">{{ $consultation->note_admin }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-ok w-100">
                        <i class="bi bi-check-circle-fill me-2"></i>Confirmer l'approbation
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── DÉCLINER ── --}}
    <div class="dc" style="border-color:rgba(226,75,74,.3);">
        <div class="dc-title" style="color:#a32d2d;"><i class="bi bi-x-circle"></i> Décliner la consultation</div>
        <form method="POST" action="{{ route('admin.consultations.decliner', $consultation) }}">
            @csrf
            <div class="mb-3">
                <textarea name="motif_declin" class="vi" rows="3" required
                          placeholder="Expliquez clairement la raison du refus..."></textarea>
            </div>
            <button type="submit" class="btn-no w-100">
                <i class="bi bi-x-circle me-2"></i>Décliner ce dossier
            </button>
        </form>
    </div>
    @endif

    {{-- ── ASSIGNER CONSULTANT ── --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-person-check"></i> Assigner un consultant</div>
        <form method="POST" action="{{ route('admin.consultations.assigner', $consultation) }}"
              class="d-flex gap-2">
            @csrf
            <select name="consultant_id" class="vi flex-fill">
                <option value="">— Choisir —</option>
                @foreach($consultants as $ct)
                <option value="{{ $ct->id }}" {{ $consultation->consultant_id==$ct->id?'selected':'' }}>
                    {{ $ct->name }} ({{ $ct->roles->first()?->name }})
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-bl" style="white-space:nowrap;">Assigner</button>
        </form>
        @if($consultation->consultant)
        <div style="font-size:12px;color:#888;margin-top:8px;">
            <i class="bi bi-check-circle-fill" style="color:#1cc88a;"></i>
            Actuellement : <strong>{{ $consultation->consultant->name }}</strong>
        </div>
        @endif
    </div>

    {{-- ── LIEN VISIO ── --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-camera-video"></i> Lien de visioconférence</div>
        <form method="POST" action="{{ route('admin.consultations.lien-visio', $consultation) }}"
              class="d-flex gap-2">
            @csrf
            <input type="url" name="lien_visio" class="vi flex-fill"
                   value="{{ $consultation->lien_visio }}"
                   placeholder="https://meet.google.com/...">
            <button type="submit" class="btn-bl" style="white-space:nowrap;">Enregistrer</button>
        </form>
        @if($consultation->lien_visio)
        <a href="{{ $consultation->lien_visio }}" target="_blank"
           style="font-size:12px;color:#1B3A6B;margin-top:6px;display:inline-flex;align-items:center;gap:4px;">
            <i class="bi bi-box-arrow-up-right"></i>Rejoindre la réunion
        </a>
        @endif
    </div>

    {{-- ── NOTE INTERNE ── --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-sticky"></i> Note interne (non visible par le client)</div>
        @if($consultation->note_admin)
        <div class="mb-3 p-3 rounded-3" style="background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.2);font-size:13px;color:#555;line-height:1.6;">
            {{ $consultation->note_admin }}
        </div>
        @endif
        <form method="POST" action="{{ route('admin.consultations.note', $consultation) }}">
            @csrf
            <textarea name="note_admin" class="vi mb-3" rows="3"
                      placeholder="Ajouter ou remplacer la note interne...">{{ $consultation->note_admin }}</textarea>
            <button type="submit" class="btn-bl">
                <i class="bi bi-save me-1"></i>Enregistrer la note
            </button>
        </form>
    </div>

    {{-- ── MOTIF DÉCLIN ── --}}
    @if($consultation->motif_declin)
    <div class="dc" style="border-color:rgba(226,75,74,.3);">
        <div class="dc-title" style="color:#a32d2d;"><i class="bi bi-x-circle"></i> Motif du déclin</div>
        <p style="font-size:13px;color:#555;line-height:1.6;margin:0;">{{ $consultation->motif_declin }}</p>
    </div>
    @endif

    {{-- ── TIMELINE ── --}}
    <div class="dc">
        <div class="dc-title"><i class="bi bi-clock-history"></i> Historique du dossier</div>
        <div style="padding:4px 0;">
            @php $tl = [
                ['dot'=>'rgba(27,58,107,.1)','ic'=>'bi-plus','col'=>'#1B3A6B','t'=>'Dossier soumis','s'=>$consultation->created_at->format('d/m/Y à H:i'),'show'=>true],
                ['dot'=>'rgba(245,166,35,.2)','ic'=>'bi-hourglass-split','col'=>'#F5A623','t'=>'En cours d\'examen','s'=>null,'show'=>$consultation->statut!=='en_attente'],
                ['dot'=>'rgba(28,200,138,.2)','ic'=>'bi-check','col'=>'#1cc88a','t'=>'Approuvée','s'=>$consultation->date_confirmee?'RDV le '.$consultation->date_confirmee->format('d/m/Y à H:i'):null,'show'=>in_array($consultation->statut,['approuvee','terminee'])],
                ['dot'=>'rgba(127,119,221,.2)','ic'=>'bi-check-all','col'=>'#7F77DD','t'=>'Terminée','s'=>null,'show'=>$consultation->estTerminee()],
                ['dot'=>'rgba(226,75,74,.15)','ic'=>'bi-x','col'=>'#E24B4A','t'=>'Déclinée','s'=>null,'show'=>$consultation->estDeclinee()],
            ]; @endphp
            @foreach($tl as $item)
            @if($item['show'])
            <div style="display:flex;gap:10px;padding-bottom:12px;position:relative;">
                <div style="width:26px;height:26px;border-radius:50%;background:{{ $item['dot'] }};display:flex;align-items:center;justify-content:center;font-size:11px;color:{{ $item['col'] }};flex-shrink:0;">
                    <i class="bi {{ $item['ic'] }}"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#333;">{{ $item['t'] }}</div>
                    @if($item['s'])<div style="font-size:11px;color:#888;">{{ $item['s'] }}</div>@endif
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

</div>
</div>

@endsection