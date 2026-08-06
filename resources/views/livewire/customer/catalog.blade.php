<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Katalog Paket Lebaran') }}
        </h2>
    </x-slot>

    <!-- Header Description -->
    <div class="mb-8 bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Pilih Paket Tabungan Anda</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Berikut adalah daftar paket tabungan Lebaran yang tersedia. Silakan pilih paket untuk ditambahkan ke keranjang belanja Anda.
            <span class="font-semibold text-indigo-650 dark:text-indigo-400">Anda dapat memilih lebih dari satu paket dan menentukan jumlah unitnya saat checkout.</span>
        </p>
    </div>

    <!-- Session Success Toast -->
    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold rounded-2xl flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <a href="{{ route('customer.cart') }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition duration-150 flex items-center gap-1.5 shrink-0" wire:navigate>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.116 60.116 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                Lihat Keranjang
            </a>
        </div>
    @endif

    <!-- Catalog Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($packages as $pkg)
            @php
                $enrolledCount = $followedPackages[$pkg->id] ?? 0;
            @endphp
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full relative">
                <!-- Image Header -->
                <div class="h-48 w-full overflow-hidden bg-gray-100 dark:bg-gray-800 relative">
                    <img src="{{ $pkg->imageUrl() }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="px-3.5 py-1.5 bg-indigo-600/90 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-xs">
                            Rp {{ number_format($pkg->weekly_installment, 0, ',', '.') }} / minggu
                        </span>
                    </div>
                    @if ($enrolledCount > 0)
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 bg-emerald-600/90 text-white text-[10px] font-black uppercase rounded-full shadow-lg backdrop-blur-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Diikuti ({{ $enrolledCount }}x)
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Package Details -->
                <div class="p-6 flex flex-col flex-1">
                    <h4 class="font-black text-lg text-gray-900 dark:text-white mb-2 leading-tight">
                        {{ $pkg->name }}
                    </h4>
                    
                    <div class="flex items-center gap-2 mb-4 text-xs font-bold text-gray-400">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Jangka Waktu: {{ $pkg->duration_weeks }} Minggu</span>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6 flex-1">
                        {{ $pkg->description ?: 'Rincian detail mengenai paket tabungan Lebaran ini dapat ditanyakan langsung kepada pengelola.' }}
                    </p>

                    <!-- Action Button -->
                    @if ($enrolledCount > 0)
                        <button wire:click="addToCart({{ $pkg->id }})" class="w-full py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 dark:hover:bg-emerald-900/30 text-center font-bold text-sm rounded-2xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm flex items-center justify-center gap-2 transition duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Ikuti Lagi (Tambah Unit)
                        </button>
                    @else
                        <button wire:click="addToCart({{ $pkg->id }})" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold text-sm rounded-2xl shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 transition duration-200">
                            Pilih Paket
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-3xl p-12 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-16 h-16 text-gray-450 dark:text-gray-650 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum Ada Paket Aktif</h3>
                <p class="text-sm max-w-md mx-auto">Saat ini belum ada paket Lebaran aktif yang dapat Anda pilih.</p>
            </div>
        @endforelse
    </div>
</div>
