<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Denda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Denda</h3>

                    {{-- Pesan Sukses atau Error --}}
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    {{-- FORM FILTER DENDA --}}
                    <div class="mb-6">
                        <form action="{{ route('denda.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                            <div>
                                <x-input-label for="filter_status_pembayaran" :value="__('Status Pembayaran')" />
                                <select id="filter_status_pembayaran" name="status_pembayaran" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua</option>
                                    <option value="belum_bayar" @selected(request('status_pembayaran')=='belum_bayar' )>Belum Bayar</option>
                                    <option value="lunas" @selected(request('status_pembayaran')=='lunas' )>Lunas</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="filter_start_date" :value="__('Dari Tanggal Dibuat')" />
                                <x-text-input id="filter_start_date" type="date" name="start_date" :value="request('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="filter_end_date" :value="__('Sampai Tanggal Dibuat')" />
                                <x-text-input id="filter_end_date" type="date" name="end_date" :value="request('end_date')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Filter') }}
                            </x-primary-button>
                            @if(request('status_pembayaran') || request('start_date') || request('end_date')) {{-- Tampilkan tombol reset jika ada filter --}}
                            <a href="{{ route('denda.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                            @endif
                        </form>
                    </div>
                    {{-- AKHIR FORM FILTER --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Denda
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Peminjam
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Buku
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nominal Denda
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Bayar
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dendas as $denda)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $denda->id_denda }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $denda->peminjaman->user->nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $denda->peminjaman->buku->judul ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp. {{ number_format($denda->nominal_denda, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($denda->status_pembayaran == 'lunas') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $denda->status_pembayaran)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $denda->tanggal_bayar ? $denda->tanggal_bayar->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($denda->is_terlambat) Terlambat @endif
                                        @if($denda->is_terlambat && $denda->is_rusak) & @endif
                                        @if($denda->is_rusak) Rusak/Hilang @endif
                                        @if(!$denda->is_terlambat && !$denda->is_rusak) - @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('denda.show', $denda->id_denda) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                        <a href="{{ route('denda.edit', $denda->id_denda) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('denda.destroy', $denda->id_denda) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus denda ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data denda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>