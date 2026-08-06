<nav class="space-y-1 py-4">
    @if(auth()->user()->isAdmin())
        <!-- Admin Links -->
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Dashboard
        </a>
        <a href="{{ route('admin.packages') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.packages') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            Paket Lebaran
        </a>
        <a href="{{ route('admin.customers') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.customers') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20M4.12 18.096a11.386 11.386 0 019.09-1.04m-9.09 1.04a4.125 4.125 0 017.533-2.493m0-2.453a3.935 3.935 0 003.478-3.974m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
            Data Pelanggan
        </a>
        <a href="{{ route('admin.payments') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.payments') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375c1.88 0 3.402-1.314 3.56-3M9 15h.008v.008H9V15zm0 2.25h.008v.008H9v-.008zM9.75 3.75h.008v.008H9.75V3.75zM12 3.75h.008v.008H12V3.75zm2.25 0h.008v.008h-.008V3.75zM12 21a9.003 9.003 0 008.354-5.646 9.003 9.003 0 00-8.354-5.646 9.003 9.003 0 00-8.354 5.646 9.003 9.003 0 008.354 5.646z" />
            </svg>
            Pembayaran
        </a>
        <a href="{{ route('admin.reports') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            Laporan
        </a>
    @elseif(auth()->user()->isCustomer())
        <!-- Customer Links -->
        <a href="{{ route('customer.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('customer.dashboard') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Dashboard
        </a>
        <a href="{{ route('customer.catalog') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('customer.catalog') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-18 20.25h-2.25a2.25 2.25 0 0 1-13.5 18v-2.25Z" />
            </svg>
            Katalog Paket
        </a>
        <a href="{{ route('customer.cart') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('customer.cart') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.116 60.116 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            Keranjang Belanja
        </a>
        <a href="{{ route('customer.my-package') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('customer.my-package') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            Paket Saya
        </a>
        <a href="{{ route('customer.upload-payment') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('customer.upload-payment') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" />
            </svg>
            Unggah Bukti
        </a>
        <a href="{{ route('customer.payment-history') }}" class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('customer.payment-history') ? 'bg-indigo-800 text-white shadow-md shadow-indigo-900/40' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }}" wire:navigate>
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            Riwayat Pembayaran
        </a>
    @endif
</nav>
