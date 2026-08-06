<div>
    <x-slot name="header">
        <div class="flex items-center justify-between no-print">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Rekap & Laporan Dana Paket') }}
            </h2>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24 3h11.04l-.24-3m-10.56 0a3 3 0 115.34-1.78l.42 2.62a3 3 0 11-5.34 1.78zm5.34-1.78a3 3 0 115.34 1.78l-.42-2.62a3 3 0 11-5.34-1.78zm-5.34 1.78h10.68M19.5 9.75v.008H21v-.008h-1.5zm-15 0v.008H6v-.008H4.5zM12 3v13.5m0-13.5L8.25 6.75M12 3l3.75 3.75" />
                </svg>
                Cetak Laporan
            </button>
        </div>
    </x-slot>

    <!-- Printable Header (Only visible on print) -->
    <div class="only-print mb-8 border-b-2 border-gray-900 pb-4 text-center">
        <h1 class="text-2xl font-bold uppercase tracking-wide">UMKM SUMBER SARI</h1>
        <p class="text-sm text-gray-600">Laporan Pengelolaan Dana Paket Kebutuhan Lebaran</p>
        <p class="text-xs text-gray-500 mt-1">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB • Dicetak oleh: {{ auth()->user()->name }}</p>
    </div>

    <!-- Summary metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Total Pelanggan Terdaftar</span>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalCustomers }} Pelanggan</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Total Dana Terkumpul (Verified)</span>
                <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($totalVerifiedIncome, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Table 1: Package Report -->
    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center no-print">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Rekapitulasi Kolektif per Paket</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50 dark:bg-gray-800/20 text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Nama Paket</th>
                        <th class="py-4 px-6 text-center">Jumlah Pelanggan</th>
                        <th class="py-4 px-6">Harga Paket</th>
                        <th class="py-4 px-6">Target Dana (Potensi)</th>
                        <th class="py-4 px-6">Dana Masuk (Verified)</th>
                        <th class="py-4 px-6">Sisa Piutang</th>
                        <th class="py-4 px-6 text-center">% Progres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($packagesReport as $rep)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition text-sm">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">{{ $rep['name'] }}</td>
                            <td class="py-4 px-6 text-center font-semibold text-gray-800 dark:text-gray-300">{{ $rep['customers_count'] }}</td>
                            <td class="py-4 px-6 text-gray-850 dark:text-gray-300">Rp {{ number_format($rep['price'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 font-semibold text-gray-850 dark:text-gray-300">Rp {{ number_format($rep['potential_amount'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($rep['verified_amount'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 font-semibold text-rose-600 dark:text-rose-400">Rp {{ number_format($rep['remaining_amount'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-center font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $rep['progress_percent'] }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data paket untuk laporan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table 2: Recent Transactions list -->
    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden page-break-before">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center no-print">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Rincian 20 Transaksi Terakhir (Verified)</h3>
        </div>
        <div class="only-print p-6 border-b border-gray-900 pb-2 mb-4">
            <h3 class="font-bold text-lg text-gray-900">Rincian Transaksi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/50 dark:bg-gray-800/20 text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Paket Terpilih</th>
                        <th class="py-4 px-6">Tanggal Bayar</th>
                        <th class="py-4 px-6">Metode</th>
                        <th class="py-4 px-6">Nominal</th>
                        <th class="py-4 px-6">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($recentTransactions as $tx)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">{{ $tx->user->name ?? 'User Terhapus' }}</td>
                            <td class="py-4 px-6 text-gray-600 dark:text-gray-400">{{ $tx->user ? $tx->user->packages->pluck('name')->implode(', ') : '-' }}</td>
                            <td class="py-4 px-6 text-gray-700 dark:text-gray-300">{{ $tx->payment_date->format('d M Y') }}</td>
                            <td class="py-4 px-6 text-gray-700 dark:text-gray-300">{{ $tx->payment_method }}</td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-xs text-gray-500 dark:text-gray-400 italic">{{ $tx->admin_notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-gray-500 dark:text-gray-400">
                                Belum ada transaksi pembayaran sukses.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Custom styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .only-print {
                display: block !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-family: serif;
            }
            .page-break-before {
                page-break-before: always;
            }
            /* Reset container paddings and shadows for print */
            main {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .shadow-xs, .shadow-sm, .shadow-md, .shadow-lg {
                box-shadow: none !important;
            }
            .border {
                border-color: #000 !important;
            }
            .border-b {
                border-bottom-color: #000 !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid #ddd !important;
                padding: 8px !important;
            }
        }
        .only-print {
            display: none;
        }
    </style>
</div>
