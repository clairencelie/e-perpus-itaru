<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Perpustakaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Jenis Laporan</h3>

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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Laporan Peminjaman (MODIFIKASI DI SINI) --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Laporan Peminjaman</h4>
                            <p class="text-sm text-gray-600 mb-4">Lihat daftar lengkap semua transaksi peminjaman buku.</p>
                            <form action="{{ route('laporan.generate.peminjaman_status_pdf') }}" method="GET" class="flex flex-col space-y-3">
                                <div>
                                    <x-input-label for="status_peminjaman_filter" :value="__('Status Peminjaman')" />
                                    <select id="status_peminjaman_filter" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="dipinjam">Dipinjam</option>
                                        <option value="diajukan_pengembalian">Diajukan Pengembalian</option>
                                        <option value="dikembalikan">Dikembalikan</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                </div>
                                {{-- TAMBAHKAN INPUT TANGGAL INI --}}
                                <div>
                                    <x-input-label for="start_date_peminjaman" :value="__('Dari Tanggal Transaksi')" />
                                    <x-text-input id="start_date_peminjaman" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date')" />
                                </div>
                                <div>
                                    <x-input-label for="end_date_peminjaman" :value="__('Sampai Tanggal Transaksi')" />
                                    <x-text-input id="end_date_peminjaman" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date')" />
                                </div>
                                {{-- AKHIR INPUT TANGGAL --}}
                                <x-primary-button class="justify-center">
                                    {{ __('Generate PDF Laporan') }}
                                </x-primary-button>
                            </form>
                        </div>

                        {{-- Laporan Denda --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Laporan Denda</h4>
                            <p class="text-sm text-gray-600 mb-4">Lihat daftar denda yang telah dibayarkan atau belum.</p>
                            <form action="{{ route('laporan.generate.denda_pdf') }}" method="GET" class="flex flex-col space-y-3">
                                <div>
                                    <x-input-label for="status_denda_filter" :value="__('Status Pembayaran')" />
                                    <select id="status_denda_filter" name="status_pembayaran" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="lunas">Lunas</option>
                                        <option value="belum_bayar">Belum Bayar</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="start_date_denda" :value="__('Dari Tanggal Denda Dibuat')" />
                                    <x-text-input id="start_date_denda" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" />
                                </div>
                                <div>
                                    <x-input-label for="end_date_denda" :value="__('Sampai Tanggal Denda Dibuat')" />
                                    <x-text-input id="end_date_denda" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                                </div>
                                <x-primary-button class="justify-center">
                                    {{ __('Generate PDF Laporan') }}
                                </x-primary-button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>