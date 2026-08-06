<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Pelanggan') }}
            </h2>
            <button onclick="Livewire.dispatch('open-customer-modal-event')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Pelanggan
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
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white pl-10 pr-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs" placeholder="Cari nama atau nomor telepon...">
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="filterPackageId" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs">
                <option value="">Semua Paket</option>
                @foreach ($packages as $pkg)
                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 text-gray-400 dark:text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Kontak</th>
                        <th class="py-4 px-6">Paket Terpilih</th>
                        <th class="py-4 px-6 text-center">Status Cicilan</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($customers as $c)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-50 dark:bg-purple-950/30 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold text-sm">
                                        {{ substr($c->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $c->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $c->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $c->phone_number ?? '-' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 max-w-[200px]">{{ $c->address ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if ($c->packages->isNotEmpty())
                                    <div class="flex flex-col gap-1 items-start">
                                        @foreach ($c->packages as $pkg)
                                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400 rounded-md max-w-fit">
                                                {{ $pkg->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Belum memilih paket</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($c->packages->isNotEmpty())
                                    <div class="flex flex-col items-center gap-1">
                                        @if ($c->weekly_status === 'Lancar')
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-md dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">Lancar</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 rounded-md dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">Menunggak</span>
                                            <span class="text-[9px] text-rose-500 font-bold">Tunggakan: Rp {{ number_format($c->arrears_amount, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="showDetail({{ $c->id }})" class="p-2 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition" title="Detail Progress">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button wire:click="edit({{ $c->id }})" class="p-2 text-gray-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition" title="Ubah Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus pelanggan ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $c->id }})" class="p-2 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition" title="Hapus Pelanggan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada pelanggan terdaftar dengan kriteria pencarian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $customers->links() }}
            </div>
        @endif
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
                        {{ $isEditing ? 'Ubah Data Pelanggan' : 'Tambah Pelanggan Baru' }}
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
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" id="name" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Ahmad Hidayat" required>
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input wire:model="email" type="email" id="email" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="contoh@gmail.com" required>
                            @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                            <input wire:model="phone_number" type="text" id="phone_number" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 0812xxxxxxxx" required>
                            @error('phone_number') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat Rumah</label>
                        <textarea wire:model="address" id="address" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Alamat lengkap tempat tinggal..."></textarea>
                        @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <span class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Paket Lebaran (Bisa lebih dari 1)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50/50 dark:bg-gray-800/50">
                            @foreach ($packages as $pkg)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-gray-800 cursor-pointer transition">
                                    <input type="checkbox" wire:model="package_ids" value="{{ $pkg->id }}" class="rounded text-indigo-600 border-gray-300 dark:border-gray-700 focus:ring-indigo-500 dark:bg-gray-900">
                                    <div class="text-sm">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $pkg->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($pkg->price / ($pkg->duration_weeks ?: 40), 0, ',', '.') }}/mgg ({{ $pkg->duration_weeks }} mgg)</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('package_ids') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai Menabung</label>
                        <input wire:model="start_date" type="date" id="start_date" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('start_date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Password Login {{ $isEditing ? '(Kosongkan jika tidak ingin diubah)' : '' }}
                        </label>
                        <input wire:model="password" type="password" id="password" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-xs focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Minimal 6 karakter" {{ $isEditing ? '' : 'required' }}>
                        @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800 mt-6">
                        <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md transition">
                            Simpan Pelanggan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Modal (Alpine.js + Livewire Entangle) -->
    <div x-data="{ open: @entangle('isOpenDetail') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="open = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-150 dark:border-gray-800 p-6 z-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Detail & Progress Cicilan Pelanggan
                    </h3>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if ($detailCustomer)
                    <!-- Details Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Data Pelanggan</span>
                            <h4 class="font-bold text-lg text-gray-900 dark:text-white mt-1">{{ $detailCustomer->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Phone: {{ $detailCustomer->phone_number ?? '-' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Email: {{ $detailCustomer->email }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Alamat: {{ $detailCustomer->address ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Data Paket Terpilih</span>
                            @if ($detailCustomer->packages->isNotEmpty())
                                <div class="flex flex-col gap-2 mt-1">
                                    @foreach ($detailCustomer->packages as $pkg)
                                        <div class="border-b border-gray-55 dark:border-gray-800 last:border-0 pb-1.5 last:pb-0">
                                            <h4 class="font-bold text-sm text-indigo-600 dark:text-indigo-400">{{ $pkg->name }}</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Harga: Rp {{ number_format($pkg->price, 0, ',', '.') }} (Rp {{ number_format($pkg->price / ($pkg->duration_weeks ?: 40), 0, ',', '.') }}/mgg)</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <h4 class="font-bold text-sm text-gray-400 mt-1">Belum memilih paket</h4>
                            @endif
                        </div>
                    </div>

                    <!-- Weekly Installment Plan -->
                    @if ($detailCustomer->packages->isNotEmpty())
                        @php
                            $calendar = $detailCustomer->getInstallmentCalendar();
                            $currentWeekIdx = $detailCustomer->current_week - 1;
                            $currentWeeklyAmt = isset($calendar[$currentWeekIdx]) ? $calendar[$currentWeekIdx]['expected_amount'] : ($detailCustomer->packages->sum(fn($p) => $p->price / ($p->duration_weeks ?: 40)));
                        @endphp
                        <div class="mb-6 p-4 rounded-xl border {{ $detailCustomer->weekly_status === 'Lancar' ? 'bg-emerald-50/30 border-emerald-100 dark:bg-emerald-950/5 dark:border-emerald-900/20' : 'bg-rose-50/30 border-rose-100 dark:bg-rose-950/5 dark:border-rose-900/20' }}">
                            <h4 class="font-bold text-xs uppercase tracking-wider text-gray-500 mb-3">Status Cicilan Mingguan</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-400">Minggu Berjalan:</span>
                                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">Minggu ke-{{ $detailCustomer->current_week }} dari {{ count($calendar) }} mgg</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400">Cicilan Mingguan:</span>
                                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">Rp {{ number_format($currentWeeklyAmt, 0, ',', '.') }} / mgg</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400">Status Tabungan:</span>
                                    <div class="mt-0.5">
                                        @if ($detailCustomer->weekly_status === 'Lancar')
                                            <span class="px-2 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md dark:bg-emerald-900/40 dark:text-emerald-300">Lancar (Up to Date)</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-bold bg-rose-100 text-rose-800 rounded-md dark:bg-rose-900/40 dark:text-rose-300">Menunggak</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400">Tenggat Minggu Ini:</span>
                                    <div class="font-bold text-sm text-gray-800 dark:text-gray-200">
                                        {{ $detailCustomer->current_week_deadline ? $detailCustomer->current_week_deadline->format('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                            @if ($detailCustomer->weekly_status === 'Menunggak')
                                <div class="mt-4 pt-3 border-t border-rose-100/55 dark:border-rose-900/20 text-xs font-semibold text-rose-700 dark:text-rose-450 flex items-center justify-between">
                                    <span>Total Tunggakan:</span>
                                    <span class="text-sm font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($detailCustomer->arrears_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Progress Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between text-sm font-semibold mb-2">
                            <span class="text-gray-700 dark:text-gray-300">Progress Pembayaran</span>
                            <span class="text-indigo-600 dark:text-indigo-400">
                                @php
                                    $pkgPrice = $detailCustomer->packages->sum('price') ?? 0;
                                    $percent = $pkgPrice > 0 ? min(100, round(($totalPaid / $pkgPrice) * 100)) : 0;
                                @endphp
                                {{ $percent }}% Terbayar
                            </span>
                        </div>
                        
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-3 overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div class="bg-gradient-to-r from-indigo-500 to-violet-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>

                        <!-- Mini metrics -->
                        <div class="grid grid-cols-3 gap-4 mt-4 text-center">
                            <div class="bg-gray-50 dark:bg-gray-800/40 rounded-xl p-3 border border-gray-100 dark:border-gray-800">
                                <span class="text-[10px] text-gray-400 uppercase font-semibold">Harga Paket</span>
                                <div class="font-bold text-sm text-gray-900 dark:text-white mt-1">Rp {{ number_format($pkgPrice, 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-emerald-50/50 dark:bg-emerald-950/10 rounded-xl p-3 border border-emerald-100/50 dark:border-emerald-900/10">
                                <span class="text-[10px] text-emerald-600 uppercase font-semibold">Sudah Dibayar</span>
                                <div class="font-bold text-sm text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-rose-50/50 dark:bg-rose-950/10 rounded-xl p-3 border border-rose-100/50 dark:border-rose-900/10">
                                <span class="text-[10px] text-rose-600 uppercase font-semibold">Sisa Cicilan</span>
                                <div class="font-bold text-sm text-rose-600 dark:text-rose-400 mt-1">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kalender Cicilan -->
                    @if ($detailCustomer && $detailCustomer->packages->isNotEmpty())
                        <div class="mb-8 p-4 rounded-xl border border-gray-150 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                                <div>
                                    <h4 class="font-bold text-xs uppercase tracking-wider text-gray-500">Kalender Cicilan</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ count($detailCustomer->getInstallmentCalendar()) }} minggu cicilan</p>
                                </div>
                                
                                <!-- Legenda -->
                                <div class="flex flex-wrap gap-x-3 gap-y-1.5 text-[10px] font-semibold text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <div class="w-2.5 h-2.5 rounded bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700"></div>
                                        <span>Belum</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-2.5 h-2.5 rounded bg-amber-500 shadow-xs"></div>
                                        <span>Pending</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-2.5 h-2.5 rounded bg-emerald-500 shadow-xs"></div>
                                        <span>Lunas</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-2.5 h-2.5 rounded bg-rose-500 shadow-xs"></div>
                                        <span>Telat</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Weeks Grid -->
                            <div class="grid grid-cols-6 sm:grid-cols-10 gap-2">
                                @foreach ($detailCustomer->getInstallmentCalendar() as $week)
                                    @php
                                        $statusClass = '';
                                        if ($week['status'] === 'verified') {
                                            $statusClass = 'bg-emerald-500 text-white shadow-xs border-emerald-600';
                                        } elseif ($week['status'] === 'pending') {
                                            $statusClass = 'bg-amber-500 text-white shadow-xs border-amber-600';
                                        } elseif ($week['status'] === 'late') {
                                            $statusClass = 'bg-rose-500 text-white shadow-xs border-rose-600';
                                        } else {
                                            $statusClass = 'bg-gray-50 dark:bg-gray-850/40 text-gray-400 dark:text-gray-500 border-gray-150 dark:border-gray-800';
                                        }
                                    @endphp
                                    <div class="aspect-square flex flex-col items-center justify-center rounded-lg border font-bold text-[10px] transition duration-200 cursor-help {{ $statusClass }}" 
                                         title="Minggu {{ $week['number'] }} • Tenggat: {{ $week['deadline'] ? $week['deadline']->format('d M Y') : '-' }} • Status: {{ ucfirst($week['status']) }} • Dibayar: Rp {{ number_format($week['paid_amount'] + $week['pending_amount'], 0, ',', '.') }} @if($week['pending_amount'] > 0)(Rp {{ number_format($week['paid_amount'], 0, ',', '.') }} Lunas, Rp {{ number_format($week['pending_amount'], 0, ',', '.') }} Pending)@endif • Target: Rp {{ number_format($week['expected_amount'], 0, ',', '.') }}">
                                        <span>{{ $week['number'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Payments History Section -->
                    <div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-4">Riwayat Pembayaran Pelanggan</h4>
                        <div class="max-h-60 overflow-y-auto space-y-3 pr-2">
                            @forelse ($detailPayments as $pmt)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($pmt->amount, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $pmt->payment_date->format('d M Y') }} • {{ $pmt->payment_method }}
                                        </div>
                                        @if ($pmt->admin_notes)
                                            <div class="text-[11px] text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-2 py-0.5 rounded-lg mt-1 inline-block">
                                                Catatan Admin: {{ $pmt->admin_notes }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if ($pmt->status === 'pending')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30">Pending</span>
                                        @elseif ($pmt->status === 'verified')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">Verified</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-600 border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">Rejected</span>
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
                @endif

                <div class="flex items-center justify-end pt-6 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="button" wire:click="closeDetail" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
