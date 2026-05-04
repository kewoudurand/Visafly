{{-- FILE: resources/views/notifications/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2" style="color: #1B3A6B;">🔔 Mes Notifications</h1>
        <p class="text-muted">
            <span class="badge bg-danger">{{ $unreadCount }}</span>
            non lue(s)
        </p>
    </div>

    {{-- Actions --}}
    <div class="mb-4">
        <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn" style="background-color: #4CAF50; color: white; font-weight: 600;">
                ✅ Marquer Toutes comme Lues
            </button>
        </form>
        <form method="POST" action="{{ route('notifications.delete-all') }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" 
                    onclick="return confirm('Supprimer TOUTES les notifications?')"
                    style="font-weight: 600;">
                🗑️ Tout Supprimer
            </button>
        </form>
    </div>

    {{-- Notifications List --}}
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <div class="card border-0 shadow-sm" style="
                border-left: 5px solid {{ $notification->getTypeColor() }};
                background-color: {{ $notification->is_read ? 'white' : '#f0f7ff' }};
                border-radius: 0.75rem;
            ">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div style="flex-grow: 1;">
                            <h5 class="mb-2" style="color: #1B3A6B;">
                                <span style="font-size: 1.5rem; margin-right: 0.5rem;">
                                    {{ $notification->getEmoji() }}
                                </span>
                                {{ $notification->title }}
                                @if(!$notification->is_read)
                                    <span class="badge bg-primary ms-2">Nouveau</span>
                                @endif
                            </h5>
                            <p class="mb-2" style="color: #555; line-height: 1.6;">
                                {{ $notification->message }}
                            </p>
                            <small style="color: #999;">
                                📅 {{ $notification->created_at->format('d M Y à H:i') }}
                            </small>
                        </div>

                        {{-- Actions --}}
                        <div style="margin-left: 1rem;">
                            @if(!$notification->is_read)
                                <form method="POST" 
                                      action="{{ route('notifications.mark-read', $notification) }}"
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="font-weight: 600;">
                                        ✅
                                    </button>
                                </form>
                            @endif

                            <form method="POST" 
                                  action="{{ route('notifications.delete', $notification) }}"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-weight: 600;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($notification->action_url)
                        <div class="mt-3">
                            <a href="{{ $notification->action_url }}" class="btn btn-sm" style="
                                background-color: {{ $notification->getTypeColor() }};
                                color: white;
                                font-weight: 600;
                                border: none;
                            ">
                                {{ $notification->action_label }} →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;">📭</div>
                    <h5 style="color: #1B3A6B;">Aucune notification</h5>
                    <p class="text-muted">Vous êtes tous à jour!</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
</div>

<style>
    .space-y-3 > * + * {
        margin-top: 1rem;
    }
</style>
@endsection