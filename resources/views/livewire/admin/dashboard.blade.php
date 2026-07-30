<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="text-xs text-gray-500 font-medium dark:text-gray-400">
                Data diperbarui: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </span>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Pendapatan -->
        <div class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 opacity-15">
                <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
                </svg>
            </div>
            <p class="text-indigo-100 text-sm font-semibold uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-extrabold tracking-tight">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-xs text-indigo-100 font-medium">
                <span class="bg-white/20 px-2 py-0.5 rounded-full mr-2">Verified</span>
                <span>Dari transaksi sukses</span>
            </div>
        </div>

        <!-- Card 2: Total Pelanggan -->
        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 dark:text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Pelanggan</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="0" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $totalCustomers }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 font-medium">
                <span class="text-emerald-500 font-semibold">+{{ $newCustomersCount }} baru</span> dalam 7 hari terakhir
            </p>
        </div>

        <!-- Card 3: Total Paket -->
        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 dark:text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Paket</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $totalPackages }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 font-medium">Tersedia untuk pelanggan</p>
        </div>

        <!-- Card 4: Menunggu Verifikasi -->
        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 dark:text-gray-500 text-sm font-semibold uppercase tracking-wider font-semibold">Menunggu Verifikasi</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $pendingPaymentsCount }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 font-medium">
                Ada <span class="text-amber-500 font-semibold">{{ $todayPaymentsCount }} transfer</span> hari ini
            </p>
        </div>
    </div>

    <!-- Quick Action / Alert Section -->
    @if ($pendingPaymentsCount > 0)
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 rounded-2xl p-4 mb-8 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <div>
                    <h4 class="font-bold text-amber-800 dark:text-amber-400 text-sm">Ada {{ $pendingPaymentsCount }} Pembayaran Baru Menunggu Verifikasi</h4>
                    <p class="text-xs text-amber-700 dark:text-amber-500">Mohon segera verifikasi bukti transfer untuk memperbarui progres cicilan pelanggan.</p>
                </div>
            </div>
            <a href="{{ route('admin.payments') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-sm transition" wire:navigate>
                Verifikasi Sekarang
            </a>
        </div>
    @endif

    <!-- Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left: Recent Payments -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Pembayaran Terbaru</h3>
                <a href="{{ route('admin.payments') }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-semibold hover:underline" wire:navigate>
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-4">
                @forelse ($recentPayments as $payment)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 transition hover:bg-gray-100/50 dark:hover:bg-gray-800/80">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                {{ substr($payment->user->name ?? 'U', 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $payment->user->name ?? 'Guest User' }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->payment_date->format('d M Y') }} • {{ $payment->payment_method }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-sm text-gray-900 dark:text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            <div class="mt-1">
                                @if ($payment->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30">Pending</span>
                                @elseif ($payment->status === 'verified')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">Verified</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-600 border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">Rejected</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                        Belum ada riwayat pembayaran.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Recent Customers -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Pelanggan Baru Terdaftar</h3>
                <a href="{{ route('admin.customers') }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-semibold hover:underline" wire:navigate>
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-4">
                @forelse ($recentCustomers as $customer)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 transition hover:bg-gray-100/50 dark:hover:bg-gray-800/80">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/30 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold text-sm">
                                {{ substr($customer->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $customer->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone_number ?? '-' }} • {{ $customer->address ? Str::limit($customer->address, 30) : '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 text-xs font-semibold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400 rounded-lg">
                                {{ $customer->package->name ?? 'Tanpa Paket' }}
                            </span>
                            <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                                Terdaftar {{ $customer->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                        Belum ada pelanggan terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
