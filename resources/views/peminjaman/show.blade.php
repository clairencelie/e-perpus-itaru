<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Peminjaman</h3>

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
                    @if (session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        {{ session('info') }}
                    </div>
                    @endif

                    {{-- Kontainer utama gambar dan detail peminjaman --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:items-start">
                        {{-- Kolom Kiri: Gambar Cover Buku --}}
                        <div class="md:col-span-1 flex justify-center items-start py-4">
                            @if($peminjaman->buku->cover)
                            {{-- Wrapper gambar dengan tinggi relatif dan background --}}
                            <div class="w-full relative pb-[133.33%] md:pb-[150%] bg-gray-100 rounded-lg shadow-lg overflow-hidden flex items-center justify-center">
                                <img src="{{ asset('storage/' . $peminjaman->buku->cover) }}" alt="Cover {{ $peminjaman->buku->judul }}" class="absolute inset-0 w-full h-full object-contain">
                            </div>
                            @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg shadow-lg">
                                Tidak Ada Cover
                            </div>
                            @endif
                        </div>

                        {{-- Kolom Kanan: Detail Peminjaman --}}
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">ID Peminjaman:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->id_peminjaman }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Peminjam:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $peminjaman->user->nama ?? 'N/A' }} ({{ $peminjaman->user->username ?? 'N/A' }})
                                </p>
                            </div>
                            <div class="sm:col-span-2"> {{-- Buku mengambil 2 kolom agar judul panjang tidak terpotong --}}
                                <p class="text-sm text-gray-600">Buku:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $peminjaman->buku->judul ?? 'N/A' }} (ISBN: {{ $peminjaman->buku->ISBN ?? 'N/A' }})
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Pinjam:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Jatuh Tempo:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_jatuh_tempo->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Pengembalian:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_pengembalian ? $peminjaman->tanggal_pengembalian->format('d M Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status Peminjaman:</p>
                                <p class="mt-1 text-sm text-gray-900">
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
                            <div class="sm:col-span-2"> {{-- Keterangan mengambil 2 kolom --}}
                                <p class="text-sm text-gray-600">Keterangan:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->keterangan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Dibuat Pada:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Diperbarui Pada:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $peminjaman->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Denda Terkait --}}
                    @if($peminjaman->denda->isNotEmpty())
                    <h4 class="text-md font-medium text-gray-900 mt-6 mb-3">Informasi Denda Terkait</h4>
                    <ul class="list-disc list-inside text-sm text-gray-900">
                        @foreach($peminjaman->denda as $denda)
                        <li>
                            ID Denda: {{ $denda->id_denda }} | Nominal: Rp. {{ number_format($denda->nominal_denda, 0, ',', '.') }} |
                            Status Pembayaran: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $denda->status_pembayaran)) }}</span>
                            @if($denda->tanggal_bayar) (Dibayar: {{ $denda->tanggal_bayar->format('d M Y') }}) @endif
                            @if($denda->is_terlambat) <span class="text-red-500">(Terlambat)</span> @endif
                            @if($denda->is_rusak) <span class="text-red-500">(Rusak/Hilang)</span> @endif
                            <a href="{{ route('denda.show', $denda->id_denda) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">Lihat Detail Denda</a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-gray-500 mt-4">Tidak ada denda terkait peminjaman ini.</p>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="mt-6 flex justify-end items-center gap-3">
                        @if($peminjaman->status_peminjaman == 'pending')
                        <form action="{{ route('peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" class="inline-block">
                            @csrf
                            <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700" onclick="return confirm('Setujui peminjaman ini?')">
                                Setujui Peminjaman
                            </x-primary-button>
                        </form>
                        <form action="{{ route('peminjaman.reject', $peminjaman->id_peminjaman) }}" method="POST" class="inline-block">
                            @csrf
                            <x-primary-button type="submit" class="bg-red-600 hover:bg-red-700" onclick="return confirm('Tolak peminjaman ini?')">
                                Tolak Peminjaman
                            </x-primary-button>
                        </form>
                        @elseif(in_array($peminjaman->status_peminjaman, ['dipinjam', 'diajukan_pengembalian', 'terlambat', 'hilang']))
                        <a href="{{ route('peminjaman.edit', $peminjaman->id_peminjaman) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Proses Pengembalian
                        </a>
                        @endif

                        <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>