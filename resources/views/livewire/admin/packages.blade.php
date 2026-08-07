<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Paket Lebaran') }}
            </h2>
            <button onclick="Livewire.dispatch('open-package-modal-event')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Paket
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
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 rounded-2xl text-rose-800 dark:text-rose-400 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($packagesList as $pkg)
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between relative overflow-hidden group">
                <!-- Image Header -->
                <div class="h-44 w-full overflow-hidden relative bg-gray-100 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-800/50">
                    <img src="{{ $pkg->imageUrl() }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4">
                        <button wire:click="toggleStatus({{ $pkg->id }})" class="focus:outline-none transition transform active:scale-95">
                            @if ($pkg->status === 'active')
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/90 text-white backdrop-blur-xs shadow-md shadow-emerald-500/20">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-900/80 text-gray-200 backdrop-blur-xs shadow-md">Tidak Aktif</span>
                            @endif
                        </button>
                    </div>
                </div>

                <div class="p-6 flex flex-col justify-between flex-1">
                    <div>
                        <!-- Title & Price -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-0.5 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                            {{ $pkg->name }}
                        </h3>
                        <p class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400 mb-0.5">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold mb-4">
                            Cicilan: <span class="text-indigo-600 dark:text-indigo-400 font-bold">Rp {{ number_format($pkg->weekly_installment, 0, ',', '.') }}</span> / minggu ({{ $pkg->duration_weeks }} minggu)
                        </p>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-6 leading-relaxed">
                            {{ $pkg->description ?: 'Tidak ada deskripsi paket.' }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-100 dark:border-gray-800 pt-4 mt-auto flex items-center justify-end gap-2">
                        <button wire:click="edit({{ $pkg->id }})" class="p-2 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition" title="Edit Paket">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>
                        <button onclick="confirm('Apakah Anda yakin ingin menghapus paket ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $pkg->id }})" class="p-2 text-gray-600 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition" title="Hapus Paket">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-900 border border-dashed border-gray-300 dark:border-gray-800 rounded-2xl p-12 text-center text-gray-500 dark:text-gray-400">
                Belum ada paket dibuat. Klik tombol di kanan atas untuk membuat paket baru.
            </div>
        @endforelse
    </div>

    <!-- Edit/Add Modal (Alpine.js + Livewire Entangle) -->
    <div x-data="{ open: @entangle('isOpen') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="open = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-150 dark:border-gray-800 p-6 z-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Title -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $isEditing ? 'Ubah Paket Lebaran' : 'Tambah Paket Lebaran' }}
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Paket</label>
                        <input wire:model="name" type="text" id="name" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Paket Sembako Hemat" required>
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga Paket (Rp)</label>
                        <input wire:model="price" type="number" id="price" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 1200000" required>
                        @error('price') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="duration_weeks" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Durasi Cicilan (Minggu)</label>
                        <input wire:model="duration_weeks" type="number" id="duration_weeks" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 40" required>
                        @error('duration_weeks') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Paket</label>
                        <textarea wire:model="description" id="description" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Rincian isi paket Lebaran..."></textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status Keaktifan</label>
                        <select wire:model="status" id="status" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                        @error('status') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Gambar Paket</label>
                        <div class="mt-1 flex items-center gap-4">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                            @elseif ($existingImage)
                                <img src="{{ asset('storage/' . $existingImage) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-gray-50 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <input type="file" wire:model="image" id="package_image" class="hidden"
                                       onchange="if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) { alert('Ukuran gambar terlalu besar! Maksimal 2MB.'); this.value = ''; event.preventDefault(); return false; }">
                                <label for="package_image" class="px-4 py-2 border border-gray-350 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer inline-block">
                                    Pilih Gambar
                                </label>
                                <p class="text-[10px] text-gray-500 mt-1">PNG, JPG, JPEG, WebP maksimal 2MB</p>
                                
                                <div wire:loading wire:target="image" class="mt-2 text-xs text-indigo-600 dark:text-indigo-400 font-semibold flex items-center gap-1.5 animate-pulse">
                                    <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Mengunggah gambar...
                                </div>
                            </div>
                        </div>
                        @error('image') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800 mt-6">
                        <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="image" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Simpan Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
