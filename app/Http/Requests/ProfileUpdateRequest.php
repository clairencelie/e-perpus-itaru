<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user()->id_user, 'id_user')],
            'email' => ['email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id_user, 'id_user')],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kampus' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'], // Sesuaikan panjang maks
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Validasi untuk upload gambar
        ];
    }
}
