{{-- resources/views/admin/roles/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Rôles & Permissions')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Rôles & Permissions</h2>
        <p class="text-muted mb-0" style="font-size:13px;">Gérez les accès de chaque rôle</p>
    </div>
</div>

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

<div class="row g-4">

    {{-- Colonne gauche — Rôles --}}
    <div class="col-lg-5">

        {{-- Créer un rôle --}}
        <div class="rounded-3 p-4 mb-3"
             style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
            <div style="font-size:14px;font-weight:600;color:#1B3A6B;margin-bottom:14px;">
                <i class="bi bi-plus-circle me-2"></i>Créer un rôle
            </div>
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" name="name"
                           class="form-control rounded-3"
                           placeholder="Nom du rôle (ex: moderator)"
                           style="border:1.5px solid #e8e8e8;font-size:13px;padding:10px 14px;">
                </div>
                <div class="mb-3">
                    <label style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;
                                  letter-spacing:.6px;display:block;margin-bottom:8px;">Permissions initiales</label>
                    <div class="row g-1">
                        @foreach($permissions->flatten() as $perm)
                        <div class="col-6">
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;
                                          color:#555;cursor:pointer;padding:3px 0;">
                                <input type="checkbox" name="permissions[]"
                                       value="{{ $perm->name }}"
                                       style="accent-color:#1B3A6B;">
                                {{ $perm->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn rounded-pill w-100 fw-semibold"
                        style="background:#1B3A6B;color:#fff;font-size:13px;">
                    Créer le rôle
                </button>
            </form>
        </div>

        {{-- Liste des rôles --}}
        <div class="rounded-3 overflow-hidden"
             style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
            @foreach($roles as $role)
            <div class="p-3 d-flex align-items-center justify-content-between"
                 style="border-bottom:1px solid #f5f5f5;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:8px;height:8px;border-radius:50%;background:#1B3A6B;flex-shrink:0;"></div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#1B3A6B;">{{ $role->name }}</div>
                        <div style="font-size:11px;color:#888;">
                            {{ $role->permissions_count }} permissions
                            · {{ $role->users_count }} utilisateur(s)
                        </div>
                    </div>
                </div>
                @if(!in_array($role->name, ['super-admin','admin','student']))
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                      onsubmit="return confirm('Supprimer le rôle {{ $role->name }} ?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="width:28px;height:28px;border-radius:6px;border:1px solid #fee;
                                   background:#fff;color:#E24B4A;font-size:12px;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @else
                <span style="font-size:10px;padding:2px 7px;border-radius:8px;
                             background:rgba(27,58,107,.08);color:#1B3A6B;">Protégé</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Colonne droite — Éditer permissions par rôle --}}
    <div class="col-lg-7">
        <div class="rounded-3 p-4"
             style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
            <div style="font-size:14px;font-weight:600;color:#1B3A6B;margin-bottom:16px;">
                <i class="bi bi-shield-lock me-2"></i>Permissions par rôle
            </div>

            @foreach($roles as $role)
            <div class="mb-4 pb-4" style="border-bottom:1px solid #f5f5f5;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:13px;font-weight:700;color:#1B3A6B;">
                        {{ $role->name }}
                    </span>
                    <span style="font-size:11px;color:#888;">
                        {{ $role->permissions->count() }} / {{ $permissions->flatten()->count() }} permissions
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf @method('PUT')
                    <div class="row g-0">
                        @foreach($permissions as $group => $perms)
                        <div class="col-12 mb-3">
                            <div style="font-size:10px;font-weight:600;color:#F5A623;text-transform:uppercase;
                                        letter-spacing:.6px;margin-bottom:6px;">{{ $group }}</div>
                            <div class="row g-1">
                                @foreach($perms as $perm)
                                <div class="col-md-6">
                                    <label style="display:flex;align-items:center;gap:6px;font-size:12px;
                                                  color:#444;cursor:pointer;padding:4px 0;">
                                        <input type="checkbox" name="permissions[]"
                                               value="{{ $perm->name }}"
                                               {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                               style="accent-color:#1B3A6B;">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-sm rounded-pill fw-semibold mt-1"
                            style="background:rgba(27,58,107,.08);color:#1B3A6B;font-size:12px;border:none;">
                        <i class="bi bi-check2 me-1"></i>Enregistrer {{ $role->name }}
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
