<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // --- Perbaikan Kunci: Ambil path foto lama SEBELUM fill() ---
        $oldPhotoPath = $request->user()->getOriginal('foto'); // Mengambil nilai asli 'foto' dari database

        $userData = $request->validated(); // Dapatkan semua data yang sudah divalidasi

        // --- LOGIKA UPLOAD FOTO ---
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                    Storage::disk('public')->delete($oldPhotoPath);
            }

            $path = $request->file('foto')->store('profile_photos', 'public');
            $userData['foto'] = $path; // Simpan path relatif yang baru di $userData
        } else {
            // Jika tidak ada upload foto baru, pertahankan foto lama jika ada
            if ($oldPhotoPath) { // Gunakan $oldPhotoPath yang sudah diambil
                $userData['foto'] = $oldPhotoPath;
            } else {
                // Jika tidak ada foto lama dan tidak ada foto baru, pastikan field 'foto' tidak ada di $userData
                // Ini akan menyebabkan kolom 'foto' di DB menjadi NULL jika sebelumnya NULL/empty string
                unset($userData['foto']);
            }
        }
        // --- AKHIR LOGIKA UPLOAD FOTO ---

        // Masukkan semua data yang sudah divalidasi dan diolah ke objek user
        $request->user()->fill($userData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $isSaved = $request->user()->save();

        if ($isSaved) {
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } else {
            return Redirect::route('profile.edit')->with('error', 'Gagal memperbarui profil. Tidak ada perubahan yang disimpan atau terjadi kesalahan.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
