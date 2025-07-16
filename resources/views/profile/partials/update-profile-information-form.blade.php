<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- FOTO PROFIL --}}
        <div>
            <x-input-label for="foto" :value="__('Foto Profil')" />
            <div class="flex items-center mt-2 space-x-4">
                {{-- Tampilan Foto Saat Ini atau Pratinjau Baru --}}
                <div id="profile-photo-preview-container" class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 flex items-center justify-center bg-gray-100 shadow-sm">
                    @if ($user->foto)
                    <img id="existing-profile-photo" src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                    {{-- Placeholder jika tidak ada foto --}}
                    <svg id="default-profile-placeholder" class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM12 12.004A6 6 0 1012 0a6 6 0 000 12.004z" />
                    </svg>
                    @endif
                    <img id="new-profile-photo-preview" src="#" alt="Pratinjau Foto Baru" class="w-full h-full object-cover hidden"> {{-- Image untuk pratinjau baru --}}
                </div>

                {{-- Input File --}}
                <div>
                    <input id="foto" type="file" name="foto" class="hidden"> {{-- Hapus onchange di sini --}}
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
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>

            {{-- NAMA --}}
            <div>
                <x-input-label for="nama" :value="__('Nama Lengkap')" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)" required autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
            </div>

            {{-- EMAIL --}}
            <div class="md:col-span-2"> {{-- Email mengambil lebar penuh --}}
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi Anda.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </p>
                    @endif
                </div>
                @endif
            </div>

            {{-- ALAMAT --}}
            <div class="md:col-span-2"> {{-- Alamat mengambil lebar penuh --}}
                <x-input-label for="alamat" :value="__('Alamat')" />
                <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" :value="old('alamat', $user->alamat)" autocomplete="street-address" />
                <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
            </div>

            {{-- KAMPUS --}}
            <div>
                <x-input-label for="kampus" :value="__('Kampus')" />
                <x-text-input id="kampus" name="kampus" type="text" class="mt-1 block w-full" :value="old('kampus', $user->kampus)" autocomplete="organization" />
                <x-input-error class="mt-2" :messages="$errors->get('kampus')" />
            </div>

            {{-- NOMOR HP --}}
            <div>
                <x-input-label for="no_hp" :value="__('Nomor HP')" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $user->no_hp)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
            </div>

            {{-- ROLE (Hanya Tampilan, Tidak Bisa Diedit oleh Pengguna Sendiri) --}}
            <div class="md:col-span-2"> {{-- Role mengambil lebar penuh --}}
                <x-input-label for="role" :value="__('Peran (Role)')" />
                <p id="role" class="mt-1 text-base font-semibold text-gray-800 capitalize">{{ $user->role }}</p>
                <p class="text-xs text-gray-500 mt-1">Peran Anda tidak dapat diubah dari sini. Hubungi Administrator.</p>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6"> {{-- Tambah mt-6 untuk jarak dari grid --}}
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

{{-- SCRIPT JAVASCRIPT UNTUK LIVE PREVIEW --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fotoInput = document.getElementById('foto');
        const fileNameSpan = document.getElementById('file-name');
        const existingPhoto = document.getElementById('existing-profile-photo');
        const defaultPlaceholder = document.getElementById('default-profile-placeholder');
        const newPhotoPreview = document.getElementById('new-profile-photo-preview');

        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                // Tampilkan nama file
                fileNameSpan.innerText = file.name;

                // Baca file dan tampilkan pratinjau
                const reader = new FileReader();
                reader.onload = function(e) {
                    newPhotoPreview.src = e.target.result; // Set src gambar pratinjau
                    newPhotoPreview.classList.remove('hidden'); // Tampilkan gambar pratinjau

                    // Sembunyikan foto yang sudah ada dan placeholder default
                    if (existingPhoto) {
                        existingPhoto.classList.add('hidden');
                    }
                    if (defaultPlaceholder) {
                        defaultPlaceholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file); // Baca file sebagai URL data
            } else {
                // Jika tidak ada file yang dipilih (misal klik batal)
                fileNameSpan.innerText = '';
                newPhotoPreview.classList.add('hidden'); // Sembunyikan pratinjau

                // Tampilkan kembali foto yang sudah ada atau placeholder default
                if (existingPhoto) {
                    existingPhoto.classList.remove('hidden');
                }
                if (defaultPlaceholder && !existingPhoto) { // Hanya jika memang tidak ada foto lama
                    defaultPlaceholder.classList.remove('hidden');
                }
            }
        });
    });
</script>
@endpush