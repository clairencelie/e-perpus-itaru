<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Requests\Auth\RegisterRequest;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            // Pastikan nama kunci di sini ('username', 'nama') SAMA PERSIS
            // dengan nama kolom di tabel 'users' Anda
            'username' => $request->username, // <-- BARIS BARU UNTUK USERNAME
            'nama' => $request->nama,         // <-- BARIS BARU UNTUK NAMA
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Jika ada kolom kustom lain yang ingin diisi saat registrasi, tambahkan di sini
            // Contoh: 'kampus' => $request->kampus,
            // Namun, untuk role, default-nya sudah diatur di migrasi, jadi tidak perlu di sini kecuali user bisa memilih.
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
