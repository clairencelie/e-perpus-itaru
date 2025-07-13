<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengarang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('pengarang.update', $pengarang->id_pengarang) }}">
                        @csrf
                        @method('PUT') {{-- Penting: untuk method PUT/PATCH --}}

                        {{-- Nama Pengarang --}}
                        <div>
                            <x-input-label for="nama_pengarang" :value="__('Nama Pengarang')" />
                            <x-text-input id="nama_pengarang" class="block mt-1 w-full" type="text" name="nama_pengarang" :value="old('nama_pengarang', $pengarang->nama_pengarang)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_pengarang')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Perbarui Pengarang') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>