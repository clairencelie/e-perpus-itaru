<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User; // Import Model User
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Untuk validasi unique
use Illuminate\Support\Facades\Hash; // Untuk hashing password saat buat/reset user
use Illuminate\Support\Facades\Storage; // Untuk menghapus foto profil

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua pengguna (anggota, staff, kepala perpustakaan).
     */
    public function index(Request $request): View
    {
        $searchQuery = $request->input('search');
        $filterRole = $request->input('role');

        $query = User::query();

        // Logika Filter Pencarian
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('username', 'like', '%' . $searchQuery . '%')
                  ->orWhere('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $searchQuery . '%')
                  ->orWhere('alamat', 'like', '%' . $searchQuery . '%')
                  ->orWhere('kampus', 'like', '%' . $searchQuery . '%')
                  ->orWhere('no_hp', 'like', '%' . $searchQuery . '%');
            });
        }

        // Logika Filter Role
        if ($filterRole) {
            $query->where('role', $filterRole);
        }

        $users = $query->orderBy('nama')->get(); // Urutkan berdasarkan nama

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk menambah user baru.
     */
    public function create(): View
    {
        // Role yang tersedia
        $roles = ['anggota', 'staff', 'kepala perpustakaan'];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan user baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['anggota', 'staff', 'kepala perpustakaan'])],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kampus' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $userData = $request->all();
        $userData['password'] = Hash::make($request->password); // Hash password

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile_photos', 'public');
            $userData['foto'] = $path;
        }

        User::create($userData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail user.
     */
    public function show(User $user): View
    {
        // Hitung jumlah buku yang sedang dipinjam (status: dipinjam, terlambat, diajukan_pengembalian, pending)
        $borrowedBooksCount = Peminjaman::where('id_user', $user->id_user)
            ->whereIn('status_peminjaman', ['dipinjam', 'terlambat', 'diajukan_pengembalian', 'pending'])
            ->count();

        // Hitung jumlah denda yang belum dibayar
        $outstandingFinesCount = Denda::whereHas('peminjaman', function ($query) use ($user) {
            $query->where('id_user', $user->id_user);
        })
            ->where('status_pembayaran', 'belum_bayar')
            ->count();

        return view('admin.users.show', compact('user', 'borrowedBooksCount', 'outstandingFinesCount'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user): View
    {
        $roles = ['anggota', 'staff', 'kepala perpustakaan'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id_user, 'id_user')], // Gunakan $user->id_user jika PK bukan 'id'
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id_user, 'id_user')], // Gunakan $user->id_user jika PK bukan 'id'
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Password opsional saat update
            'role' => ['required', 'string', Rule::in(['anggota', 'staff', 'kepala perpustakaan'])],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kampus' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $userData = $request->except(['password', 'password_confirmation']); // Kecualikan password jika tidak diisi

        // Handle password update
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        } else {
            unset($userData['password']); // Jangan update password jika kosong
        }

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('profile_photos', 'public');
            $userData['foto'] = $path;
        } else {
            // Jika tidak ada upload foto baru, dan tidak ada intent untuk menghapus, pertahankan yang lama
            if ($user->foto && !$request->has('remove_foto')) { // Asumsi ada checkbox 'remove_foto'
                $userData['foto'] = $user->foto;
            } elseif ($request->has('remove_foto') && $user->foto) { // Jika 'remove_foto' dicentang
                Storage::disk('public')->delete($user->foto);
                $userData['foto'] = null;
            }
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus user.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Hapus foto profil jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
