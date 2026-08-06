<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex items-center gap-4">
    @if(auth()->user()->isCustomer())
        <livewire:layout.cart-badge />
        <livewire:layout.notification-dropdown />
    @endif

    <!-- User Info and Avatar Dropdown -->
    <div class="relative" x-data="{ dropdownOpen: false }">
        <button @click="dropdownOpen = ! dropdownOpen" @click.outside="dropdownOpen = false" class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition focus:outline-none">
            <!-- Initials Avatar -->
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center font-extrabold text-sm shadow-md shadow-indigo-500/20">
                {{ auth()->user()->initials }}
            </div>
            
            <!-- Name & Role -->
            <div class="hidden md:flex flex-col items-start text-left mr-2">
                <span class="font-bold text-sm text-gray-800 dark:text-gray-200 leading-none">{{ auth()->user()->name }}</span>
                <span class="font-bold text-[9px] text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mt-1.5">{{ auth()->user()->role }}</span>
            </div>

            <!-- Arrow Icon -->
            <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-2xl bg-white dark:bg-gray-900 py-2 shadow-lg ring-1 ring-black/5 dark:ring-white/5 border border-gray-100 dark:border-gray-800 focus:outline-none" style="display: none;">
            <a href="{{ route('profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-850/50 transition font-medium" wire:navigate>
                {{ __('Ubah Profil') }}
            </a>
            
            <button wire:click="logout" class="w-full text-left block px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition font-bold border-t border-gray-100 dark:border-gray-800 mt-1">
                {{ __('Log Out') }}
            </button>
        </div>
    </div>
</div>
