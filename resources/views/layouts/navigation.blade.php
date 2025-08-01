<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.jpg') }}" alt="E-Perpus Itaru" class="block h-9 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- MENU UNTUK STAFF SAJA --}}
                    @if(Auth::user()->role === 'staff')
                    {{-- DROPDOWN BARU: MASTER ANGGOTA --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    {{ __('Keanggotaan') }}
                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.users.index')">
                                    {{ __('Manajemen Pengguna') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('peminjaman.index')">
                                    {{ __('Transaksi') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('denda.index')">
                                    {{ __('Denda Anggota') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- DROPDOWN MANAJEMEN DATA MASTER (yang sudah dibuat sebelumnya) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    {{ __('Manajemen Buku') }}
                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('buku.index')">
                                    {{ __('Buku') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('penerbit.index')">
                                    {{ __('Penerbit') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('pengarang.index')">
                                    {{ __('Pengarang') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('kategori.index')">
                                    {{ __('Kategori') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endif {{-- AKHIR MENU STAFF SAJA --}}

                    {{-- MENU KHUSUS UNTUK KEPALA PERPUSTAKAAN SAJA --}}
                    @if(Auth::user()->role === 'kepala perpustakaan')
                    <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                        {{ __('Laporan') }}
                    </x-nav-link>
                    @endif

                    {{-- MENU UNTUK ANGGOTA (atau semua user terautentikasi) --}}
                    @if(Auth::user()->role === 'anggota')
                    <x-nav-link :href="route('katalog.index')" :active="request()->routeIs('katalog.*')">
                        {{ __('Katalog Buku') }}
                    </x-nav-link>
                    <x-nav-link :href="route('peminjaman.my_history')" :active="request()->routeIs('peminjaman.my_history')">
                        {{ __('Riwayat Peminjaman') }}
                    </x-nav-link>
                    <x-nav-link :href="route('denda.my_denda')" :active="request()->routeIs('denda.my_denda')">
                        {{ __('Denda Saya') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <span class="inline-flex rounded-md">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                {{-- FOTO PROFIL KECIL DI NAV BAR --}}
                                @if (Auth::user()->foto)
                                <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->nama }}" />
                                @else
                                {{-- Placeholder SVG jika tidak ada foto --}}
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM12 12.004A6 6 0 1012 0a6 6 0 000 12.004z" />
                                </svg>
                                @endif
                                {{-- NAMA PENGGUNA --}}
                                {{ Auth::user()->nama }}

                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </span>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- MENU UNTUK STAFF SAJA --}}
            @if(Auth::user()->role === 'staff')
            {{-- HEADER UNTUK MASTER ANGGOTA DI RESPONSIVE --}}
            <div class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600">
                {{ __('Keanggotaan') }}
            </div>
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                {{ __('Manajemen Pengguna') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.*')">
                {{ __('Transaksi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('denda.index')" :active="request()->routeIs('denda.*')">
                {{ __('Denda Anggota') }}
            </x-responsive-nav-link>
            {{-- AKHIR HEADER DAN LINK MASTER ANGGOTA --}}

            {{-- HEADER UNTUK MANAJEMEN DATA MASTER DI RESPONSIVE --}}
            <div class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600">
                {{ __('Manajemen Buku') }}
            </div>
            <x-responsive-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                {{ __('Buku') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('penerbit.index')" :active="request()->routeIs('penerbit.*')">
                {{ __('Penerbit') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pengarang.index')" :active="request()->routeIs('pengarang.*')">
                {{ __('Pengarang') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kategori.index')" :active="request()->routeIs('kategori.*')">
                {{ __('Kategori') }}
            </x-responsive-nav-link>
            {{-- AKHIR HEADER DAN LINK MANAJEMEN DATA MASTER --}}
            @endif {{-- AKHIR MENU STAFF SAJA --}}

            {{-- MENU KHUSUS UNTUK KEPALA PERPUSTAKAAN SAJA --}}
            @if(Auth::user()->role === 'kepala perpustakaan')
            <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            @endif

            {{-- MENU UNTUK ANGGOTA (atau semua user terautentikasi) --}}
            @if(Auth::user()->role === 'anggota' || Auth::user()->role === 'staff' || Auth::user()->role === 'kepala perpustakaan')
            <x-responsive-nav-link :href="route('katalog.index')" :active="request()->routeIs('katalog.*')">
                {{ __('Katalog Buku') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peminjaman.my_history')" :active="request()->routeIs('peminjaman.my_history')">
                {{ __('Riwayat Peminjaman') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('denda.my_denda')" :active="request()->routeIs('denda.my_denda')">
                {{ __('Denda Saya') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>