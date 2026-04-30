{{-- resources/views/users/profil.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Mon profil — VisaFly')

@push('styles')
<style>
.pf-card{background:#fff;border-radius:14px;border:1px solid #eee;
         padding:24px;box-shadow:0 2px 12px rgba(27,58,107,.05);margin-bottom:20px;}
.pf-section-title{font-size:13px;font-weight:700;color:#1B3A6B;margin-bottom:18px;
                  padding-bottom:10px;border-bottom:2px solid rgba(27,58,107,.07);
                  display:flex;align-items:center;gap:8px;}
.pf-input{border:1.5px solid #e8e8e8;border-radius:10px;padding:11px 14px;
          font-size:13px;width:100%;outline:none;transition:all .2s;color:#333;
          background:#fafafa;}
.pf-input:focus{border-color:#F5A623;background:#fff;
                box-shadow:0 0 0 3px rgba(245,166,35,.08);}
.pf-input.is-invalid{border-color:#E24B4A;background:#fff;}
.pf-label{font-size:11px;font-weight:700;color:#1B3A6B;display:block;
          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;}
.pf-hint{font-size:11px;color:#aaa;margin-top:4px;}
.btn-save{background:#1B3A6B;color:#fff;border:none;border-radius:25px;
          padding:11px 28px;font-size:13px;font-weight:700;cursor:pointer;
          transition:all .2s;display:inline-flex;align-items:center;gap:8px;}
.btn-save:hover{background:#152d54;transform:translateY(-1px);}
.btn-danger{background:transparent;color:#E24B4A;border:1.5px solid #E24B4A;
            border-radius:25px;padding:9px 20px;font-size:13px;font-weight:600;
            cursor:pointer;transition:all .2s;}
.btn-danger:hover{background:#E24B4A;color:#fff;}
.avatar-wrap{position:relative;width:88px;height:88px;flex-shrink:0;}
.avatar-circle{width:88px;height:88px;border-radius:50%;background:#1B3A6B;
               border:3px solid #F5A623;display:flex;align-items:center;
               justify-content:center;font-size:28px;font-weight:800;color:#F5A623;
               overflow:hidden;cursor:pointer;}
.avatar-circle img{width:100%;height:100%;object-fit:cover;}
.avatar-overlay{position:absolute;inset:0;border-radius:50%;background:rgba(0,0,0,.45);
                display:none;align-items:center;justify-content:center;cursor:pointer;}
.avatar-wrap:hover .avatar-overlay{display:flex;}
.lang-option{display:flex;align-items:center;gap:8px;padding:10px 14px;
             border:1.5px solid #e8e8e8;border-radius:10px;cursor:pointer;
             transition:all .2s;background:#fafafa;}
.lang-option:has(input:checked){border-color:#1B3A6B;background:rgba(27,58,107,.04);}
.lang-option input{accent-color:#1B3A6B;}
.strength-bar{height:4px;border-radius:2px;transition:all .3s;background:#eee;}
.strength-bar.s1{background:#E24B4A;width:25%;}
.strength-bar.s2{background:#F5A623;width:50%;}
.strength-bar.s3{background:#1cc88a;width:75%;}
.strength-bar.s4{background:#1B3A6B;width:100%;}
</style>
@endpush

@section('content')

{{-- En-tête --}}
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="{{ route('dashboard.index') }}"
     style="width:36px;height:36px;border-radius:9px;background:#fff;border:1px solid #e8e8e8;
            display:flex;align-items:center;justify-content:center;
            color:#1B3A6B;text-decoration:none;flex-shrink:0;">
    <i class="bi bi-arrow-left"></i>
  </a>
  <div>
    <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.3rem;">Mon profil</h2>
    <p class="text-muted mb-0" style="font-size:12px;">Gérez vos informations personnelles</p>
  </div>
</div>

{{-- Alertes --}}
@if(session('success'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
  <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('success_password'))
<div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
     style="background:rgba(27,58,107,.06);border:1px solid rgba(27,58,107,.2);color:#1B3A6B;">
  <i class="bi bi-shield-check-fill"></i> {{ session('success_password') }}
</div>
@endif

<div class="row g-4">

  {{-- ══ COLONNE GAUCHE ══ --}}
  <div class="col-lg-4">

    {{-- Carte avatar --}}
    <div class="pf-card text-center">

      {{-- Avatar avec overlay upload --}}
      <div style="display:flex;justify-content:center;margin-bottom:16px;">
        <div class="avatar-wrap" onclick="document.getElementById('avatarInput').click()">
          <div class="avatar-circle">
            @if($user->avatar)
              <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->first_name }}">
            @else
              @php $parts = explode(' ', trim($user->first_name)); @endphp
              {{ strtoupper(substr($parts[0],0,1)) }}{{ isset($parts[1]) ? strtoupper(substr($parts[1],0,1)) : '' }}
            @endif
          </div>
          <div class="avatar-overlay">
            <i class="bi bi-camera-fill" style="color:#fff;font-size:20px;"></i>
          </div>
        </div>
      </div>

      {{-- Nom + rôle --}}
      <div style="font-size:17px;font-weight:800;color:#1B3A6B;">{{ $user->first_name }} {{ $user->last_name }}</div>
      <div style="font-size:12px;color:#888;margin-bottom:4px;">{{ $user->email }}</div>
      @if($user->roles->isNotEmpty())
        <span style="font-size:11px;padding:3px 12px;border-radius:10px;
                     background:rgba(27,58,107,.08);color:#1B3A6B;font-weight:600;">
          {{ $user->roles->first()->name }}
        </span>
      @endif

      <div style="margin-top:16px;font-size:11px;color:#aaa;">
        Membre depuis {{ $user->created_at->format('d/m/Y') }}
      </div>

      {{-- Form upload avatar (silencieux) --}}
      <form id="avatarForm" method="POST" action="{{ route('profil.avatar') }}"
            enctype="multipart/form-data">
        @csrf
        <input type="file" id="avatarInput" name="avatar" accept="image/*"
               style="display:none;" onchange="this.form.submit()">
      </form>

      @if($user->avatar)
      <form method="POST" action="{{ route('profil.avatar.delete') }}"
            onsubmit="return confirm('Supprimer la photo de profil ?')"
            style="margin-top:10px;">
        @csrf @method('DELETE')
        <button type="submit"
                style="font-size:12px;color:#E24B4A;background:none;border:none;
                       cursor:pointer;text-decoration:underline;">
          Supprimer la photo
        </button>
      </form>
      @endif

      {{-- Infos rapides --}}
      <div style="margin-top:20px;text-align:left;border-top:1px solid #f5f5f5;padding-top:16px;">
        @foreach([
          ['bi-telephone','Téléphone', $user->phone ?? 'Non renseigné'],
          ['bi-geo-alt',  'Pays',      $user->country ?? 'Non renseigné'],
          ['bi-translate','Langue',    $user->language ?? 'fr'],
        ] as [$icon, $label, $value])
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <i class="bi {{ $icon }}" style="color:#F5A623;width:16px;font-size:14px;flex-shrink:0;"></i>
          <div>
            <div style="font-size:10px;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">{{ $label }}</div>
            <div style="font-size:13px;color:#333;font-weight:500;">{{ $value }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>

  {{-- ══ COLONNE DROITE ══ --}}
  <div class="col-lg-8">

    {{-- ── Informations personnelles ── --}}
    <div class="pf-card">
      <div class="pf-section-title">
        <i class="bi bi-person-circle" style="color:#F5A623;"></i>
        Informations personnelles
      </div>

      <form method="POST" action="{{ route('profil.update') }}">
        @csrf

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="pf-label">Nom complet *</label>
            <input type="text" name="name" class="pf-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->first_name) }}" placeholder="Jean Dupont">
            @error('name')
              <div style="font-size:11px;color:#E24B4A;margin-top:4px;">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="pf-label">Adresse email *</label>
            <input type="email" name="email" class="pf-input @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}" placeholder="email@exemple.com">
            @error('email')
              <div style="font-size:11px;color:#E24B4A;margin-top:4px;">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="pf-label">Téléphone</label>
            <input type="text" name="phone" class="pf-input"
                   value="{{ old('phone', $user->phone) }}" placeholder="+237 6XX XXX XXX">
            <p class="pf-hint">Incluez l'indicatif pays</p>
          </div>
          <div class="col-md-6">
            <label class="pf-label">Pays de résidence</label>
            <input type="text" name="country" class="pf-input"
                   value="{{ old('country', $user->country) }}" placeholder="Cameroun">
          </div>
        </div>

        {{-- Langue préférée --}}
        <div class="mb-4">
          <label class="pf-label">Langue préférée</label>
          <div class="row g-2">
            @foreach(['fr'=>'🇫🇷 Français','en'=>'🇬🇧 English','de'=>'🇩🇪 Deutsch','pt'=>'🇵🇹 Português'] as $code => $label)
            <div class="col-6 col-md-3">
              <label class="lang-option">
                <input type="radio" name="language" value="{{ $code }}"
                       {{ old('language', $user->language ?? 'fr') === $code ? 'checked' : '' }}>
                <span style="font-size:13px;">{{ $label }}</span>
              </label>
            </div>
            @endforeach
          </div>
        </div>

        <button type="submit" class="btn-save">
          <i class="bi bi-check-circle-fill"></i>
          Enregistrer les modifications
        </button>

      </form>
    </div>

    {{-- ── Mot de passe ── --}}
    <div class="pf-card">
      <div class="pf-section-title">
        <i class="bi bi-shield-lock" style="color:#F5A623;"></i>
        Changer le mot de passe
      </div>

      <form method="POST" action="{{ route('profil.password') }}">
        @csrf

        <div class="mb-3">
          <label class="pf-label">Mot de passe actuel *</label>
          <div style="position:relative;">
            <input type="password" name="current_password" id="currentPwd"
                   class="pf-input @error('current_password') is-invalid @enderror"
                   placeholder="••••••••">
            <button type="button" onclick="togglePwd('currentPwd','eyeIcon0')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:#888;">
              <i class="bi bi-eye" id="eyeIcon0"></i>
            </button>
          </div>
          @error('current_password')
            <div style="font-size:11px;color:#E24B4A;margin-top:4px;">{{ $message }}</div>
          @enderror
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="pf-label">Nouveau mot de passe *</label>
            <div style="position:relative;">
              <input type="password" name="password" id="newPwd"
                     class="pf-input @error('password') is-invalid @enderror"
                     placeholder="••••••••" oninput="checkStrength(this.value)">
              <button type="button" onclick="togglePwd('newPwd','eyeIcon1')"
                      style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                             background:none;border:none;cursor:pointer;color:#888;">
                <i class="bi bi-eye" id="eyeIcon1"></i>
              </button>
            </div>
            <div id="strengthBar" class="strength-bar mt-2"></div>
            <p id="strengthText" class="pf-hint"></p>
            @error('password')
              <div style="font-size:11px;color:#E24B4A;margin-top:4px;">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="pf-label">Confirmer le mot de passe *</label>
            <div style="position:relative;">
              <input type="password" name="password_confirmation" id="confirmPwd"
                     class="pf-input" placeholder="••••••••">
              <button type="button" onclick="togglePwd('confirmPwd','eyeIcon2')"
                      style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                             background:none;border:none;cursor:pointer;color:#888;">
                <i class="bi bi-eye" id="eyeIcon2"></i>
              </button>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-save"
                style="background:rgba(27,58,107,.1);color:#1B3A6B;">
          <i class="bi bi-shield-check-fill"></i>
          Modifier le mot de passe
        </button>

      </form>
    </div>

    {{-- ── Zone de danger ── --}}
    <div class="pf-card" style="border-color:rgba(226,75,74,.2);">
      <div class="pf-section-title" style="color:#a32d2d;border-bottom-color:rgba(226,75,74,.1);">
        <i class="bi bi-exclamation-triangle" style="color:#E24B4A;"></i>
        Zone de danger
      </div>
      <p style="font-size:13px;color:#888;margin-bottom:16px;line-height:1.6;">
        La suppression de votre compte est irréversible. Toutes vos données seront perdues.
      </p>
      <button onclick="document.getElementById('deleteModal').style.display='flex'"
              class="btn-danger">
        <i class="bi bi-trash me-2"></i>Supprimer mon compte
      </button>
    </div>

  </div>
</div>

{{-- Modal suppression compte --}}
<div id="deleteModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
            z-index:1000;align-items:center;justify-content:center;padding:1rem;">
  <div style="background:#fff;border-radius:16px;padding:32px;max-width:440px;width:100%;
              box-shadow:0 24px 60px rgba(0,0,0,.2);">
    <div style="font-size:20px;font-weight:800;color:#a32d2d;margin-bottom:8px;">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>Supprimer le compte
    </div>
    <p style="font-size:13px;color:#666;margin-bottom:20px;line-height:1.6;">
      Cette action est <strong>irréversible</strong>. Toutes vos épreuves, consultations
      et données personnelles seront supprimées définitivement.
    </p>
    <p style="font-size:12px;color:#888;margin-bottom:16px;">
      Tapez <strong>SUPPRIMER</strong> pour confirmer :
    </p>
    <input type="text" id="deleteConfirm" class="pf-input mb-4"
           placeholder="SUPPRIMER" oninput="checkDelete(this.value)">
    <div style="display:flex;gap:10px;">
      <button onclick="document.getElementById('deleteModal').style.display='none'"
              style="flex:1;padding:11px;border-radius:25px;border:1.5px solid #ddd;
                     background:#fff;color:#666;font-size:13px;font-weight:600;cursor:pointer;">
        Annuler
      </button>
      <button id="deleteBtn" disabled
              onclick="document.getElementById('deleteForm').submit()"
              style="flex:1;padding:11px;border-radius:25px;border:none;
                     background:#eee;color:#aaa;font-size:13px;font-weight:600;cursor:not-allowed;">
        Supprimer
      </button>
    </div>
    <form id="deleteForm" method="POST" action="{{ route('profil.delete') }}">
      @csrf @method('DELETE')
    </form>
  </div>
</div>

@push('scripts')
<script>
// Afficher/masquer mot de passe
function togglePwd(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
}

// Jauge de force du mot de passe
function checkStrength(val) {
  const bar  = document.getElementById('strengthBar');
  const text = document.getElementById('strengthText');
  let score  = 0;
  if (val.length >= 8)  score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const levels = [
    ['',''],
    ['s1','Trop court'],
    ['s2','Moyen'],
    ['s3','Bon'],
    ['s4','Excellent'],
  ];
  bar.className  = 'strength-bar mt-2 ' + (levels[score][0] ?? '');
  text.textContent = levels[score][1] ?? '';
}

// Vérifier saisie "SUPPRIMER"
function checkDelete(val) {
  const btn = document.getElementById('deleteBtn');
  const ok  = val.trim() === 'SUPPRIMER';
  btn.disabled = !ok;
  btn.style.background  = ok ? '#E24B4A' : '#eee';
  btn.style.color       = ok ? '#fff'    : '#aaa';
  btn.style.cursor      = ok ? 'pointer' : 'not-allowed';
}
</script>
@endpush

@endsection