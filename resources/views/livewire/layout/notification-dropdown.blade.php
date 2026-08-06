<?php

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Get the notifications for the authenticated user.
     */
    public function getNotifications()
    {
        return Auth::user()->notifications()->take(5)->get();
    }

    /**
     * Get the unread notifications count.
     */
    public function getUnreadCountProperty(): int
    {
        return Auth::user()->notifications()->where('is_read', false)->count();
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $id): void
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);
        
        $this->dispatch('notification-updated');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): void
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        
        $this->dispatch('notification-updated');
    }

    /**
     * Delete all notifications.
     */
    public function deleteAll(): void
    {
        Auth::user()->notifications()->delete();
        
        $this->dispatch('notification-updated');
    }
}; ?>

<div class="relative" x-data="{ notificationsOpen: false }" @notification-updated.window="$wire.$refresh">
    <!-- Bell Trigger Button -->
    <button @click="notificationsOpen = ! notificationsOpen" @click.outside="notificationsOpen = false" class="relative p-2 text-gray-400 hover:text-gray-650 dark:hover:text-gray-300 hover:bg-gray-55 dark:hover:bg-gray-800 rounded-xl transition focus:outline-none flex items-center justify-center">
        <!-- Bell Icon -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>

        <!-- Unread Badge Count -->
        @if ($this->unreadCount > 0)
            <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-black text-white ring-2 ring-white dark:ring-gray-900 animate-pulse">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notifications Dropdown Menu -->
    <div x-show="notificationsOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-3xl bg-white dark:bg-gray-900 shadow-2xl ring-1 ring-black/5 dark:ring-white/5 border border-gray-100 dark:border-gray-800 focus:outline-none overflow-hidden" style="display: none;">
        <!-- Header -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-850/50 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <span class="font-bold text-sm text-gray-900 dark:text-white">Notifikasi</span>
            <div class="flex gap-2">
                @if ($this->unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-[10px] text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold">
                        Tandai Semua Dibaca
                    </button>
                @endif
                @if ($this->getNotifications()->count() > 0)
                    <span class="text-gray-300 dark:text-gray-700 text-[10px]">|</span>
                    <button wire:click="deleteAll" class="text-[10px] text-rose-600 hover:text-rose-700 dark:text-rose-405 dark:hover:text-rose-300 font-bold">
                        Hapus Semua
                    </button>
                @endif
            </div>
        </div>

        <!-- List -->
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800/60">
            @forelse ($this->getNotifications() as $noti)
                @php
                    $iconBg = 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400';
                    $iconSvg = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>';
                    
                    if ($noti->type === 'payment_verified') {
                        $iconBg = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-450';
                        $iconSvg = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                    } elseif ($noti->type === 'payment_rejected') {
                        $iconBg = 'bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-455';
                        $iconSvg = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
                    } elseif ($noti->type === 'due_warning') {
                        $iconBg = 'bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-455';
                        $iconSvg = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    } elseif ($noti->type === 'catalog_checkout') {
                        $iconBg = 'bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-455';
                        $iconSvg = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.173-.437.681-.66 1.09-.336l3.52 2.77c.437.344.664.9.435 1.417L14.76 13.52c-.229.517-.798.813-1.346.666l-4.1-1.1c-.548-.147-.905-.724-.766-1.272l1.62-6.388c.14-.548.72-.9 1.266-.766l1.04.28c.548.147.905.724.766 1.272l-1.04-1.28z"/></svg>';
                    }
                @endphp
                
                <div wire:click="markAsRead({{ $noti->id }})" class="p-4 flex gap-3 hover:bg-gray-50/75 dark:hover:bg-gray-850/30 transition cursor-pointer relative {{ !$noti->is_read ? 'bg-indigo-50/15 dark:bg-indigo-950/5' : '' }}">
                    <!-- Status Icon -->
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $iconBg }}">
                        {!! $iconSvg !!}
                    </div>

                    <!-- Message Body -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-xs text-gray-900 dark:text-white leading-tight truncate">
                                {{ $noti->title }}
                            </span>
                            <!-- Unread Indicator Dot -->
                            @if (!$noti->is_read)
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 leading-normal">
                            {{ $noti->message }}
                        </p>
                        <span class="text-[9px] text-gray-400 block mt-2 font-medium">
                            {{ $noti->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-8 h-8 text-gray-350 dark:text-gray-650 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
    </div>
</div>
