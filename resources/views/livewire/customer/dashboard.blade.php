<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Portal Pelanggan') }}
            </h2>
            <a href="{{ route('customer.upload-payment') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center gap-2" wire:navigate>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Unggah Bukti Transfer
            </a>
        </div>
    </x-slot>

    <!-- Welcome Widget -->
    <div class="bg-gradient-to-r from-indigo-600 to-violet-700 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-indigo-500/10 mb-8 relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 opacity-10">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
            </svg>
        </div>
        <span class="text-xs font-bold bg-white/20 text-white px-3 py-1 rounded-full uppercase tracking-wider">Selamat Datang</span>
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-3 mb-2">Halo, {{ $customer->name }}!</h1>
        <p class="text-sm text-indigo-100 max-w-xl leading-relaxed">
            Terima kasih telah menabung di UMKM Sumber Sari. Anda terdaftar pada **{{ $customer->packages->isNotEmpty() ? $customer->packages->pluck('name')->implode(', ') : 'Belum Memilih Paket' }}**. 
            Pantau terus progres cicilan Anda di bawah ini menuju Lebaran yang penuh berkah.
        </p>
    </div>

    @if ($customer->packages->isNotEmpty())
        @php
            $calendar = $customer->getInstallmentCalendar();
            $currentWeekIdx = $customer->current_week - 1;
            $currentWeeklyAmt = isset($calendar[$currentWeekIdx]) ? $calendar[$currentWeekIdx]['expected_amount'] : ($customer->packages->sum(fn($p) => $p->price / ($p->pivot->duration_weeks ?: 40)));
            $totalCalendarWeeks = count($calendar);
        @endphp
        <!-- Dashboard Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Progress and Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Status Cicilan Mingguan Card -->
                <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm {{ $customer->weekly_status === 'Lancar' ? 'border-l-4 border-l-emerald-500' : 'border-l-4 border-l-rose-500' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">Status Cicilan Mingguan</h3>
                        @if ($customer->weekly_status === 'Lancar')
                            <span class="px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl dark:bg-emerald-950/20 dark:text-emerald-400">Lancar (Up to Date)</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100 rounded-xl dark:bg-rose-950/20 dark:text-rose-400">Ada Tunggakan</span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-800/40 rounded-xl p-4 border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] text-gray-400 uppercase font-semibold">Cicilan Mingguan</span>
                            <div class="font-extrabold text-lg text-gray-900 dark:text-white mt-1">Rp {{ number_format($currentWeeklyAmt, 0, ',', '.') }}</div>
                            <span class="text-[10px] text-gray-500">per minggu</span>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800/40 rounded-xl p-4 border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] text-gray-400 uppercase font-semibold">Minggu Berjalan</span>
                            <div class="font-extrabold text-lg text-gray-900 dark:text-white mt-1">Minggu ke-{{ $customer->current_week }}</div>
                            <span class="text-[10px] text-gray-500">dari {{ $totalCalendarWeeks }} minggu</span>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800/40 rounded-xl p-4 border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] text-gray-400 uppercase font-semibold">Tenggat Minggu Ini</span>
                            <div class="font-extrabold text-lg text-gray-900 dark:text-white mt-1">
                                {{ $customer->current_week_deadline ? $customer->current_week_deadline->format('d M Y') : '-' }}
                            </div>
                            <span class="text-[10px] text-gray-500">Batas setoran mingguan</span>
                        </div>
                    </div>

                    @if ($customer->weekly_status === 'Menunggak')
                        <div class="mt-6 p-4 rounded-xl bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h4 class="text-sm font-bold text-rose-700 dark:text-rose-450">Anda Memiliki Tunggakan Tabungan</h4>
                                <p class="text-xs text-rose-600 dark:text-rose-400 mt-0.5">Segera lakukan transfer untuk menyamakan kewajiban tabungan minggu ini.</p>
                            </div>
                            <div class="text-right sm:text-left">
                                <span class="text-[10px] text-rose-500 uppercase font-semibold block">Total Tunggakan</span>
                                <span class="font-black text-lg text-rose-700 dark:text-rose-400">Rp {{ number_format($customer->arrears_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Kalender Cicilan Card -->
                <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Kalender Cicilan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $customer->duration_weeks }} minggu cicilan</p>
                        </div>
                        
                        <!-- Legenda -->
                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-[11px] font-semibold text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded bg-gray-100 dark:bg-gray-800 border border-gray-250 dark:border-gray-700"></div>
                                <span>Belum Bayar</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded bg-amber-500 shadow-xs shadow-amber-500/20"></div>
                                <span>Bukti Dikirim</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded bg-emerald-500 shadow-xs shadow-emerald-500/20"></div>
                                <span>Diverifikasi</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded bg-rose-500 shadow-xs shadow-rose-500/20"></div>
                                <span>Terlambat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Weeks Grid -->
                    <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-3">
                        @foreach ($customer->getInstallmentCalendar() as $week)
                            @php
                                $statusClass = '';
                                if ($week['status'] === 'verified') {
                                    $statusClass = 'bg-emerald-500 text-white shadow-md shadow-emerald-500/10 border-emerald-600';
                                } elseif ($week['status'] === 'pending') {
                                    $statusClass = 'bg-amber-500 text-white shadow-md shadow-amber-500/10 border-amber-600';
                                } elseif ($week['status'] === 'late') {
                                    $statusClass = 'bg-rose-500 text-white shadow-md shadow-rose-500/10 border-rose-600';
                                } else {
                                    $statusClass = 'bg-gray-50 dark:bg-gray-850/40 text-gray-400 dark:text-gray-500 border-gray-150 dark:border-gray-800';
                                }
                            @endphp
                            <div class="aspect-square flex flex-col items-center justify-center rounded-xl border font-bold text-xs transition duration-200 cursor-help {{ $statusClass }}" 
                                 title="Minggu {{ $week['number'] }} • Tenggat: {{ $week['deadline'] ? $week['deadline']->format('d M Y') : '-' }} • Status: {{ ucfirst($week['status']) }} • Dibayar: Rp {{ number_format($week['paid_amount'] + $week['pending_amount'], 0, ',', '.') }} @if($week['pending_amount'] > 0)(Rp {{ number_format($week['paid_amount'], 0, ',', '.') }} Lunas, Rp {{ number_format($week['pending_amount'], 0, ',', '.') }} Pending)@endif • Target: Rp {{ number_format($week['expected_amount'], 0, ',', '.') }}">
                                <span>{{ $week['number'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Progress Card -->
                <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-6">Progres Cicilan Paket</h3>
                    
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <!-- Progress Circle / Horizontal -->
                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-center text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">
                                <span>Status Tabungan</span>
                                <span class="text-indigo-600 dark:text-indigo-400">{{ $progressPercent }}% Terpenuhi</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full h-4 overflow-hidden mb-4">
                                <div class="bg-gradient-to-r from-indigo-500 to-violet-600 h-4 rounded-full transition-all duration-700" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Berhasil mengumpulkan <strong class="text-gray-800 dark:text-gray-200">Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong> dari total target <strong class="text-gray-800 dark:text-gray-200">Rp {{ number_format($customer->packages->sum('price'), 0, ',', '.') }}</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- Detail metrics grid -->
                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <div class="bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-100/50 dark:border-emerald-900/20 rounded-xl p-4 text-center">
                            <span class="text-xs text-emerald-600 uppercase tracking-wider font-semibold">Total Sudah Dibayar</span>
                            <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                        </div>

                        <div class="bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/20 rounded-xl p-4 text-center">
                            <span class="text-xs text-rose-600 uppercase tracking-wider font-semibold">Sisa Cicilan</span>
                            <div class="text-xl font-black text-rose-600 dark:text-rose-400 mt-1">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Package Info Card -->
                <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Informasi Paket Lebaran</h3>
                    <div class="space-y-4">
                        @foreach ($customer->packages as $pkg)
                            <div class="flex flex-col sm:flex-row items-start gap-4 pb-4 last:pb-0 border-b last:border-0 border-gray-100 dark:border-gray-800">
                                <div class="w-full sm:w-24 h-16 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-150 dark:border-gray-800 shrink-0">
                                    <img src="{{ $pkg->imageUrl() }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-base text-gray-900 dark:text-white">{{ $pkg->name }}</h4>
                                    <p class="text-xs font-semibold text-gray-450 dark:text-gray-400">Setoran: Rp {{ number_format($pkg->price / ($pkg->duration_weeks ?: 40), 0, ',', '.') }} / mgg</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                                        {{ $pkg->description ?: 'Tidak ada rincian tambahan untuk paket ini.' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Col: Recent Activity & Actions -->
            <div class="space-y-8">
                <!-- Quick bank info -->
                <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white mb-4">Informasi Transfer Bank</h3>
                    <div class="space-y-4">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <span class="text-xs text-gray-400">Nama Bank</span>
                            <div class="font-bold text-sm text-gray-800 dark:text-gray-200 mt-0.5">BANK MANDIRI</div>
                        </div>
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <span class="text-xs text-gray-400">Nomor Rekening</span>
                            <div class="font-bold text-sm text-gray-800 dark:text-gray-200 mt-0.5 flex items-center justify-between">
                                <span>123-00-9876543-2</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400">Atas Nama</span>
                            <div class="font-bold text-sm text-gray-800 dark:text-gray-200 mt-0.5">UMKM SUMBER SARI</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">Aktivitas Terakhir</h3>
                        <a href="{{ route('customer.payment-history') }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-semibold hover:underline" wire:navigate>
                            Semua &rarr;
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse ($recentPayments as $pmt)
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-sm text-gray-900 dark:text-white">Rp {{ number_format($pmt->amount, 0, ',', '.') }}</div>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">{{ $pmt->payment_date->format('d M Y') }}</span>
                                </div>
                                <div>
                                    @if ($pmt->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30">Pending</span>
                                    @elseif ($pmt->status === 'verified')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">Verified</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-rose-50 text-rose-600 border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">Rejected</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-xs text-gray-500 dark:text-gray-400">
                                Belum ada transaksi pembayaran.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-3xl p-12 text-center text-gray-500 dark:text-gray-400">
            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Akun Anda Belum Memiliki Paket Aktif</h3>
            <p class="text-sm max-w-md mx-auto mb-6">Silakan pilih salah satu paket Lebaran yang tersedia di katalog kami untuk memulai tabungan Anda.</p>
            <a href="{{ route('customer.catalog') }}" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-md transition duration-200 inline-flex items-center gap-2" wire:navigate>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Lihat Katalog Paket
            </a>
        </div>
    @endif
</div>
