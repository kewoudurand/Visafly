@extends('layouts.dashboard')
@section('title', isset($user) ? 'Modifier '.$user->name : 'Nouvel utilisateur')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}"
       style="width:36px;height:36px;border-radius:8px;background:#fff;border:1px solid #e8e8e8;
              display:flex;align-items:center;justify-content:center;color:#1B3A6B;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="fw-bold mb-0" style="color:#1B3A6B;font-size:1.3rem;">
            {{ isset($user) ? 'Modifier '.$user->name : 'Ajouter un utilisateur' }}
        </h2>
        <p class="text-muted mb-0" style="font-size:12px;">
            {{ isset($user) ? 'Modification du compte et des permissions' : 'Créer un nouveau compte VisaFly' }}
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="rounded-3 p-4"
             style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">

            <form method="POST"
                  action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
                @csrf
                @if(isset($user)) @method('PUT') @endif

                {{-- Nom + Email --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:12px;font-weight:600;color:#1B3A6B;">
                            Nom complet *
                        </label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name ?? '') }}"
                               placeholder="Jean Dupont"
                               style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:12px;font-weight:600;color:#1B3A6B;">
                            Email *
                        </label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email ?? '') }}"
                               placeholder="email@exemple.com"
                               style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:12px;font-weight:600;color:#1B3A6B;">
                            Mot de passe {{ isset($user) ? '(laisser vide = inchangé)' : '*' }}
                        </label>
                        <input type="password" name="password"
                               class="form-control rounded-3 @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:12px;font-weight:600;color:#1B3A6B;">
                            Téléphone
                        </label>
                        <input type="text" name="phone"
                               class="form-control rounded-3"
                               value="{{ old('phone', $user->phone ?? '') }}"
                               placeholder="+237 6XX XXX XXX"
                               style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                    </div>
                </div>

                {{-- Pays + Rôle --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:12px;font-weight:600;color:#1B3A6B;">
                            Pays
                        </label>
                        <input type="text" name="country"
                               class="form-control rounded-3"
                               value="{{ old('country', $user->country ?? '') }}"
                               placeholder="Cameroun"
                               style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:12px;font-weight:600;color:#1B3A6B;">
                            Rôle *
                        </label>
                        <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror"
                                style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ (old('role', isset($user) ? ($userRoles[0] ?? '') : '') == $role->name) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn rounded-pill px-4 fw-semibold"
                            style="background:#1B3A6B;color:#fff;font-size:13px;">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ isset($user) ? 'Enregistrer les modifications' : 'Créer l\'utilisateur' }}
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="btn rounded-pill px-4"
                       style="border:1.5px solid #ddd;color:#666;font-size:13px;">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Colonne info --}}
    <div class="col-lg-4">
        <div class="rounded-3 p-4"
             style="background:rgba(27,58,107,.03);border:1px solid rgba(27,58,107,.08);">
            <div style="font-size:13px;font-weight:600;color:#1B3A6B;margin-bottom:12px;">
                <i class="bi bi-shield-check me-2"></i>Rôles disponibles
            </div>
            @foreach($roles as $role)
            <div class="d-flex align-items-center gap-2 mb-2">
                <span style="width:8px;height:8px;border-radius:50%;background:#1B3A6B;flex-shrink:0;"></span>
                <div>
                    <span style="font-size:12px;font-weight:600;color:#1B3A6B;">{{ $role->name }}</span>
                    <span style="font-size:11px;color:#888;margin-left:6px;">
                        {{ $role->permissions->count() }} permissions
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
