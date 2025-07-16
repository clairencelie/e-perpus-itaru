<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengguna</h3>

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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:items-start">
                        {{-- Kolom Kiri: Foto Profil --}}
                        <div class="md:col-span-1 flex justify-center items-start py-4">
                            @if($user->foto)
                            <div class="w-full relative pb-[100%] bg-gray-100 rounded-full overflow-hidden shadow-lg flex items-center justify-center"> {{-- Ratio 1:1 untuk foto profil --}}
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="absolute inset-0 w-full h-full object-cover">
                            </div>
                            @else
                            {{-- PLACEHOLDER YANG SUDAH DIBENARKAN & DITENGAHKAN --}}
                            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 flex items-center justify-center bg-blue-50 shadow-lg"> {{-- Gunakan ukuran tetap w-24 h-24 --}}
                                <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM12 12.004A6 6 0 1012 0a6 6 0 000 12.004z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        {{-- Kolom Kanan: Detail Pengguna --}}
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">ID Pengguna:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->id_user }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Username:</p>
                                <p class="mt-1 text-sm text-gray-900 font-bold">{{ $user->username }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nama Lengkap:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->nama }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Peran (Role):</p>
                                <p class="mt-1 text-sm text-gray-900 capitalize">{{ $user->role }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status Peminjaman:</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($user->status_peminjaman) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $user->status_peminjaman ? 'Terblokir' : 'Normal' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Buku Dipinjam:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $borrowedBooksCount }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Denda Belum Dibayar:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $outstandingFinesCount }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-sm text-gray-600">Alamat:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->alamat ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kampus:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->kampus ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nomor HP:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->no_hp ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Dibuat Pada:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Diperbarui Pada:</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end items-center gap-3">
                        <a href="{{ route('admin.users.edit', $user->id_user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>