<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Peminjaman</h3>

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

                    {{-- FORM PENCARIAN/FILTER --}}
                    <div class="mb-6">
                        <form action="{{ route('peminjaman.index') }}" method="GET" class="flex flex-col sm:flex-row sm:flex-wrap gap-3 items-end">
                            <div class="w-full sm:w-auto flex-grow">
                                <x-input-label for="search_peminjaman" :value="__('Cari Peminjaman')" />
                                <x-text-input type="text" id="search_peminjaman" name="search" placeholder="Nama Anggota, Judul Buku, ISBN..." class="w-full" value="{{ request('search') }}" />
                            </div>
                            <div>
                                <x-input-label for="filter_status_peminjaman" :value="__('Status Peminjaman')" />
                                <select id="filter_status_peminjaman" name="status_peminjaman" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" @selected(request('status_peminjaman')=='pending' )>Pending</option>
                                    <option value="dipinjam" @selected(request('status_peminjaman')=='dipinjam' )>Dipinjam</option>
                                    <option value="diajukan_pengembalian" @selected(request('status_peminjaman')=='diajukan_pengembalian' )>Diajukan Pengembalian</option>
                                    <option value="dikembalikan" @selected(request('status_peminjaman')=='dikembalikan' )>Dikembalikan</option>
                                    <option value="ditolak" @selected(request('status_peminjaman')=='ditolak' )>Ditolak</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="filter_start_date_pinjam" :value="__('Dari Tgl Pinjam')" />
                                <x-text-input id="filter_start_date_pinjam" type="date" name="start_date_pinjam" :value="request('start_date_pinjam')" />
                            </div>
                            <div>
                                <x-input-label for="filter_end_date_pinjam" :value="__('Sampai Tgl Pinjam')" />
                                <x-text-input id="filter_end_date_pinjam" type="date" name="end_date_pinjam" :value="request('end_date_pinjam')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Filter') }}
                            </x-primary-button>
                            @if(request('search') || request('status_peminjaman') || request('start_date_pinjam') || request('end_date_pinjam'))
                            <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                            @endif
                        </form>
                    </div>
                    {{-- AKHIR FORM PENCARIAN/FILTER --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Peminjam
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Buku
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Pinjam
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jatuh Tempo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Kembali
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($peminjaman as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->id_peminjaman }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->user->nama ?? 'N/A' }} ({{ $item->user->username ?? 'N/A' }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->buku->judul ?? 'N/A' }} (ISBN: {{ $item->buku->ISBN ?? 'N/A' }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->tanggal_pinjam->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->tanggal_jatuh_tempo->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->tanggal_pengembalian ? $item->tanggal_pengembalian->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
        @if($item->status_peminjaman == 'pending') bg-yellow-100 text-yellow-800
        @elseif($item->status_peminjaman == 'dipinjam') bg-blue-100 text-blue-800
        @elseif($item->status_peminjaman == 'diajukan_pengembalian') bg-orange-100 text-orange-800
        @elseif($item->status_peminjaman == 'dikembalikan') bg-green-100 text-green-800
        @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $item->status_peminjaman)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        @if ($item->status_peminjaman == 'pending')
                                        <form action="{{ route('peminjaman.approve', $item->id_peminjaman) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm('Setujui peminjaman ini?')">Setujui</button>
                                        </form>
                                        <form action="{{ route('peminjaman.reject', $item->id_peminjaman) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                                        </form>
                                        @elseif (in_array($item->status_peminjaman, ['dipinjam', 'diajukan_pengembalian', 'terlambat', 'hilang']))
                                        <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                        {{-- Tombol 'Kembalikan' akan mengarah ke form pengembalian --}}
                                        <a href="{{ route('peminjaman.edit', $item->id_peminjaman) }}" class="text-blue-600 hover:text-blue-900">Proses Pengembalian</a>
                                        @else
                                        <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data peminjaman.
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

    {{-- Modal untuk Proses Pengembalian Buku --}}
    <div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Proses Pengembalian Buku</h3>
            <form id="returnForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="POST"> {{-- Karena rute prosesReturn adalah POST --}}

                <div class="mb-4">
                    <label for="kondisi_buku" class="block text-sm font-medium text-gray-700">Kondisi Buku</label>
                    <select id="kondisi_buku" name="kondisi_buku" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="keterangan_pengembalian" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                    <textarea id="keterangan_pengembalian" name="keterangan_pengembalian" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showReturnForm(peminjamanId) {
            const modal = document.getElementById('returnModal');
            const form = document.getElementById('returnForm');
            form.action = `/peminjaman/${peminjamanId}/process-return`; // Set action URL
            modal.classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>