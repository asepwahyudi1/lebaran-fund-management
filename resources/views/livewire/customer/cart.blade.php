<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 text-rose-600 dark:text-rose-400 font-semibold rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (empty($cartItems))
                <!-- Empty Cart Layout -->
                <div class="bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-3xl p-12 text-center text-gray-500 dark:text-gray-400 max-w-2xl mx-auto shadow-sm">
                    <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md shadow-indigo-500/5">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.116 60.116 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Keranjang Belanja Kosong</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8">Anda belum menambahkan paket Lebaran apa pun ke dalam keranjang belanja Anda.</p>
                    <a href="{{ route('customer.catalog') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition duration-200 inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Lihat Katalog Paket
                    </a>
                </div>
            @else
                <!-- Active Cart Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    <!-- Left: Cart Items List (2 columns) -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach ($cartItems as $item)
                            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                                    <!-- Image -->
                                    <div class="w-24 h-20 rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-850 flex-none border border-gray-150 dark:border-gray-800">
                                        <img src="{{ $item['package']->imageUrl() }}" alt="{{ $item['package']->name }}" class="w-full h-full object-cover">
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-1 text-center sm:text-left min-w-0">
                                        <h4 class="font-black text-base text-gray-900 dark:text-white truncate">
                                            {{ $item['package']->name }}
                                        </h4>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold mt-1">
                                            Durasi: {{ $item['package']->duration_weeks }} Minggu
                                        </p>
                                        <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                                            Rp {{ number_format($item['weekly_installment'], 0, ',', '.') }} / minggu
                                        </p>
                                    </div>

                                    <!-- Action and Qty Controls -->
                                    <div class="flex sm:flex-col items-center justify-between sm:justify-start gap-4 sm:items-end w-full sm:w-auto">
                                        <!-- Delete Button -->
                                        <button type="button" wire:click="removeItem({{ $item['package']->id }})" class="p-2 text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl transition sm:order-first" title="Hapus Paket">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>

                                        <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-xl p-1 shrink-0">
                                            <button type="button" wire:click="updateQuantity({{ $item['package']->id }}, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-white dark:hover:bg-gray-700 rounded-lg transition font-bold text-sm">
                                                -
                                            </button>
                                            <span class="w-8 text-center text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $item['quantity'] }}
                                            </span>
                                            <button type="button" wire:click="updateQuantity({{ $item['package']->id }}, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-white dark:hover:bg-gray-700 rounded-lg transition font-bold text-sm">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Right: Summary Card (1 column) -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Ringkasan Pembayaran</h3>

                            <div class="space-y-4">
                                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>Total Unit Paket</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ array_sum(session('cart', [])) }} unit
                                    </span>
                                </div>

                                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-end">
                                    <div class="text-sm font-bold text-gray-800 dark:text-gray-200">Total Cicilan Mingguan</div>
                                    <div class="text-right">
                                        <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($totalWeeklyInstallment, 0, ',', '.') }}
                                        </div>
                                        <span class="text-[10px] text-gray-400 block mt-0.5">diakumulasikan tiap minggu</span>
                                    </div>
                                </div>

                                <button type="button" wire:click="checkout" class="w-full mt-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-850 text-white text-center font-bold text-sm rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Lanjut ke Checkout
                                </button>
                            </div>
                        </div>

                        <!-- Info guidelines -->
                        <div class="bg-gray-50/50 dark:bg-gray-800/20 border border-gray-150 dark:border-gray-800 rounded-3xl p-5 text-xs text-gray-500 dark:text-gray-400 space-y-3">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">ℹ️ Ketentuan Program Tabungan</h4>
                            <p class="leading-relaxed">Dengan melakukan checkout, Anda mendaftarkan komitmen setoran tabungan mingguan baru. Setoran akan ditagih berkala mulai minggu depan.</p>
                            <p class="leading-relaxed">Setelah checkout berhasil, Anda dapat mengunggah bukti transfer setoran pertama Anda melalui halaman Unggah Bukti.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Checkout Confirmation Modal -->
    <div x-data="{ showModal: @entangle('isOpenCheckoutModal') }"
         x-show="showModal"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 dark:bg-gray-950/80 backdrop-blur-xs"
         style="display: none;"
         x-transition>
        
        <div class="bg-white dark:bg-gray-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-150 dark:border-gray-800 relative">
            <!-- Close -->
            <button type="button" @click="showModal = false" class="absolute top-4 right-4 p-1.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Header -->
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h3 class="font-black text-xl text-gray-900 dark:text-white">Konfirmasi Checkout</h3>
                <p class="text-xs text-gray-400 mt-1">Harap verifikasi komitmen tabungan Anda</p>
            </div>

            <!-- Summary list -->
            <div class="bg-gray-50 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 rounded-2xl p-4 mb-6 space-y-3.5">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Item Checkout:</div>
                <div class="space-y-2 max-h-28 overflow-y-auto pr-1">
                    @foreach ($cartItems as $item)
                        <div class="flex justify-between items-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                            <span class="truncate pr-4">{{ $item['package']->name }}</span>
                            <span class="shrink-0 text-gray-500 text-xs">{{ $item['quantity'] }} unit</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="pt-3 border-t border-gray-100 dark:border-gray-850 flex justify-between items-end">
                    <span class="text-xs text-gray-400 uppercase tracking-wider font-bold">Total Cicilan</span>
                    <span class="text-base font-black text-indigo-600 dark:text-indigo-400">
                        Rp {{ number_format($totalWeeklyInstallment, 0, ',', '.') }} / mgg
                    </span>
                </div>
            </div>

            <!-- Transfer details -->
            <div class="p-4 border border-indigo-100 dark:border-indigo-900/30 bg-indigo-50/20 dark:bg-indigo-950/10 rounded-2xl mb-6 space-y-2.5 text-xs text-indigo-900 dark:text-indigo-300">
                <p class="font-bold text-sm text-indigo-950 dark:text-indigo-200">💳 Instruksi Transfer Setoran Awal</p>
                <p>Transfer cicilan pertama Anda ke rekening pengelola berikut:</p>
                <div class="font-mono bg-white dark:bg-gray-850 p-2.5 rounded-xl border border-indigo-100 dark:border-indigo-900/20 space-y-1">
                    <div>Bank: <strong class="text-gray-900 dark:text-white">BANK MANDIRI</strong></div>
                    <div>No. Rek: <strong class="text-gray-900 dark:text-white select-all">123-00-9876543-2</strong></div>
                    <div>Nama: <strong class="text-gray-900 dark:text-white">UMKM SUMBER SARI</strong></div>
                </div>
                <p class="text-[10px] leading-relaxed italic text-gray-400 dark:text-gray-500">Unggah bukti transfer Anda di menu "Unggah Bukti" setelah menyelesaikan transfer agar diverifikasi admin.</p>
            </div>

            <!-- Action -->
            <div class="flex items-center gap-3">
                <button type="button" @click="showModal = false" class="flex-1 py-3 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    Batal
                </button>
                <button type="button" wire:click="confirmCheckout" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-2xl shadow-md transition">
                    Checkout Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
