<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Denda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Status Pembayaran Denda</h3>

                    <form method="POST" action="{{ route('denda.update', $denda->id_denda) }}">
                        @csrf
                        @method('PUT')

                        {{-- Informasi Peminjaman --}}
                        <div class="mb-6 border p-4 rounded-md bg-gray-50">
                            <p class="text-sm text-gray-700"><strong>ID Peminjaman:</strong> {{ $denda->peminjaman->id_peminjaman ?? '-' }}</p>
                            <p class="text-sm text-gray-700"><strong>Peminjam:</strong> {{ $denda->peminjaman->user->nama ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-700"><strong>Buku:</strong> {{ $denda->peminjaman->buku->judul ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-700"><strong>Nominal Denda:</strong> Rp. {{ number_format($denda->nominal_denda, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-700"><strong>Keterangan:</strong>
                                @if($denda->is_terlambat) Terlambat @endif
                                @if($denda->is_terlambat && $denda->is_rusak) & @endif
                                @if($denda->is_rusak) Rusak/Hilang @endif
                                @if(!$denda->is_terlambat && !$denda->is_rusak) - @endif
                            </p>
                        </div>

                        {{-- Status Pembayaran --}}
                        <div>
                            <x-input-label for="status_pembayaran" :value="__('Status Pembayaran')" />
                            <select id="status_pembayaran" name="status_pembayaran" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="belum_bayar" @selected(old('status_pembayaran', $denda->status_pembayaran) == 'belum_bayar')>Belum Bayar</option>
                                <option value="lunas" @selected(old('status_pembayaran', $denda->status_pembayaran) == 'lunas')>Lunas</option>
                            </select>
                            <x-input-error :messages="$errors->get('status_pembayaran')" class="mt-2" />
                        </div>

                        {{-- Tanggal Bayar (opsional, bisa diisi otomatis di controller) --}}
                        <div class="mt-4">
                            <x-input-label for="tanggal_bayar" :value="__('Tanggal Bayar (Opsional)')" />
                            <x-text-input id="tanggal_bayar" class="block mt-1 w-full" type="date" name="tanggal_bayar" :value="old('tanggal_bayar', $denda->tanggal_bayar ? $denda->tanggal_bayar->format('Y-m-d') : '')" />
                            <x-input-error :messages="$errors->get('tanggal_bayar')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Isi jika tanggal pembayaran spesifik, atau biarkan kosong untuk tanggal saat ini jika status Lunas dipilih.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Perbarui Denda') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>