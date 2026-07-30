<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Pembayaran & Verifikasi') }}
            </h2>
            <button onclick="Livewire.dispatch('open-manual-modal-event')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Input Manual (Tunai)
            </button>
        </div>
    </x-slot>

    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-2xl text-emerald-800 dark:text-emerald-400 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Search and Filters Panel -->
    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 mb-6 shadow-xs flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="searchCustomer" type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white pl-10 pr-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs" placeholder="Cari nama pelanggan...">
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="filterStatus" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Verifikasi</option>
                <option value="verified">Verified (Disetujui)</option>
                <option value="rejected">Rejected (Ditolak)</option>
            </select>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 text-gray-400 dark:text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Pelanggan / Paket</th>
                        <th class="py-4 px-6">Tanggal & Metode</th>
                        <th class="py-4 px-6">Nominal</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($paymentsList as $pmt)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition">
                            <td class="py-4 px-6">
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $pmt->user->name ?? 'Deleted User' }}</h4>
                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold">{{ $pmt->user->package->name ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $pmt->payment_date->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $pmt->payment_method }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-sm text-gray-900 dark:text-white">Rp {{ number_format($pmt->amount, 0, ',', '.') }}</span>
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
                            <td class="py-4 px-6 text-center">
                                @if ($pmt->status === 'pending')
                                    <button wire:click="openVerifyModal({{ $pmt->id }})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                        Periksa Bukti
                                    </button>
                                @else
                                    <button wire:click="openVerifyModal({{ $pmt->id }})" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition">
                                        Detail
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada transaksi pembayaran yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($paymentsList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $paymentsList->links() }}
            </div>
        @endif
    </div>

    <!-- Record Manual Payment Modal (Alpine.js + Livewire Entangle) -->
    <div x-data="{ open: @entangle('isOpenManual') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="open = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-150 dark:border-gray-800 p-6 z-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Title -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Catat Pembayaran Manual (Tunai / Cash)
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="saveManual" class="space-y-4">
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilih Pelanggan</label>
                        <select wire:model="user_id" id="user_id" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->package->name ?? 'Belum memilih paket' }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nominal Pembayaran (Rp)</label>
                        <input wire:model="amount" type="number" id="amount" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 100000" required>
                        @error('amount') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="payment_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                            <input wire:model="payment_date" type="date" id="payment_date" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('payment_date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="payment_method" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Metode</label>
                            <select wire:model="payment_method" id="payment_method" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Tunai">Tunai (Cash)</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                            @error('payment_method') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800 mt-6">
                        <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md transition">
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Verification Detail Modal (Alpine.js + Livewire Entangle) -->
    <div x-data="{ open: @entangle('isOpenVerify') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="open = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-150 dark:border-gray-800 p-6 z-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Verifikasi Transaksi Pembayaran
                    </h3>
                    <button wire:click="closeVerifyModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if ($verifyingPayment)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Panel: Transaction info -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                <h4 class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Pelanggan</h4>
                                <p class="font-bold text-sm text-gray-900 dark:text-white mt-1">{{ $verifyingPayment->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $verifyingPayment->user->phone_number }}</p>
                                
                                <h4 class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold mt-4">Paket Pilihan</h4>
                                <p class="font-bold text-sm text-indigo-600 dark:text-indigo-400 mt-1">{{ $verifyingPayment->user->package->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Harga: Rp {{ number_format($verifyingPayment->user->package->price ?? 0, 0, ',', '.') }}</p>
                            </div>

                            <div class="bg-indigo-50/30 dark:bg-indigo-950/10 p-4 rounded-2xl border border-indigo-100/50 dark:border-indigo-900/20">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Tanggal Pembayaran</h4>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white mt-0.5">{{ $verifyingPayment->payment_date->format('d F Y') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Metode Transfer</h4>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white mt-0.5">{{ $verifyingPayment->payment_method }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h4 class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Nominal Pembayaran</h4>
                                    <p class="font-extrabold text-lg text-indigo-600 dark:text-indigo-400 mt-0.5">Rp {{ number_format($verifyingPayment->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div>
                                <label for="admin_notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Catatan Admin</label>
                                <textarea wire:model="admin_notes" id="admin_notes" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Catatan bukti transfer, nomor rekening pengirim, atau alasan penolakan..."></textarea>
                                @error('admin_notes') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Right Panel: Receipt Preview -->
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold mb-2">Preview Bukti Transfer</span>
                            @if ($verifyingPayment->proof_path)
                                <div class="relative bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 flex items-center justify-center min-h-[250px] max-h-[300px]">
                                    <img src="{{ Storage::url($verifyingPayment->proof_path) }}" class="object-contain max-h-[300px] w-full" alt="Bukti Transfer">
                                    <a href="{{ Storage::url($verifyingPayment->proof_path) }}" target="_blank" class="absolute bottom-3 right-3 px-3 py-1.5 bg-black/60 hover:bg-black/80 text-white rounded-lg text-xs font-semibold transition backdrop-blur-xs flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                        Buka Gambar
                                    </a>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-dashed border-gray-300 dark:border-gray-800 flex flex-col items-center justify-center py-12 text-center text-gray-400 h-full min-h-[250px]">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="text-xs">Tidak ada file bukti transfer (Pembayaran Manual/Tunai)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Modal Footer actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-800 mt-6 flex-wrap gap-3">
                    <div>
                        @if ($verifyingPayment && $verifyingPayment->status !== 'pending')
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Status: <strong class="uppercase text-indigo-600">{{ $verifyingPayment->status }}</strong>
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="closeVerifyModal" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Batal
                        </button>
                        @if ($verifyingPayment && $verifyingPayment->status === 'pending')
                            <button type="button" wire:click="rejectPayment" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-sm font-semibold rounded-xl shadow-md transition">
                                Tolak Pembayaran
                            </button>
                            <button type="button" wire:click="verifyPayment" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold rounded-xl shadow-md transition">
                                Setujui Pembayaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
