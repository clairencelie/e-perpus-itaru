<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Buku Tersedia</h3>

                    {{-- Pesan Sukses atau Error dari Controller --}}
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

                    {{-- FORM PENCARIAN BUKU --}}
                    <div class="mb-6">
                        <form action="{{ route('katalog.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <x-text-input type="text" name="search" placeholder="Cari judul, ISBN, pengarang, kategori..." class="flex-grow" value="{{ request('search') }}" />
                            <x-primary-button type="submit">
                                {{ __('Cari') }}
                            </x-primary-button>
                            @if(request('search')) {{-- Tampilkan tombol reset jika ada query search --}}
                            <a href="{{ route('katalog.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                            @endif
                        </form>
                    </div>
                    {{-- AKHIR FORM PENCARIAN --}}

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse($bukus as $buku) {{-- Gunakan @forelse untuk pesan jika kosong --}}
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                            {{-- Tampilkan Cover Buku --}}
                            @if($buku->cover)
                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" class="w-full h-64 object-contain">
                            @else
                            {{-- Placeholder jika tidak ada cover --}}
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                Tidak Ada Cover
                            </div>
                            @endif

                            <div class="p-4 flex-grow"> {{-- Tambah flex-grow agar konten mengisi ruang --}}
                                <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $buku->judul }}</h4>
                                <p class="text-sm text-gray-600 mb-1">Oleh: {{ $buku->pengarangs->pluck('nama_pengarang')->join(', ') }}</p>
                                <p class="text-sm text-gray-600 mb-1">Penerbit: {{ $buku->penerbit->nama_penerbit ?? '-' }}</p>
                                <p class="text-sm text-gray-600 mb-1">Tahun: {{ $buku->tahun_terbit }}</p>
                                <p class="text-sm text-gray-600 mb-1">Kategori: {{ $buku->kategoris->pluck('nama_kategori')->join(', ') }}</p>
                                {{-- Kondisi untuk menampilkan Stok dan Status hanya jika BUKAN buku digital --}}
                                @if (!$buku->file_PDF && !$buku->tautan_digital)
                                <p class="text-sm text-gray-600 mb-1">Stok: <span class="font-semibold">{{ $buku->stok_buku }}</span></p>
                                <p class="text-sm text-gray-600 mb-3">Status: <span class="font-semibold text-green-600">{{ ucfirst($buku->status_ketersediaan) }}</span></p>
                                @else
                                <p class="text-sm text-gray-600 mb-3">Jenis: <span class="font-semibold text-purple-600">Digital</span></p> {{-- Opsional: tampilkan "Digital" --}}
                                @endif
                            </div>

                            {{-- Tombol Aksi (Pinjam/Baca/Tidak Tersedia) dan Detail --}}
                            <div class="p-4 bg-gray-100 border-t border-gray-200 flex flex-col space-y-2">
                                @auth
                                @if(Auth::user()->role === 'anggota')
                                @if ($buku->file_PDF || $buku->tautan_digital)
                                {{-- Tombol Baca untuk buku digital --}}
                                <a href="{{ $buku->file_PDF ? asset('storage/' . str_replace('public/', '', $buku->file_PDF)) : $buku->tautan_digital }}" target="_blank" class="w-full text-center bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 font-semibold text-xs uppercase tracking-widest">
                                    {{ __('Baca Buku') }}
                                </a>
                                @elseif ($buku->stok_buku > 0 && $buku->status_ketersediaan == 'tersedia')
                                {{-- Tombol Pinjam untuk buku fisik yang tersedia --}}
                                <form action="{{ route('peminjaman.request_borrow') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="id_buku" value="{{ $buku->id_buku }}">
                                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 font-semibold text-xs uppercase tracking-widest">
                                        {{ __('Pinjam') }}
                                    </button>
                                </form>
                                @else
                                {{-- Notifikasi Tidak Tersedia untuk buku fisik yang tidak tersedia --}}
                                <span class="block w-full text-center text-red-600 text-xs font-semibold py-2">Tidak Tersedia</span>
                                @endif
                                @endif
                                @endauth

                                {{-- Tombol Detail selalu ada --}}
                                <a href="{{ route('katalog.show', $buku->id_buku) }}" class="w-full text-center px-3 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Detail') }}
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center text-gray-500 py-10">
                            Belum ada buku yang tersedia di katalog.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>