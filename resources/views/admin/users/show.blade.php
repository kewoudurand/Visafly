{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Profil — '.$user->name)

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}"
       style="width:36px;height:36px;border-radius:8px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.3rem;">Profil utilisateur</h2>
</div>

@if(session('success'))
    <div class="alert rounded-3 d-flex align-items-center gap-2 mb-3"
        style="background:rgba(28,200,138,.08);border:1px solid rgba(28,200,138,.3);color:#0f6e56;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Colonne gauche — Infos --}}
    <div class="col-lg-4">

        {{-- Carte profil --}}
        <div class="rounded-3 p-4 mb-3 text-center"
             style="background:#1B3A6B;box-shadow:0 8px 24px rgba(27,58,107,.2);">
            <div style="width:72px;height:72px;border-radius:50%;background:rgba(245,166,35,.2);
                        border:3px solid #F5A623;display:flex;align-items:center;justify-content:center;
                        margin:0 auto 14px;font-size:24px;font-weight:700;color:#F5A623;">
                {{ strtoupper(substr($user->first_name, 0, 2)) }}
            </div>
            <div style="font-size:16px;font-weight:700;color:#fff;">{{ $user->name }}</div>
            <div style="font-size:12px;color:rgba(255,255,255,.6);margin-top:3px;">{{ $user->email }}</div>
            <div class="mt-3 d-flex justify-content-center gap-2 flex-wrap">
                @foreach($user->roles as $role)
                <span style="padding:3px 12px;border-radius:20px;font-size:11px;font-weight:600;
                             background:rgba(245,166,35,.2);color:#F5A623;">
                    {{ $role->name }}
                </span>
                @endforeach
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:12px;">
                Inscrit le {{ $user->created_at->format('d/m/Y') }}
            </div>
        </div>

        {{-- Infos contact --}}
        <div class="rounded-3 p-4 mb-3"
             style="background:#fff;border:1px solid #eee;">
            <div style="font-size:12px;font-weight:600;color:#888;text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:12px;">Informations</div>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center gap-10">
                    <i class="bi bi-telephone" style="color:#F5A623;width:18px;"></i>
                    <span style="font-size:13px;color:#333;margin-left:10px;">{{ $user->phone ?? '—' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-geo-alt" style="color:#F5A623;width:18px;"></i>
                    <span style="font-size:13px;color:#333;margin-left:10px;">{{ $user->country ?? '—' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-translate" style="color:#F5A623;width:18px;"></i>
                    <span style="font-size:13px;color:#333;margin-left:10px;">{{ $user->language ?? 'fr' }}</span>
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="btn rounded-pill flex-fill fw-semibold"
               style="background:#1B3A6B;color:#fff;font-size:12px;">
                <i class="bi bi-pencil me-1"></i>Modifier
            </a>
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm('Supprimer {{ $user->first_name }} ?')" class="flex-fill">
                @csrf @method('DELETE')
                <button type="submit" class="btn rounded-pill w-100 fw-semibold"
                        style="border:1.5px solid #E24B4A;color:#E24B4A;background:transparent;font-size:12px;">
                    <i class="bi bi-trash me-1"></i>Supprimer
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Colonne droite --}}
    <div class="col-lg-8">

        {{-- Abonnement actuel --}}
        <div class="rounded-3 p-4 mb-3"
             style="background:#fff;border:1px solid #eee;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div style="font-size:14px;font-weight:600;color:#1B3A6B;">
                    <i class="bi bi-credit-card me-2"></i>Abonnement
                </div>
                <button onclick="document.getElementById('abo-form').classList.toggle('d-none')"
                        class="btn btn-sm rounded-pill"
                        style="background:rgba(245,166,35,.12);color:#854F0B;font-size:12px;border:none;">
                    <i class="bi bi-plus me-1"></i>Attribuer
                </button>
            </div>

            @if($abonnement)
            <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                 style="background:rgba(28,200,138,.06);border:1px solid rgba(28,200,138,.2);">
                <div style="width:42px;height:42px;background:rgba(28,200,138,.15);border-radius:10px;
                            display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-patch-check-fill" style="color:#1cc88a;font-size:18px;"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:#0f6e56;">
                        Abonnement {{ ucfirst($abonnement->forfait) }} actif
                    </div>
                    <div style="font-size:12px;color:#888;margin-top:2px;">
                        Du {{ $abonnement->debut_at->format('d/m/Y') }}
                        au {{ $abonnement->fin_at->format('d/m/Y') }}
                        · {{ number_format($abonnement->montant, 0, ',', ' ') }} {{ $abonnement->devise }}
                    </div>
                </div>
                <div class="ms-auto">
                    <span style="font-size:11px;font-weight:600;color:#888;">
                        Réf: {{ $abonnement->reference_paiement }}
                    </span>
                </div>
            </div>
            @else
            <div class="p-3 rounded-3 text-center"
                 style="background:#f8f9fb;border:1px dashed #ddd;">
                <i class="bi bi-credit-card" style="font-size:24px;color:#ddd;display:block;margin-bottom:6px;"></i>
                <span style="font-size:13px;color:#888;">Aucun abonnement actif</span>
            </div>
            @endif

            {{-- Formulaire attribution --}}
            <div id="abo-form" class="mt-3 d-none">
                <form method="POST" action="{{ route('admin.users.toggle-abonnement', $user) }}">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label style="font-size:11px;font-weight:600;color:#1B3A6B;">Forfait</label>
                            <select name="forfait" class="form-select form-select-sm rounded-3"
                                    style="border:1.5px solid #e8e8e8;font-size:13px;">
                                <option value="mensuel">Mensuel — 5 000 XAF</option>
                                <option value="trimestriel">Trimestriel — 12 000 XAF</option>
                                <option value="annuel">Annuel — 40 000 XAF</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-sm rounded-pill w-100 fw-semibold"
                                    style="background:#F5A623;color:#1B3A6B;font-size:12px;">
                                <i class="bi bi-check-circle me-1"></i>Activer l'abonnement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Changer le rôle --}}
        <div class="rounded-3 p-4 mb-3"
             style="background:#fff;border:1px solid #eee;">
            <div style="font-size:14px;font-weight:600;color:#1B3A6B;margin-bottom:12px;">
                <i class="bi bi-shield-lock me-2"></i>Rôle & Permissions
            </div>

            <form method="POST" action="{{ route('admin.users.change-role', $user) }}"
                  class="d-flex gap-2 align-items-center mb-3">
                @csrf
                <select name="role" class="form-select form-select-sm rounded-3"
                        style="border:1.5px solid #e8e8e8;font-size:13px;max-width:220px;">
                    @foreach(\Spatie\Permission\Models\Role::all() as $r)
                        <option value="{{ $r->name }}"
                            {{ $user->hasRole($r->name) ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm rounded-pill fw-semibold"
                        style="background:#1B3A6B;color:#fff;font-size:12px;white-space:nowrap;">
                    <i class="bi bi-arrow-repeat me-1"></i>Changer le rôle
                </button>
            </form>

            {{-- Permissions directes --}}
            <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                        letter-spacing:.6px;margin-bottom:8px;">Permissions via le rôle</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($user->getAllPermissions() as $perm)
                <span style="padding:3px 10px;border-radius:10px;font-size:11px;
                             background:rgba(27,58,107,.07);color:#1B3A6B;">
                    {{ $perm->name }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- Historique abonnements --}}
        @if($historique->count())
        <div class="rounded-3 p-4"
             style="background:#fff;border:1px solid #eee;">
            <div style="font-size:14px;font-weight:600;color:#1B3A6B;margin-bottom:12px;">
                <i class="bi bi-clock-history me-2"></i>Historique abonnements
            </div>
            @foreach($historique as $h)
            <div class="d-flex align-items-center justify-content-between py-2"
                 style="border-bottom:1px solid #f5f5f5;">
                <div>
                    <span style="font-size:13px;font-weight:600;color:#333;">
                        {{ ucfirst($h->forfait) }}
                    </span>
                    <span style="font-size:11px;color:#888;margin-left:8px;">
                        {{ $h->debut_at->format('d/m/Y') }} → {{ $h->fin_at->format('d/m/Y') }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;color:#888;">
                        {{ number_format($h->montant, 0, ',', ' ') }} {{ $h->devise }}
                    </span>
                    @if($h->actif && $h->fin_at >= now())
                        <span style="font-size:10px;padding:2px 7px;border-radius:10px;
                                     background:rgba(28,200,138,.1);color:#0f6e56;">Actif</span>
                    @else
                        <span style="font-size:10px;padding:2px 7px;border-radius:10px;
                                     background:#f0f0f0;color:#888;">Expiré</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
