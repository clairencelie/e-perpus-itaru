<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- FOTO PROFIL --}}
                        <div>
                            <x-input-label for="foto" :value="__('Foto Profil')" />
                            <div class="flex items-center mt-2 space-x-4">
                                {{-- Placeholder untuk Pratinjau --}}
                                <div id="profile-photo-preview-container" class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 flex items-center justify-center bg-gray-100 shadow-sm">
                                    <img id="new-profile-photo-preview" src="#" alt="Pratinjau Foto Baru" class="w-full h-full object-cover hidden">
                                    <svg id="default-profile-placeholder" class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM12 12.004A6 6 0 1012 0a6 6 0 000 12.004z" />
                                    </svg>
                                </div>

                                {{-- Input File --}}
                                <div>
                                    <input id="foto" type="file" name="foto" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name; document.getElementById('new-profile-photo-preview').src = URL.createObjectURL(this.files[0]); document.getElementById('new-profile-photo-preview').classList.remove('hidden'); document.getElementById('default-profile-placeholder').classList.add('hidden');">
                                    <label for="foto" class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Pilih Foto
                                    </label>
                                    <span id="file-name" class="ms-2 text-sm text-gray-600"></span>
                                    <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                                    <p class="text-xs text-gray-500 mt-1">Ukuran maks 2MB, format JPG, PNG, GIF, SVG.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Grid untuk Field Lainnya --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- USERNAME --}}
                            <div>
                                <x-input-label for="username" :value="__('Username')" />
                                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('username')" />
                            </div>

                            {{-- NAMA --}}
                            <div>
                                <x-input-label for="nama" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                            </div>

                            {{-- EMAIL --}}
                            <div class="md:col-span-2">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            {{-- PASSWORD --}}
                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            {{-- KONFIRMASI PASSWORD --}}
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                            </div>

                            {{-- ROLE --}}
                            <div class="md:col-span-2">
                                <x-input-label for="role" :value="__('Peran (Role)')" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption }}" @selected(old('role')==$roleOption)>
                                        {{ ucfirst($roleOption) }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role')" />
                            </div>

                            {{-- ALAMAT --}}
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" :value="old('alamat')" />
                                <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                            </div>

                            {{-- KAMPUS --}}
                            <div>
                                <x-input-label for="kampus" :value="__('Kampus')" />
                                <x-text-input id="kampus" name="kampus" type="text" class="mt-1 block w-full" :value="old('kampus')" />
                                <x-input-error class="mt-2" :messages="$errors->get('kampus')" />
                            </div>

                            {{-- NOMOR HP --}}
                            <div>
                                <x-input-label for="no_hp" :value="__('Nomor HP')" />
                                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp')" />
                                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Simpan Pengguna') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fotoInput = document.getElementById('foto');
        const fileNameSpan = document.getElementById('file-name');
        const existingPhoto = document.getElementById('existing-profile-photo'); // Bisa null jika di create
        const defaultPlaceholder = document.getElementById('default-profile-placeholder');
        const newPhotoPreview = document.getElementById('new-profile-photo-preview');

        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                fileNameSpan.innerText = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    newPhotoPreview.src = e.target.result;
                    newPhotoPreview.classList.remove('hidden'); // Tampilkan pratinjau baru

                    if (existingPhoto) { // Jika ada foto lama, sembunyikan
                        existingPhoto.classList.add('hidden');
                    }
                    if (defaultPlaceholder) { // Jika ada placeholder, sembunyikan
                        defaultPlaceholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // Jika tidak ada file yang dipilih (misal klik batal)
                fileNameSpan.innerText = '';
                newPhotoPreview.classList.add('hidden'); // Sembunyikan pratinjau

                if (existingPhoto && existingPhoto.src !== '') { // Jika ada foto lama, tampilkan kembali
                    existingPhoto.classList.remove('hidden');
                    if (defaultPlaceholder) { // Sembunyikan default placeholder
                        defaultPlaceholder.classList.add('hidden');
                    }
                } else if (defaultPlaceholder) { // Jika tidak ada foto lama, tampilkan placeholder default
                    defaultPlaceholder.classList.remove('hidden');
                }
            }
        });
    });
</script>
@endpush