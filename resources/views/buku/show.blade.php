<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Buku</h3>

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

                    {{-- Kontainer utama gambar dan detail --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:items-start">
                        {{-- Kolom Kiri: Gambar Cover --}}
                        <div class="md:col-span-1 flex justify-center items-start py-4">
                            @if($buku->cover)
                            {{-- Wrapper gambar dengan tinggi relatif dan background --}}
                            <div class="w-full relative pb-[133.33%] md:pb-[150%] bg-gray-100 rounded-lg shadow-lg overflow-hidden flex items-center justify-center">
                                <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" class="absolute inset-0 w-full h-full object-contain">
                            </div>
                            @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg shadow-lg">
                                Tidak Ada Cover
                            </div>
                            @endif
                        </div>

                        {{-- Kolom Kanan: Detail Buku --}}
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Judul:</p>
                                <p class="mt-1 text-sm text-gray-900 font-bold">{{ $buku->judul }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">ISBN:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->ISBN }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Penerbit:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->penerbit->nama_penerbit ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tahun Terbit:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->tahun_terbit }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pengarang:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    @forelse ($buku->pengarangs as $pengarang)
                                    {{ $pengarang->nama_pengarang }}{{ !$loop->last ? ', ' : '' }}
                                    @empty
                                    -
                                    @endforelse
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kategori:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    @forelse ($buku->kategoris as $kategori)
                                    {{ $kategori->nama_kategori }}{{ !$loop->last ? ', ' : '' }}
                                    @empty
                                    -
                                    @endforelse
                                </p>
                            </div>
                            {{-- Stok dan Status hanya jika BUKAN buku digital --}}
                            @if (!$buku->file_PDF && !$buku->tautan_digital)
                            <div>
                                <p class="text-sm text-gray-600">Stok Tersedia:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->stok_buku }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status Ketersediaan:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="font-semibold @if($buku->stok_buku > 0 && $buku->status_ketersediaan == 'tersedia') text-green-600 @else text-red-600 @endif">
                                        {{ ucfirst($buku->status_ketersediaan) }}
                                    </span>
                                </p>
                            </div>
                            @else
                            <div>
                                <p class="text-sm text-gray-600">Jenis Buku:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="font-semibold text-purple-600">Digital</span>
                                </p>
                            </div>
                            <div>
                                {{-- Kolom kosong untuk menjaga layout grid --}}
                            </div>
                            @endif
                            @if ($buku->file_PDF)
                            <div>
                                <p class="text-sm text-gray-600">File Digital (PDF):</p>
                                <p class="mt-1 text-sm text-gray-900"><a href="{{ asset('storage/' . $buku->file_PDF) }}" target="_blank" class="text-blue-600 hover:underline">Lihat PDF</a></p>
                            </div>
                            @endif
                            @if ($buku->tautan_digital)
                            <div>
                                <p class="text-sm text-gray-600">Tautan Digital:</p>
                                <p class="mt-1 text-sm text-gray-900"><a href="{{ $buku->tautan_digital }}" target="_blank" class="text-blue-600 hover:underline">{{ $buku->tautan_digital }}</a></p>
                            </div>
                            @endif
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Deskripsi Buku:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->deskripsi_buku ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Dibuat Pada:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Diperbarui Pada:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $buku->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end items-center gap-3">
                        <a href="{{ route('buku.edit', $buku->id_buku) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                        <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>