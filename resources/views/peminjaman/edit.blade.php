<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pengembalian Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Verifikasi Pengembalian Buku</h3>

                    <form method="POST" action="{{ route('peminjaman.process_return', $peminjaman->id_peminjaman) }}">
                        @csrf
                        <!-- @method('PUT') {{-- Gunakan PUT untuk update --}} -->

                        {{-- Informasi Peminjaman --}}
                        <div class="mb-6 border p-4 rounded-md bg-gray-50">
                            <p class="text-sm text-gray-700"><strong>ID Peminjaman:</strong> {{ $peminjaman->id_peminjaman }}</p>
                            <p class="text-sm text-gray-700"><strong>Peminjam:</strong> {{ $peminjaman->user->nama ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-700"><strong>Buku:</strong> {{ $peminjaman->buku->judul ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-700"><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                            <p class="text-sm text-gray-700"><strong>Tanggal Jatuh Tempo:</strong> {{ $peminjaman->tanggal_jatuh_tempo->format('d M Y') }}</p>
                            <p class="text-sm text-gray-700"><strong>Status Saat Ini:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($peminjaman->status_peminjaman == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($peminjaman->status_peminjaman == 'dipinjam') bg-blue-100 text-blue-800
                                    @elseif($peminjaman->status_peminjaman == 'diajukan_pengembalian') bg-orange-100 text-orange-800
                                    @elseif($peminjaman->status_peminjaman == 'dikembalikan') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $peminjaman->status_peminjaman)) }}
                                </span>
                            </p>
                        </div>

                        {{-- Kondisi Buku --}}
                        <div>
                            <x-input-label for="kondisi_buku" :value="__('Kondisi Buku Saat Dikembalikan')" />
                            <select id="kondisi_buku" name="kondisi_buku" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="baik" @selected(old('kondisi_buku')=='baik' )>Baik</option>
                                <option value="rusak" @selected(old('kondisi_buku')=='rusak' )>Rusak</option>
                                <option value="hilang" @selected(old('kondisi_buku')=='hilang' )>Hilang</option>
                            </select>
                            <x-input-error :messages="$errors->get('kondisi_buku')" class="mt-2" />
                        </div>

                        {{-- Keterangan Pengembalian --}}
                        <div class="mt-4">
                            <x-input-label for="keterangan_pengembalian" :value="__('Keterangan (Opsional)')" />
                            <x-text-input id="keterangan_pengembalian" class="block mt-1 w-full" type="text" name="keterangan_pengembalian" :value="old('keterangan_pengembalian')" />
                            <x-input-error :messages="$errors->get('keterangan_pengembalian')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Proses Pengembalian') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>