<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengarang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengarang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID Pengarang:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $pengarang->id_pengarang }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama Pengarang:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $pengarang->nama_pengarang }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dibuat Pada:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $pengarang->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Diperbarui Pada:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $pengarang->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex">
                        <a href="{{ route('pengarang.edit', $pengarang->id_pengarang) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                            Edit
                        </a>
                        <a href="{{ route('pengarang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>