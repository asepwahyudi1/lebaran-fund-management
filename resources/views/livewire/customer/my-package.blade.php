<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Paket Saya') }}
        </h2>
    </x-slot>

    @if ($packages->isNotEmpty())
        <!-- Active Package Container -->
        <div class="space-y-8">
            
            <!-- Active Packages List -->
            <div class="space-y-4">
                @foreach ($packages as $pkg)
                    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-5 shadow-sm">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-150 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex-none">
                                <img src="{{ $pkg->imageUrl() }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-md dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-900/30 uppercase tracking-wider">
                                        Paket Aktif
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-505 font-medium">Terdaftar: {{ \Carbon\Carbon::parse($pkg->pivot->start_date)->format('d M Y') }}</span>
                                </div>
                                <h3 class="font-black text-lg text-gray-900 dark:text-white mt-1 mb-0.5">{{ $pkg->name }}</h3>
                                <p class="text-xs font-semibold text-gray-500">
                                    Setoran: <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($pkg->price / ($pkg->pivot->duration_weeks ?: 40), 0, ',', '.') }} / minggu</span> • Durasi: {{ $pkg->pivot->duration_weeks }} Minggu
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Savings Progress & Status -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left 2 columns: Progress and Calendar -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Week Progress Card -->
                    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
                        <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-6">Progres Tabungan Mingguan</h4>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm font-bold text-gray-800 dark:text-gray-200">
                                <span>Minggu Selesai</span>
                                <span class="text-indigo-600 dark:text-indigo-400">{{ $verifiedWeeks }} dari {{ $totalWeeks }} Minggu ({{ $progressPercent }}%)</span>
                            </div>
                            
                            <div class="w-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full h-4.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-violet-600 h-4.5 rounded-full transition-all duration-700" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                                <div class="p-3 bg-gray-50 dark:bg-gray-850/50 border border-gray-100 dark:border-gray-800 rounded-2xl text-center">
                                    <span class="text-[10px] text-gray-400 uppercase font-semibold">Total Tabungan Anda</span>
                                    <div class="text-lg font-black text-emerald-600 dark:text-emerald-400 mt-1">
                                        Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-gray-850/50 border border-gray-100 dark:border-gray-800 rounded-2xl text-center">
                                    <span class="text-[10px] text-gray-400 uppercase font-semibold">Sisa Kewajiban</span>
                                    <div class="text-lg font-black text-gray-800 dark:text-gray-200 mt-1">
                                        {{ $unpaidWeeks + $lateWeeks }} Minggu Lagi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Grid Card -->
                    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div>
                                <h4 class="font-bold text-lg text-gray-900 dark:text-white">Kalender Setoran Mingguan</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Detail status setoran tiap minggu berjalan</p>
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex flex-wrap gap-x-3 gap-y-1.5 text-[10px] font-bold text-gray-500 dark:text-gray-450">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded bg-gray-100 dark:bg-gray-800 border border-gray-250 dark:border-gray-700"></div>
                                    <span>Belum Bayar</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded bg-amber-500 shadow-xs shadow-amber-500/10"></div>
                                    <span>Pending</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded bg-emerald-500 shadow-xs shadow-emerald-500/10"></div>
                                    <span>Lunas</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded bg-rose-500 shadow-xs shadow-rose-500/10"></div>
                                    <span>Terlambat</span>
                                </div>
                            </div>
                        </div>

                        <!-- Grid -->
                        <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-3">
                            @foreach ($calendar as $week)
                                @php
                                    $statusClass = '';
                                    if ($week['status'] === 'verified') {
                                        $statusClass = 'bg-emerald-500 text-white border-emerald-600 shadow-md shadow-emerald-500/10';
                                    } elseif ($week['status'] === 'pending') {
                                        $statusClass = 'bg-amber-500 text-white border-amber-600 shadow-md shadow-amber-500/10';
                                    } elseif ($week['status'] === 'late') {
                                        $statusClass = 'bg-rose-500 text-white border-rose-600 shadow-md shadow-rose-500/10';
                                    } else {
                                        $statusClass = 'bg-gray-50 dark:bg-gray-850/40 text-gray-400 dark:text-gray-500 border-gray-150 dark:border-gray-800';
                                    }
                                @endphp
                                <div class="aspect-square flex flex-col items-center justify-center rounded-2xl border font-bold text-xs transition duration-200 cursor-help {{ $statusClass }}" 
                                     title="Minggu {{ $week['number'] }} • Tenggat: {{ $week['deadline'] ? $week['deadline']->format('d M Y') : '-' }} • Status: {{ ucfirst($week['status']) }} • Dibayar: Rp {{ number_format($week['paid_amount'] + $week['pending_amount'], 0, ',', '.') }} @if($week['pending_amount'] > 0)(Rp {{ number_format($week['paid_amount'], 0, ',', '.') }} Lunas, Rp {{ number_format($week['pending_amount'], 0, ',', '.') }} Pending)@endif • Target: Rp {{ number_format($week['expected_amount'], 0, ',', '.') }}">
                                    <span>{{ $week['number'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right 1 column: Weekly Status, Bank Transfer Info & Quick CTA -->
                <div class="space-y-8">
                    <!-- Status Card -->
                    @php
                        $currentWeekIdx = $customer->current_week - 1;
                        $currentWeeklyAmt = isset($calendar[$currentWeekIdx]) ? $calendar[$currentWeekIdx]['expected_amount'] : ($packages->sum(fn($p) => $p->price / ($p->pivot->duration_weeks ?: 40)));
                    @endphp
                    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-6 shadow-sm {{ $customer->weekly_status === 'Lancar' ? 'border-l-4 border-l-emerald-500' : 'border-l-4 border-l-rose-500' }}">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">Status Setoran</h4>
                            @if ($customer->weekly_status === 'Lancar')
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg dark:bg-emerald-950/20 dark:text-emerald-450 dark:border-emerald-900/30">Lancar</span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 rounded-lg dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/30">Menunggak</span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase font-semibold">Cicilan Selanjutnya</span>
                                <div class="font-black text-base text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($currentWeeklyAmt, 0, ',', '.') }}
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] text-gray-400 uppercase font-semibold">Minggu Berjalan</span>
                                <div class="font-bold text-sm text-gray-900 dark:text-white">Minggu ke-{{ $customer->current_week }}</div>
                            </div>
                            
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase font-semibold">Tenggat Pembayaran</span>
                                <div class="font-bold text-sm text-gray-900 dark:text-white">
                                    {{ $customer->current_week_deadline ? $customer->current_week_deadline->format('d M Y') : '-' }}
                                </div>
                            </div>

                            @if ($customer->weekly_status === 'Menunggak')
                                @php
                                    $arrearsWeeks = $currentWeeklyAmt > 0 ? round($customer->arrears_amount / $currentWeeklyAmt) : 0;
                                @endphp
                                <div class="pt-3 border-t border-rose-100 dark:border-rose-900/20">
                                    <span class="text-[10px] text-rose-500 uppercase font-semibold">Kekurangan Tabungan</span>
                                    <div class="font-black text-base text-rose-600 dark:text-rose-400">
                                        Rp {{ number_format($customer->arrears_amount, 0, ',', '.') }}
                                    </div>
                                    <span class="text-[9px] text-rose-450 block mt-0.5">
                                        Setara dengan {{ $arrearsWeeks }} minggu cicilan.
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bank Transfer Card -->
                    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-4">Informasi Rekening Transfer</h4>
                        
                        <div class="space-y-4 text-sm">
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                                <span class="text-xs text-gray-400">Nama Bank</span>
                                <div class="font-bold text-gray-800 dark:text-gray-200 mt-0.5">BANK MANDIRI</div>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                                <span class="text-xs text-gray-400">Nomor Rekening</span>
                                <div class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 font-mono">123-00-9876543-2</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400">Atas Nama</span>
                                <div class="font-bold text-gray-800 dark:text-gray-200 mt-0.5">UMKM SUMBER SARI</div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('customer.upload-payment') }}" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-850 text-white text-center font-bold text-sm rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition duration-200 flex items-center justify-center gap-2" wire:navigate>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" />
                                </svg>
                                Unggah Bukti Transfer
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        <!-- No active package layout -->
        <div class="bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-3xl p-12 text-center text-gray-500 dark:text-gray-400">
            <svg class="w-16 h-16 text-gray-405 dark:text-gray-655 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Anda Belum Memiliki Paket Aktif</h3>
            <p class="text-sm max-w-md mx-auto mb-6">Silakan pilih salah satu paket Lebaran melalui katalog kami terlebih dahulu untuk memulai program tabungan.</p>
            <a href="{{ route('customer.catalog') }}" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-md transition duration-200 inline-flex items-center gap-2" wire:navigate>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Lihat Katalog Paket
            </a>
        </div>
    @endif
</div>
