<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penerbit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penerbit</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID Penerbit:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $penerbit->id_penerbit }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama Penerbit:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $penerbit->nama_penerbit }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Alamat:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $penerbit->alamat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dibuat Pada:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $penerbit->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Diperbarui Pada:</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $penerbit->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex">
                        <a href="{{ route('penerbit.edit', $penerbit->id_penerbit) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                            Edit
                        </a>
                        <a href="{{ route('penerbit.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>