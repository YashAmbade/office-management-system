<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationBell extends Component
{
    #[Computed]
    public function notifications()
    {
        return Auth::user()->notifications()->latest()->limit(10)->get();
    }

    #[Computed]
    public function unreadCount(): int
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function markAsRead(string $notificationId): void
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        $notification?->markAsRead();

        unset($this->notifications, $this->unreadCount);
    }

    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();

        unset($this->notifications, $this->unreadCount);
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
