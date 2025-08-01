<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Judul --}}
                        <div>
                            <x-input-label for="judul" :value="__('Judul')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul')" required autofocus />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        {{-- Tahun Terbit --}}
                        <div class="mt-4">
                            <x-input-label for="tahun_terbit" :value="__('Tahun Terbit')" />
                            <x-text-input id="tahun_terbit" class="block mt-1 w-full" type="number" name="tahun_terbit" :value="old('tahun_terbit')" required />
                            <x-input-error :messages="$errors->get('tahun_terbit')" class="mt-2" />
                        </div>

                        {{-- COVER BUKU --}}
                        <div class="mt-4">
                            <x-input-label for="cover" :value="__('Cover Buku')" />
                            <div class="flex items-center mt-2 space-x-4">
                                {{-- Kontainer untuk Pratinjau Gambar --}}
                                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg overflow-hidden border-2 border-gray-200 flex items-center justify-center bg-gray-100 shadow-sm">
                                    {{-- Pratinjau cover baru (awalnya tersembunyi) --}}
                                    <img id="new-cover-preview" src="#" alt="Pratinjau Cover" class="w-full h-full object-contain hidden">

                                    {{-- Placeholder SVG (awalnya ditampilkan) --}}
                                    <svg id="default-cover-placeholder" class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 6h16v12H4V6zm14 2H6v8h12V8zm-6 2c-.55 0-1 .45-1 1s.45 1 1 1h4c.55 0 1-.45 1-1s-.45-1-1-1h-4zM6 14h12v-2H6v2z" />
                                    </svg>
                                </div>

                                {{-- Input File Cover --}}
                                <div>
                                    <input id="cover" type="file" name="cover" class="hidden">
                                    <label for="cover" class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Pilih Cover
                                    </label>
                                    <span id="cover-file-name" class="ms-2 text-sm text-gray-600"></span>
                                    <x-input-error :messages="$errors->get('cover')" class="mt-2" />
                                    <p class="text-xs text-gray-500 mt-1">Ukuran maks 2MB, format JPG, PNG, GIF, SVG.</p>
                                </div>
                            </div>
                        </div>
                        {{-- AKHIR COVER BUKU --}}

                        {{-- ISBN --}}
                        <div class="mt-4">
                            <x-input-label for="ISBN" :value="__('ISBN')" />
                            <x-text-input id="ISBN" class="block mt-1 w-full" type="text" name="ISBN" :value="old('ISBN')" required />
                            <x-input-error :messages="$errors->get('ISBN')" class="mt-2" />
                        </div>

                        {{-- Stok Buku --}}
                        <div class="mt-4">
                            <x-input-label for="stok_buku" :value="__('Stok Buku')" />
                            <x-text-input id="stok_buku" class="block mt-1 w-full" type="number" name="stok_buku" :value="old('stok_buku')" required min="0" />
                            <x-input-error :messages="$errors->get('stok_buku')" class="mt-2" />
                        </div>

                        {{-- Penerbit --}}
                        <div class="mt-4">
                            <x-input-label for="id_penerbit" :value="__('Penerbit')" />
                            <select id="id_penerbit" name="id_penerbit" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Penerbit</option>
                                @foreach ($penerbits as $penerbit)
                                <option value="{{ $penerbit->id_penerbit }}" @selected(old('id_penerbit')==$penerbit->id_penerbit)>
                                    {{ $penerbit->nama_penerbit }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_penerbit')" class="mt-2" />
                        </div>

                        {{-- Pengarang (Multiple Select / Checkbox) --}}
                        <div class="mt-4">
                            <x-input-label for="pengarang_ids" :value="__('Pengarang')" />
                            <select multiple id="pengarang_ids" name="pengarang_ids[]" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($pengarangs as $pengarang)
                                <option value="{{ $pengarang->id_pengarang }}" @selected(in_array($pengarang->id_pengarang, old('pengarang_ids', [])))>
                                    {{ $pengarang->nama_pengarang }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pengarang_ids')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Tekan Ctrl/Command untuk memilih lebih dari satu.</p>
                        </div>

                        {{-- Kategori (Multiple Select / Checkbox) --}}
                        <div class="mt-4">
                            <x-input-label for="kategori_ids" :value="__('Kategori')" />
                            <select multiple id="kategori_ids" name="kategori_ids[]" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" @selected(in_array($kategori->id_kategori, old('kategori_ids', [])))>
                                    {{ $kategori->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kategori_ids')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Tekan Ctrl/Command untuk memilih lebih dari satu.</p>
                        </div>

                        {{-- Deskripsi Buku --}}
                        <div class="mt-4">
                            <x-input-label for="deskripsi_buku" :value="__('Deskripsi Buku')" />
                            <textarea id="deskripsi_buku" name="deskripsi_buku" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi_buku') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_buku')" class="mt-2" />
                        </div>

                        {{-- File PDF --}}
                        <div class="mt-4">
                            <x-input-label for="file_PDF" :value="__('File PDF (Opsional)')" />
                            <input id="file_PDF" type="file" name="file_PDF" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <x-input-error :messages="$errors->get('file_PDF')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Ukuran maks 10MB, format PDF.</p>
                        </div>

                        {{-- Tautan Digital --}}
                        <div class="mt-4">
                            <x-input-label for="tautan_digital" :value="__('Tautan Digital (Opsional)')" />
                            <x-text-input id="tautan_digital" class="block mt-1 w-full" type="url" name="tautan_digital" :value="old('tautan_digital')" />
                            <x-input-error :messages="$errors->get('tautan_digital')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">URL lengkap (misal: https://example.com/buku.html).</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Buku') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script cover preview loaded and DOM ready!'); // LOG 1

        const coverInput = document.getElementById('cover');
        const coverFileNameSpan = document.getElementById('cover-file-name');
        const defaultCoverPlaceholder = document.getElementById('default-cover-placeholder');
        const newCoverPreview = document.getElementById('new-cover-preview');

        // Pastikan elemen ditemukan
        if (!coverInput) {
            console.error('Element with ID "cover" not found!');
            return;
        } // LOG ERROR
        if (!newCoverPreview) {
            console.error('Element with ID "new-cover-preview" not found!');
            return;
        } // LOG ERROR

        console.log('Elements found. Attaching event listener to coverInput.', coverInput); // LOG 2

        coverInput.addEventListener('change', function(event) {
            console.log('Change event fired on cover input.'); // LOG 3
            const file = event.target.files[0];

            if (file) {
                console.log('File selected:', file.name, file.type, file.size); // LOG 4
                coverFileNameSpan.innerText = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('FileReader onload event fired. Result type:', typeof e.target.result); // LOG 5
                    newCoverPreview.src = e.target.result; // Set src gambar pratinjau
                    newCoverPreview.classList.remove('hidden'); // Tampilkan gambar pratinjau
                    console.log('newCoverPreview.classList after remove hidden:', newCoverPreview.classList.toString()); // LOG 6

                    if (defaultCoverPlaceholder) {
                        defaultCoverPlaceholder.classList.add('hidden'); // Sembunyikan placeholder default
                        console.log('defaultCoverPlaceholder hidden.'); // LOG 7
                    }
                };
                reader.onerror = function(e) { // Tambahkan error handler untuk FileReader
                    console.error('FileReader error:', e);
                    alert('Gagal membaca file gambar. Silakan coba lagi atau gunakan file lain.');
                };
                reader.readAsDataURL(file); // Baca file sebagai URL data
            } else {
                console.log('No file selected (or selection cancelled).'); // LOG 8
                coverFileNameSpan.innerText = '';
                newCoverPreview.classList.add('hidden'); // Sembunyikan pratinjau
                newCoverPreview.src = '#'; // Reset src agar gambar lama tidak terlihat (PENTING)

                // Tampilkan kembali placeholder default
                if (defaultCoverPlaceholder) {
                    defaultCoverPlaceholder.classList.remove('hidden');
                    console.log('defaultCoverPlaceholder shown.'); // LOG 9
                }
            }
        });
    });
</script>
{{-- AKHIR SCRIPT JAVASCRIPT --}}