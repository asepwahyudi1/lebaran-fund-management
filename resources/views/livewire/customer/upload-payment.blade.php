<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Unggah Bukti Transfer') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <!-- Notifications -->
                @if (session()->has('message'))
                    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-2xl text-emerald-800 dark:text-emerald-400 text-sm font-medium flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-bold">{{ session('message') }}</p>
                            <a href="{{ route('customer.payment-history') }}" class="text-xs text-emerald-700 dark:text-emerald-300 hover:underline mt-1 block" wire:navigate>Lihat Riwayat Pembayaran &rarr;</a>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="save" class="space-y-6">
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nominal Pembayaran (Rp)</label>
                        <input wire:model="amount" type="number" id="amount" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Masukkan jumlah yang ditransfer..." required>
                        @error('amount') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="payment_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Transfer</label>
                            <input wire:model="payment_date" type="date" id="payment_date" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('payment_date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="payment_method" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                            <select wire:model="payment_method" id="payment_method" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                            @error('payment_method') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- File Upload Input with Drag & Drop styling -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Unggah Gambar Bukti Transfer</label>
                        
                        <div class="relative bg-gray-50 dark:bg-gray-800/40 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 transition p-6 flex flex-col items-center justify-center text-center">
                            <input type="file" wire:model="proof" id="proof" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" required
                                   onchange="if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) { alert('Ukuran berkas terlalu besar! Maksimal adalah 2MB.'); this.value = ''; event.preventDefault(); return false; }">
                            
                            <div class="space-y-2 pointer-events-none">
                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih file gambar atau seret ke sini</p>
                                <p class="text-xs text-gray-400">JPG, PNG, JPEG hingga 2MB</p>
                            </div>
                        </div>

                        <!-- Progress Bar for File Uploading -->
                        <div wire:loading wire:target="proof" class="w-full mt-2">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                <span>Mengunggah file...</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-800 h-1 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-1 animate-pulse" style="width: 100%"></div>
                            </div>
                        </div>

                        @error('proof') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image Preview Area -->
                    @if ($proof)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold mb-2 block">Pratinjau Gambar</span>
                            <div class="relative max-h-60 rounded-xl overflow-hidden flex items-center justify-center bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 p-2">
                                <img src="{{ $proof->temporaryUrl() }}" class="max-h-56 object-contain rounded-lg">
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition" wire:navigate>
                            Kembali
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:target="proof" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Col: Guide -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white mb-4">Informasi Rekening Bank</h3>
                <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl space-y-3">
                    <div>
                        <span class="text-xs text-gray-400">Nama Bank</span>
                        <div class="font-bold text-sm text-gray-800 dark:text-gray-200">BANK MANDIRI</div>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Nomor Rekening</span>
                        <div class="font-bold text-sm text-indigo-600 dark:text-indigo-400">123-00-9876543-2</div>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Atas Nama</span>
                        <div class="font-bold text-sm text-gray-800 dark:text-gray-200">UMKM SUMBER SARI</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white mb-4">Ketentuan Pembayaran</h3>
                <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-400 leading-relaxed list-disc pl-4">
                    <li>Nominal yang dicatat harus persis sama dengan yang tertera di bukti transfer.</li>
                    <li>Pastikan gambar bukti transfer terbaca dengan jelas (tidak buram).</li>
                    <li>Proses verifikasi oleh Admin membutuhkan waktu maksimal 1x24 jam.</li>
                    <li>Sisa cicilan akan otomatis berkurang setelah status pembayaran diverifikasi oleh Admin.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
