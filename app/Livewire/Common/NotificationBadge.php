<?php

declare(strict_types=1);

namespace App\Livewire\Common;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class NotificationBadge extends Component
{
    public bool $isOpen = false;

    public function toggleDropdown(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function markAsRead(string $notificationId): void
    {
        $user = auth()->user();
        if ($user !== null) {
            $notification = $user->notifications()->where('id', $notificationId)->first();
            $notification?->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        $user = auth()->user();
        $user?->unreadNotifications->markAsRead();
    }

    public function render(): View
    {
        $user = auth()->user();
        $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
        $notifications = $user ? $user->notifications()->take(5)->get() : collect();

        return view('livewire.common.notification-badge', [
            'unreadCount' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}
