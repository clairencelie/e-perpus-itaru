<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules; // Pastikan ini diimport
use Illuminate\Validation\Rule; // <--- PASTIKAN INI JUGA DIIMPORT UNTUK Rule::unique

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')], // <--- PASTIKAN ADA Rule::unique
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')], // <--- PASTIKAN ADA Rule::unique
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Field opsional lainnya (sesuaikan jika Anda punya di form registrasi)
            'alamat' => ['nullable', 'string', 'max:255'],
            'kampus' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'role' => ['nullable', 'string', Rule::in(['anggota', 'staff', 'kepala perpustakaan'])],
        ];
    }
}
