<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Denda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Denda</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID Denda:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->id_denda }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Peminjam:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->peminjaman->user->nama ?? 'N/A' }} ({{ $denda->peminjaman->user->username ?? 'N/A' }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Buku:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->peminjaman->buku->judul ?? 'N/A' }} (ISBN: {{ $denda->peminjaman->buku->ISBN ?? 'N/A' }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nominal Denda:</p>
                            <p class="mt-1 text-sm text-gray-900">Rp. {{ number_format($denda->nominal_denda, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status Pembayaran:</p>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($denda->status_pembayaran == 'lunas') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $denda->status_pembayaran)) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Bayar:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->tanggal_bayar ? $denda->tanggal_bayar->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Terlambat:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->is_terlambat ? 'Ya' : 'Tidak' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rusak/Hilang:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->is_rusak ? 'Ya' : 'Tidak' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dibuat Pada:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Diperbarui Pada:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $denda->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex">
                        <a href="{{ route('denda.edit', $denda->id_denda) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                            Edit Status Pembayaran
                        </a>
                        <a href="{{ route('denda.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>