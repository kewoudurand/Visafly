{{-- FILE: resources/views/components/notifications-bell.blade.php --}}
<div class="dropdown" id="notificationsDropdown">
    <button class="vf-icon-btn vf-desktop-only" type="button" data-bs-toggle="dropdown" 
            title="Notifications" id="notificationsBell" style="position: relative;">
        <i class="bi bi-bell" style="font-size:16px;"></i>
        <span class="vf-notif-dot" id="notifDot" style="
            position: absolute;
            top: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background-color: #f44336;
            border-radius: 50%;
            display: none;
        "></span>
    </button>

    {{-- Dropdown Menu --}}
    <div class="dropdown-menu dropdown-menu-end shadow-lg" style="
        width: 400px;
        max-height: 600px;
        border-radius: 0.75rem;
        border: 1px solid #eee;
    " id="notificationsMenu">
        {{-- Header --}}
        <div style="
            padding: 1rem;
            border-bottom: 2px solid #F5A623;
            background-color: #f8f9fa;
        ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color: #1B3A6B;">🔔 Notifications</h6>
                <small>
                    <span id="unreadCount" class="badge bg-danger">0</span>
                </small>
            </div>
        </div>

        {{-- Notifications List --}}
        <div style="max-height: 450px; overflow-y: auto;" id="notificationsList">
            <div class="text-center py-5 text-muted">
                <div style="font-size: 2rem; opacity: 0.3;">📭</div>
                <p>Aucune notification</p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="
            padding: 0.75rem;
            border-top: 1px solid #eee;
            background-color: #f8f9fa;
            display: flex;
            gap: 0.5rem;
        ">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                📋 Voir Tout
            </a>
            <button class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="markAllAsRead()">
                ✅ Marquer Tout
            </button>
        </div>
    </div>
</div>

<script>
    // Charger les notifications au chargement
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        
        // Rafraîchir toutes les 30 secondes
        setInterval(loadNotifications, 30000);
    });

    function loadNotifications() {
        fetch('{{ route("notifications.getUnread") }}')
            .then(response => response.json())
            .then(data => {
                renderNotifications(data);
                updateUnreadCount(data.unread_count);
            })
            .catch(error => console.error('Erreur:', error));
    }

    function renderNotifications(data) {
        const list = document.getElementById('notificationsList');
        const notifications = data.notifications;

        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <div style="font-size: 2rem; opacity: 0.3;">📭</div>
                    <p>Aucune notification</p>
                </div>
            `;
            return;
        }

        list.innerHTML = notifications.map(notif => `
            <div class="notification-item" style="
                padding: 1rem;
                border-bottom: 1px solid #eee;
                cursor: pointer;
                background-color: ${notif.is_read ? 'white' : '#f0f7ff'};
                transition: background-color 0.2s;
            " onmouseover="this.style.backgroundColor='#f8f9fa'" 
               onmouseout="this.style.backgroundColor='${notif.is_read ? 'white' : '#f0f7ff'}'">
                
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div style="flex-grow: 1;">
                        <h6 class="mb-1" style="color: #1B3A6B; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 0.5rem;">
                                ${getEmoji(notif.type)}
                            </span>
                            ${notif.title}
                        </h6>
                        <p class="mb-1 small" style="color: #555; line-height: 1.4;">
                            ${notif.message}
                        </p>
                        <small style="color: #999;">
                            ${formatDate(notif.created_at)}
                        </small>
                    </div>
                    <button class="btn btn-sm btn-close" 
                            onclick="deleteNotification(${notif.id}, event)"
                            style="margin-left: 0.5rem;"></button>
                </div>

                ${notif.action_url ? `
                    <a href="${notif.action_url}" class="btn btn-sm" style="
                        background-color: ${getTypeColor(notif.type)};
                        color: white;
                        font-weight: 600;
                        border: none;
                    " onclick="markAsRead(${notif.id})">
                        ${notif.action_label} →
                    </a>
                ` : ''}
            </div>
        `).join('');
    }

    function updateUnreadCount(count) {
        const dot = document.getElementById('notifDot');
        const badge = document.getElementById('unreadCount');

        badge.textContent = count;

        if (count > 0) {
            dot.style.display = 'block';
            badge.style.display = 'inline-block';
        } else {
            dot.style.display = 'none';
            badge.style.display = 'none';
        }
    }

    function markAsRead(notifId) {
        fetch(`/notifications/${notifId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(() => loadNotifications());
    }

    function markAllAsRead() {
        fetch('{{ route("notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(() => {
            loadNotifications();
            alert('✅ Toutes les notifications marquées comme lues');
        });
    }

    function deleteNotification(notifId, event) {
        event.stopPropagation();
        
        if (confirm('Supprimer cette notification?')) {
            fetch(`/notifications/${notifId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(() => loadNotifications());
        }
    }

    function getEmoji(type) {
        const emojis = {
            'withdrawal_initiated': '⏳',
            'withdrawal_approved': '✅',
            'withdrawal_rejected': '❌',
            'affiliation_completed': '👥',
            'commission_earned': '💰',
            'course_created': '📚',
            'lesson_created': '📖',
            'new_student': '🎉',
            'system': 'ℹ️',
        };
        return emojis[type] || '📢';
    }

    function getTypeColor(type) {
        const colors = {
            'withdrawal_initiated': '#FFA726',
            'withdrawal_approved': '#4CAF50',
            'withdrawal_rejected': '#f44336',
            'affiliation_completed': '#4CAF50',
            'commission_earned': '#4CAF50',
            'course_created': '#2196F3',
            'lesson_created': '#2196F3',
            'new_student': '#9C27B0',
            'system': '#1B3A6B',
        };
        return colors[type] || '#999';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) return 'À l\'instant';
        if (diff < 3600) return Math.floor(diff / 60) + ' min';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h';
        if (diff < 604800) return Math.floor(diff / 86400) + 'j';

        return date.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    }
</script>