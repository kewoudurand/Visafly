@extends('layouts.dashboard')
@section('title', 'Gestion des utilisateurs')

@push('styles')
<style>
    .user-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: #1B3A6B;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; color: #F5A623;
        flex-shrink: 0;
    }
    .role-badge {
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        display: inline-block;
    }
    .role-super-admin { background: rgba(127,119,221,.15); color: #3C3489; }
    .role-admin        { background: rgba(27,58,107,.1);   color: #1B3A6B; }
    .role-instructor   { background: rgba(28,200,138,.1);  color: #0f6e56; }
    .role-consultant   { background: rgba(245,166,35,.15); color: #633806; }
    .role-student      { background: rgba(84,148,243,.1);  color: #185FA5; }
    .role-partner      { background: rgba(226,75,74,.1);   color: #a32d2d; }
    .abo-actif   { background: rgba(28,200,138,.1);  color: #0f6e56; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .abo-inactif { background: rgba(226,75,74,.08);  color: #a32d2d; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .table-vf th { font-size:11px; font-weight:600; color:#888; text-transform:uppercase; letter-spacing:.6px; border:none; padding:12px 16px; background:#f8f9fb; }
    .table-vf td { padding:14px 16px; vertical-align:middle; border-bottom:1px solid #f0f0f0; font-size:13px; }
    .table-vf tr:hover td { background:rgba(27,58,107,.02); }
    .action-btn { width:32px; height:32px; border-radius:8px; border:1px solid #e8e8e8; background:#fff; display:inline-flex; align-items:center; justify-content:center; color:#666; font-size:13px; cursor:pointer; transition:all .2s; text-decoration:none; }
    .action-btn:hover { border-color:#1B3A6B; color:#1B3A6B; }
    .action-btn.danger:hover { border-color:#E24B4A; color:#E24B4A; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#1B3A6B;font-size:1.4rem;">Gestion des utilisateurs</h2>
        <p class="text-muted mb-0" style="font-size:13px;">{{ $users->total() }} utilisateur(s) enregistré(s)</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="btn rounded-pill px-4 fw-semibold"
       style="background:#1B3A6B;color:#fff;font-size:13px;">
        <i class="bi bi-person-plus me-2"></i>Ajouter un utilisateur
    </a>
</div>

{{-- Alertes --}}
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

{{-- Filtres --}}
<div class="p-3 rounded-3 mb-4"
     style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label" style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;">Recherche</label>
            <input type="text" name="search" class="form-control form-control-sm rounded-3"
                   value="{{ request('search') }}"
                   placeholder="Nom ou email..."
                   style="border:1.5px solid #e8e8e8;font-size:13px;">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;">Rôle</label>
            <select name="role" class="form-select form-select-sm rounded-3"
                    style="border:1.5px solid #e8e8e8;font-size:13px;">
                <option value="">Tous les rôles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:.6px;">Abonnement</label>
            <select name="abonnement" class="form-select form-select-sm rounded-3"
                    style="border:1.5px solid #e8e8e8;font-size:13px;">
                <option value="">Tous</option>
                <option value="actif"   {{ request('abonnement') == 'actif'   ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ request('abonnement') == 'inactif' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-sm rounded-pill w-100 fw-semibold"
                    style="background:#1B3A6B;color:#fff;font-size:12px;">
                <i class="bi bi-search me-1"></i>Filtrer
            </button>
        </div>
    </form>
</div>

{{-- Tableau --}}
<div class="rounded-3 overflow-hidden"
     style="background:#fff;border:1px solid #eee;box-shadow:0 2px 8px rgba(27,58,107,.05);">
    <table class="table table-vf mb-0">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Rôle</th>
                <th>Abonnement</th>
                <th>Inscrit le</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            @php
                $abo = $user->abonnements()
                    ->where('actif', true)
                    ->where('fin_at', '>=', now())
                    ->latest()->first();
            @endphp
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar">
                            {{ strtoupper(substr($user->first_name, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-weight:600;color:#1B3A6B;">{{ $user->first_name }}</div>
                            <div style="font-size:11px;color:#888;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @foreach($user->roles as $role)
                        <span class="role-badge role-{{ str_replace(' ', '-', $role->name) }}">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </td>
                <td>
                    @if($abo)
                        <span class="abo-actif">
                            <i class="bi bi-check-circle me-1"></i>{{ number_format($abo->montant, 0, ',', ' ') }} XAF
                        </span>
                        <div style="font-size:10px;color:#888;margin-top:2px;">
                            jusqu'au {{ $abo->fin_at->format('d/m/Y') }}
                        </div>
                    @else
                        <span class="abo-inactif">
                            <i class="bi bi-x-circle me-1"></i>Aucun
                        </span>
                    @endif
                </td>
                <td style="color:#888;font-size:12px;">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td>
                    <div class="d-flex gap-1 justify-content-end">
                        {{-- Voir --}}
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="action-btn" title="Voir le profil">
                            <i class="bi bi-eye"></i>
                        </a>
                        {{-- Modifier --}}
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="action-btn" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        {{-- Supprimer --}}
                        @if($user->id !== auth()->id())
                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Supprimer {{ $user->name }} ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5" style="color:#888;">
                    <i class="bi bi-people" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px;"></i>
                    Aucun utilisateur trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($users->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>
@endif

@endsection
