<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Riwayat Pembayaran Cicilan') }}
            </h2>
            <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition" wire:navigate>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 text-gray-400 dark:text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Tanggal Pembayaran</th>
                        <th class="py-4 px-6">Metode</th>
                        <th class="py-4 px-6">Nominal</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Catatan Admin / Keterangan</th>
                        <th class="py-4 px-6 text-center">Bukti Transfer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($payments as $pmt)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition text-sm">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                {{ $pmt->payment_date->format('d F Y') }}
                            </td>
                            <td class="py-4 px-6 text-gray-600 dark:text-gray-400">
                                {{ $pmt->payment_method }}
                            </td>
                            <td class="py-4 px-6 font-extrabold text-gray-900 dark:text-white">
                                Rp {{ number_format($pmt->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                @if ($pmt->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30">Pending</span>
                                @elseif ($pmt->status === 'verified')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">Verified</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">Rejected</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 max-w-[250px] truncate">
                                @if ($pmt->status === 'rejected')
                                    <span class="text-xs text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-2 py-0.5 rounded-lg font-medium inline-block max-w-full truncate" title="{{ $pmt->admin_notes }}">
                                        Alasan: {{ $pmt->admin_notes ?: 'Ditolak oleh Admin' }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $pmt->admin_notes ?: '-' }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($pmt->proof_path)
                                    <button wire:click="openDetailModal({{ $pmt->id }})" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/30 dark:hover:bg-indigo-950/50 dark:text-indigo-400 text-xs font-semibold rounded-lg transition">
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">No File (Manual)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center text-gray-500 dark:text-gray-400">
                                Anda belum pernah mengunggah bukti pembayaran cicilan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Lightbox Modal (Alpine.js + Livewire Entangle) -->
    <div x-data="{ open: @entangle('isOpenDetail') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/75 backdrop-blur-xs transition-opacity" @click="open = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-150 dark:border-gray-800 p-6 z-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800 mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                        Bukti Transfer Pembayaran
                    </h3>
                    <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if ($detailPayment)
                    <div class="space-y-4">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden flex items-center justify-center min-h-[200px] border border-gray-200 dark:border-gray-700">
                            <img src="{{ Storage::url($detailPayment->proof_path) }}" class="object-contain max-h-[350px] w-full" alt="Bukti Transfer">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-xs bg-gray-50 dark:bg-gray-800/40 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                            <div>
                                <span class="text-gray-400">Tanggal Upload</span>
                                <div class="font-bold text-gray-950 dark:text-white mt-0.5">{{ $detailPayment->created_at->format('d M Y, H:i') }} WIB</div>
                            </div>
                            <div>
                                <span class="text-gray-400">Nominal Transfer</span>
                                <div class="font-extrabold text-indigo-600 dark:text-indigo-400 mt-0.5">Rp {{ number_format($detailPayment->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ Storage::url($detailPayment->proof_path) }}" download class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Unduh Gambar
                            </a>
                            <button type="button" wire:click="closeDetailModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
