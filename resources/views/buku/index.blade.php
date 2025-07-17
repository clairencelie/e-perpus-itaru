<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Buku</h3>
                        <a href="{{ route('buku.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Tambah Buku
                        </a>
                    </div>

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

                    {{-- FORM PENCARIAN/FILTER --}}
                    <div class="mb-6">
                        <form action="{{ route('buku.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                            <div>
                                <x-input-label for="search_buku" :value="__('Cari Buku')" />
                                <x-text-input type="text" id="search_buku" name="search" placeholder="Judul, ISBN, Pengarang, Kategori..." class="flex-grow" value="{{ request('search') }}" />
                            </div>
                            <div>
                                <x-input-label for="filter_penerbit" :value="__('Filter Penerbit')" />
                                <select id="filter_penerbit" name="penerbit_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Penerbit</option>
                                    @foreach($penerbits as $penerbit)
                                    <option value="{{ $penerbit->id_penerbit }}" @selected(request('penerbit_id')==$penerbit->id_penerbit)>{{ $penerbit->nama_penerbit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Filter') }}
                            </x-primary-button>
                            @if(request('search') || request('penerbit_id') || request('status_ketersediaan'))
                            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                            @endif
                        </form>
                    </div>
                    {{-- AKHIR FORM PENCARIAN/FILTER --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed"> {{-- TAMBAHKAN table-fixed di sini --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16"> {{-- Beri lebar pada TH --}}
                                        ID Buku
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48"> {{-- Beri lebar pada TH --}}
                                        Judul
                                    </th>
                                    <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32"> {{-- Beri lebar pada TH --}}
                                        Penerbit
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32"> {{-- Beri lebar pada TH --}}
                                        Pengarang
                                    </th> -->
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32"> {{-- Beri lebar pada TH --}}
                                        Kategori
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20"> {{-- Stok juga bisa diberi lebar --}}
                                        Stok
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24"> {{-- Status juga bisa diberi lebar --}}
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24"> {{-- Status juga bisa diberi lebar --}}
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($bukus as $buku)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 w-16"> {{-- Samakan lebar TD dengan TH --}}
                                        {{ $buku->id_buku }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 w-48 overflow-hidden whitespace-nowrap truncate"> {{-- TERAPKAN MASKING DI SINI --}}
                                        {{ $buku->judul }}
                                    </td>
                                    <!-- <td class="px-6 py-4 text-sm text-gray-900 w-32 overflow-hidden whitespace-nowrap truncate"> {{-- TERAPKAN MASKING DI SINI --}}
                                        {{ $buku->penerbit->nama_penerbit ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 w-32 overflow-hidden whitespace-nowrap truncate"> {{-- TERAPKAN MASKING DI SINI --}}
                                        {{ $buku->pengarangs->pluck('nama_pengarang')->join(', ') }}
                                    </td> -->
                                    <td class="px-6 py-4 text-sm text-gray-900 w-32 overflow-hidden whitespace-nowrap truncate"> {{-- TERAPKAN MASKING DI SINI --}}
                                        {{ $buku->kategoris->pluck('nama_kategori')->join(', ') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 w-20">
                                        {{ $buku->stok_buku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 w-24">
                                        {{ ucfirst($buku->status_ketersediaan) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-40">
                                        <a href="{{ route('buku.show', $buku->id_buku) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                        <a href="{{ route('buku.edit', $buku->id_buku) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('buku.destroy', $buku->id_buku) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data buku.
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