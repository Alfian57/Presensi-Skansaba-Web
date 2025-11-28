<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'username' => ['required', 'string', 'unique:users,username,'.$user->id],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
        ]);

        $user->update($validated);

        Alert::success('Berhasil', 'Profil berhasil diperbarui.');

        return redirect()->route('dashboard.profile.edit');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            Alert::error('Gagal', 'Password lama tidak sesuai.');

            return back();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        Alert::success('Berhasil', 'Password berhasil diperbarui.');

        return redirect()->route('dashboard.profile.edit');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'], // 2MB
        ], [
            'photo.required' => 'Foto wajib dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new photo
        $path = $request->file('photo')->store('profiles', 'public');

        $user->update(['profile_picture' => $path]);

        Alert::success('Berhasil', 'Foto profil berhasil diperbarui.');

        return back();
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto()
    {
        $user = auth()->user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->update(['profile_picture' => null]);

            Alert::success('Berhasil', 'Foto profil berhasil dihapus.');
        } else {
            Alert::info('Info', 'Tidak ada foto profil untuk dihapus.');
        }

        return back();
    }
}
