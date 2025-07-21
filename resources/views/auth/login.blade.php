<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- TAMBAHKAN TEKS SELAMAT DATANG DI SINI --}}
    <div class="mb-4 mt-4 text-center text-gray-700 text-lg font-semibold">
        Selamat datang di E-Perpus Itaru
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pe-10"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />

                {{-- Tombol Toggle Password --}}
                <button type="button" id="togglePassword" class="absolute inset-y-0 end-0 pe-3 flex items-center text-gray-700 hover:text-gray-900 focus:outline-none focus:text-gray-900">
                    {{-- UBAH IKON MATA TERBUKA --}}
                    <svg id="eye-open" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zM12 14c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                    </svg>

                    {{-- UBAH IKON MATA TERTUTUP --}}
                    <svg id="eye-closed" class="h-5 w-5 hidden" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zM12 14c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                        <path d="M2 4.27l2.28 2.28L6.38 8.4l1.41 1.41L11.59 13.59l2.83 2.83L19.73 21 21 19.73 4.27 2 2 4.27z" fill="currentColor" class="text-gray-900" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
        </div> --}}

        <div class="flex items-center justify-end mt-4"> {{-- Tetap justify-end jika hanya Log In dan Register --}}
            {{-- Tombol Register (Jika Anda ingin tetap ada, ini adalah letaknya) --}}
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Register') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (togglePassword && passwordInput && eyeOpen && eyeClosed) {
            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the eye icon
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
            });
        } else {
            console.warn('Password toggle elements not found. Feature disabled.');
        }
    });
</script>