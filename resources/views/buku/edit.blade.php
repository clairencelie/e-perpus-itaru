<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('buku.update', $buku->id_buku) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Penting: untuk method PUT/PATCH --}}

                        {{-- Judul --}}
                        <div>
                            <x-input-label for="judul" :value="__('Judul')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul', $buku->judul)" required autofocus />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        {{-- Tahun Terbit --}}
                        <div class="mt-4">
                            <x-input-label for="tahun_terbit" :value="__('Tahun Terbit')" />
                            <x-text-input id="tahun_terbit" class="block mt-1 w-full" type="number" name="tahun_terbit" :value="old('tahun_terbit', $buku->tahun_terbit)" required />
                            <x-input-error :messages="$errors->get('tahun_terbit')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="cover" class="block text-sm font-medium text-gray-700">Cover Buku</label>
                            <input type="file" name="cover" id="cover" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            @error('cover')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            {{-- Untuk edit.blade.php, tampilkan cover lama jika ada --}}
                            @if(isset($buku) && $buku->cover)
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">Cover saat ini:</p>
                                <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover Buku" class="w-24 h-auto object-cover rounded-md mt-1">
                            </div>
                            @endif
                        </div>

                        {{-- ISBN --}}
                        <div class="mt-4">
                            <x-input-label for="ISBN" :value="__('ISBN')" />
                            <x-text-input id="ISBN" class="block mt-1 w-full" type="text" name="ISBN" :value="old('ISBN', $buku->ISBN)" required />
                            <x-input-error :messages="$errors->get('ISBN')" class="mt-2" />
                        </div>

                        {{-- Stok Buku --}}
                        <div class="mt-4">
                            <x-input-label for="stok_buku" :value="__('Stok Buku')" />
                            <x-text-input id="stok_buku" class="block mt-1 w-full" type="number" name="stok_buku" :value="old('stok_buku', $buku->stok_buku)" required min="0" />
                            <x-input-error :messages="$errors->get('stok_buku')" class="mt-2" />
                        </div>

                        {{-- Penerbit --}}
                        <div class="mt-4">
                            <x-input-label for="id_penerbit" :value="__('Penerbit')" />
                            <select id="id_penerbit" name="id_penerbit" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Penerbit</option>
                                @foreach ($penerbits as $penerbit)
                                <option value="{{ $penerbit->id_penerbit }}" @selected(old('id_penerbit', $buku->id_penerbit) == $penerbit->id_penerbit)>
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
                                <option value="{{ $pengarang->id_pengarang }}" @selected(in_array($pengarang->id_pengarang, old('pengarang_ids', $buku->pengarangs->pluck('id_pengarang')->toArray())))>
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
                                <option value="{{ $kategori->id_kategori }}" @selected(in_array($kategori->id_kategori, old('kategori_ids', $buku->kategoris->pluck('id_kategori')->toArray())))>
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
                            <textarea id="deskripsi_buku" name="deskripsi_buku" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi_buku', $buku->deskripsi_buku) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_buku')" class="mt-2" />
                        </div>

                        {{-- Status Ketersediaan --}}
                        <div class="mt-4">
                            <x-input-label for="status_ketersediaan" :value="__('Status Ketersediaan')" />
                            <select id="status_ketersediaan" name="status_ketersediaan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="tersedia" @selected(old('status_ketersediaan', $buku->status_ketersediaan) == 'tersedia')>Tersedia</option>
                                <option value="dipinjam" @selected(old('status_ketersediaan', $buku->status_ketersediaan) == 'dipinjam')>Dipinjam</option>
                                <option value="hilang" @selected(old('status_ketersediaan', $buku->status_ketersediaan) == 'hilang')>Hilang</option>
                            </select>
                            <x-input-error :messages="$errors->get('status_ketersediaan')" class="mt-2" />
                        </div>

                        {{-- File PDF --}}
                        <div class="mt-4">
                            <x-input-label for="file_PDF" :value="__('File PDF (Opsional)')" />
                            @if ($buku->file_PDF)
                            <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ Storage::url($buku->file_PDF) }}" target="_blank" class="text-blue-600 hover:underline">Lihat PDF</a></p>
                            @endif
                            <input id="file_PDF" type="file" name="file_PDF" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <x-input-error :messages="$errors->get('file_PDF')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah. Ukuran maks 10MB, format PDF.</p>
                        </div>

                        {{-- Tautan Digital --}}
                        <div class="mt-4">
                            <x-input-label for="tautan_digital" :value="__('Tautan Digital (Opsional)')" />
                            <x-text-input id="tautan_digital" class="block mt-1 w-full" type="url" name="tautan_digital" :value="old('tautan_digital', $buku->tautan_digital)" />
                            <x-input-error :messages="$errors->get('tautan_digital')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">URL lengkap (misal: https://example.com/buku.html).</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Perbarui Buku') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>