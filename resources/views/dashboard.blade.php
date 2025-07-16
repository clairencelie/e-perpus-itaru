<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Anda telah login!") }}

                    {{-- Tampilan ringkasan untuk Staff atau Kepala Perpustakaan --}}
                    @if(Auth::user()->role === 'staff' || Auth::user()->role === 'kepala perpustakaan')
                    <h3 class="text-xl font-semibold mt-8 mb-6">Ringkasan Perpustakaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        {{-- Kartu Jumlah Buku --}}
                        <div class="bg-blue-50 p-6 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h4 class="text-2xl font-bold text-blue-800">{{ $totalBuku ?? 0 }}</h4>
                            <p class="text-sm text-blue-600 mt-2">Total Buku</p>
                        </div>

                        {{-- Kartu Jumlah Anggota --}}
                        <div class="bg-green-50 p-6 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h4 class="text-2xl font-bold text-green-800">{{ $totalAnggota ?? 0 }}</h4>
                            <p class="text-sm text-green-600 mt-2">Total Anggota</p>
                        </div>

                        {{-- Kartu Denda Belum Dibayar --}}
                        <div class="bg-red-50 p-6 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h4 class="text-2xl font-bold text-red-800">{{ $dendaBelumDibayar ?? 0 }}</h4>
                            <p class="text-sm text-red-600 mt-2">Denda Belum Dibayar</p>
                        </div>

                        {{-- Kartu Jumlah Peminjaman Buku (Aktif/Pending/Terlambat) --}}
                        <div class="bg-yellow-50 p-6 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h4 class="text-2xl font-bold text-yellow-800">{{ $totalPeminjamanAktif ?? 0 }}</h4>
                            <p class="text-sm text-yellow-600 mt-2">Peminjaman Aktif</p>
                        </div>

                        {{-- Kartu Peminjaman Terlambat --}}
                        <div class="bg-purple-50 p-6 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h4 class="text-2xl font-bold text-purple-800">{{ $peminjamanTerlambat ?? 0 }}</h4>
                            <p class="text-sm text-purple-600 mt-2">Peminjaman Terlambat</p>
                        </div>

                        {{-- Kartu Buku Tersedia --}}
                        <div class="bg-teal-50 p-6 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h4 class="text-2xl font-bold text-teal-800">{{ $bukuTersedia ?? 0 }}</h4>
                            <p class="text-sm text-teal-600 mt-2">Buku Tersedia</p>
                        </div>
                    </div>
                    @else
                    {{-- Tampilan Dashboard untuk Anggota (MODIFIKASI DI SINI) --}}
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3">Selamat Datang, {{ Auth::user()->nama }}!</h3>
                        <p class="text-gray-700 mb-6">Anda dapat menjelajahi katalog buku, melihat riwayat peminjaman, dan mengecek denda Anda.</p>

                        <div class="flex flex-col sm:flex-row flex-wrap gap-4 justify-start"> {{-- UBAH CLASS INI --}}
                            <a href="{{ route('katalog.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Lihat Katalog Buku
                            </a>
                            <a href="{{ route('peminjaman.my_history') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Riwayat Peminjaman Saya
                            </a>
                            <a href="{{ route('denda.my_denda') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white text-xs uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Denda Saya
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>