{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Créer un compte — VisaFly')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;
            background:#f0f4f8;padding:2rem 1rem;">

  <div style="width:100%;max-width:520px;">

    {{-- Logo --}}
    <div style="text-align:center;margin-bottom:28px;">
      <a href="{{ url('/') }}" style="text-decoration:none;display:inline-flex;align-items:center;gap:10px;">
        <div style="width:42px;height:42px;background:#1B3A6B;border-radius:11px;
                    display:flex;align-items:center;justify-content:center;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M12 2L2 7l10 5 10-5-10-5z" fill="#F5A623"/>
            <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="#F5A623" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
        <span style="font-size:22px;font-weight:800;color:#1B3A6B;">Visa<span style="color:#F5A623;">Fly</span></span>
      </a>
    </div>

    {{-- Carte --}}
    <div style="background:#fff;border-radius:18px;padding:36px;
                box-shadow:0 8px 40px rgba(27,58,107,.1);border:1px solid rgba(27,58,107,.08);">

      <h1 style="font-size:1.5rem;font-weight:800;color:#1B3A6B;margin-bottom:6px;">
        Créer un compte
      </h1>
      <p style="font-size:13px;color:#888;margin-bottom:28px;">
        Rejoignez VisaFly et commencez votre préparation TCF/TEF.
      </p>

      {{-- Erreurs globales --}}
      @if($errors->any())
      <div style="background:rgba(226,75,74,.07);border:1px solid rgba(226,75,74,.25);
                  border-radius:10px;padding:12px 16px;margin-bottom:20px;">
        @foreach($errors->all() as $error)
        <div style="font-size:12px;color:#a32d2d;display:flex;align-items:center;gap:6px;">
          <i class="bi bi-exclamation-circle-fill" style="font-size:11px;"></i>{{ $error }}
        </div>
        @endforeach
      </div>
      @endif

      <form method="POST" action="{{ route('register.store') }}">
        @csrf

        
        @if($referrer)
            <div class="alert alert-info">
                Vous êtes parrainé par: <strong>{{ $referrer->first_name }}</strong>
                <input type="hidden" name="ref" value="{{ $referralCode }}">
            </div>
        @elseif(request()->query('ref'))
            <input type="hidden" name="ref" value="{{ request()->query('ref') }}">
        @endif

        {{-- Prénom + Nom --}}
        <div class="row g-3 mb-3">
          <div class="col-6">
            <label style="font-size:11px;font-weight:700;color:#1B3A6B;display:block;
                          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;">
              Prénom *
            </label>
            <input type="text" name="first_name"
                   value="{{ old('first_name') }}"
                   placeholder="Jean"
                   style="border:1.5px solid {{ $errors->has('first_name') ? '#E24B4A' : '#e8e8e8' }};
                          border-radius:10px;padding:11px 14px;font-size:13px;
                          width:100%;outline:none;background:#fafafa;
                          transition:border-color .2s;"
                   onfocus="this.style.borderColor='#F5A623';this.style.background='#fff';"
                   onblur="this.style.borderColor='#e8e8e8';this.style.background='#fafafa';">
          </div>
          <div class="col-6">
            <label style="font-size:11px;font-weight:700;color:#1B3A6B;display:block;
                          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;">
              Nom *
            </label>
            <input type="text" name="last_name"
                   value="{{ old('last_name') }}"
                   placeholder="Dupont"
                   style="border:1.5px solid {{ $errors->has('last_name') ? '#E24B4A' : '#e8e8e8' }};
                          border-radius:10px;padding:11px 14px;font-size:13px;
                          width:100%;outline:none;background:#fafafa;
                          transition:border-color .2s;"
                   onfocus="this.style.borderColor='#F5A623';this.style.background='#fff';"
                   onblur="this.style.borderColor='#e8e8e8';this.style.background='#fafafa';">
          </div>
        </div>

        {{-- Email --}}
        <div style="margin-bottom:16px;">
          <label style="font-size:11px;font-weight:700;color:#1B3A6B;display:block;
                        margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;">
            Adresse email *
          </label>
          <input type="email" name="email"
                 value="{{ old('email') }}"
                 placeholder="jean.dupont@email.com"
                 style="border:1.5px solid {{ $errors->has('email') ? '#E24B4A' : '#e8e8e8' }};
                        border-radius:10px;padding:11px 14px;font-size:13px;
                        width:100%;outline:none;background:#fafafa;
                        transition:border-color .2s;"
                 onfocus="this.style.borderColor='#F5A623';this.style.background='#fff';"
                 onblur="this.style.borderColor='#e8e8e8';this.style.background='#fafafa';">
        </div>

        {{-- Mot de passe --}}
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label style="font-size:11px;font-weight:700;color:#1B3A6B;display:block;
                          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;">
              Mot de passe *
            </label>
            <div style="position:relative;">
              <input type="password" name="password" id="pwd"
                     placeholder="••••••••"
                     oninput="strength(this.value)"
                     style="border:1.5px solid {{ $errors->has('password') ? '#E24B4A' : '#e8e8e8' }};
                            border-radius:10px;padding:11px 40px 11px 14px;font-size:13px;
                            width:100%;outline:none;background:#fafafa;transition:border-color .2s;"
                     onfocus="this.style.borderColor='#F5A623';this.style.background='#fff';"
                     onblur="this.style.borderColor='#e8e8e8';this.style.background='#fafafa';">
              <button type="button" onclick="toggle('pwd','ei1')"
                      style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                             background:none;border:none;cursor:pointer;color:#aaa;">
                <i class="bi bi-eye" id="ei1"></i>
              </button>
            </div>
            <div id="sBar" style="height:3px;border-radius:2px;background:#eee;margin-top:6px;transition:all .3s;"></div>
            <div id="sText" style="font-size:10px;color:#aaa;margin-top:3px;"></div>
          </div>
          <div class="col-md-6">
            <label style="font-size:11px;font-weight:700;color:#1B3A6B;display:block;
                          margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px;">
              Confirmer *
            </label>
            <div style="position:relative;">
              <input type="password" name="password_confirmation" id="pwd2"
                     placeholder="••••••••"
                     style="border:1.5px solid #e8e8e8;border-radius:10px;
                            padding:11px 40px 11px 14px;font-size:13px;
                            width:100%;outline:none;background:#fafafa;transition:border-color .2s;"
                     onfocus="this.style.borderColor='#F5A623';this.style.background='#fff';"
                     onblur="this.style.borderColor='#e8e8e8';this.style.background='#fafafa';">
              <button type="button" onclick="toggle('pwd2','ei2')"
                      style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                             background:none;border:none;cursor:pointer;color:#aaa;">
                <i class="bi bi-eye" id="ei2"></i>
              </button>
            </div>
          </div>
        </div>

        {{-- Bouton --}}
        <button type="submit"
                style="width:100%;padding:13px;background:#1B3A6B;color:#fff;
                       border:none;border-radius:25px;font-size:14px;font-weight:700;
                       cursor:pointer;transition:all .2s;
                       box-shadow:0 4px 16px rgba(27,58,107,.3);"
                onmouseover="this.style.background='#152d54';this.style.transform='translateY(-1px)';"
                onmouseout="this.style.background='#1B3A6B';this.style.transform='none';">
          <i class="bi bi-person-plus me-2"></i>Créer mon compte
        </button>

      </form>
    </div>

    {{-- Lien connexion --}}
    <div style="text-align:center;margin-top:20px;font-size:13px;color:#888;">
      Déjà inscrit ?
      <a href="{{ route('login') }}"
         style="color:#1B3A6B;font-weight:700;text-decoration:none;">
        Se connecter →
      </a>
    </div>

  </div>
</div>

<script>
function toggle(id, eid) {
  const i = document.getElementById(id);
  const e = document.getElementById(eid);
  const h = i.type === 'password';
  i.type = h ? 'text' : 'password';
  e.className = h ? 'bi bi-eye-slash' : 'bi bi-eye';
}
function strength(v) {
  const bar = document.getElementById('sBar');
  const txt = document.getElementById('sText');
  let s = 0;
  if (v.length >= 6)           s++;
  if (v.length >= 10)          s++;
  if (/[A-Z]/.test(v))        s++;
  if (/[0-9]/.test(v))        s++;
  if (/[^A-Za-z0-9]/.test(v)) s++;
  const cfg = [
    ['#eee','',     '0%'],
    ['#E24B4A','Trop court','20%'],
    ['#F5A623','Moyen',     '50%'],
    ['#54a3f3','Bon',       '75%'],
    ['#1cc88a','Fort',      '90%'],
    ['#1B3A6B','Excellent', '100%'],
  ];
  const [c,l,w] = cfg[Math.min(s,5)];
  bar.style.background = c;
  bar.style.width = w;
  txt.textContent = l;
  txt.style.color = c;
}
</script>
@endsection