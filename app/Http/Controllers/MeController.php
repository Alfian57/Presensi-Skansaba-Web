<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class MeController extends Controller
{
    public function changePassword()
    {
        session()->push('history', [
            'route' => 'admin/changePassword',
            'name' => 'Ganti Password',
        ]);

        $user = null;
        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
        } else {
            $user = Auth::guard('user')->user();
        }
        $data = [
            'title' => 'Ganti Password',
            'user' => $user,
        ];

        return view('auth.change-password', $data);
    }

    public function updatePassword(Request $request)
    {
        if (! Hash::check($request->oldPassword, $request->password)) {
            Alert::error('Password Lama Salah');

            return back();
        }

        $request->validate([
            'newPassword' => 'required|min:8',
        ]);

        if (Auth::guard('teacher')->check()) {
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->newPassword),
            ]);
        } else {
            Teacher::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->newPassword),
            ]);
        }

        return redirect('/admin/home')->with('success', 'Password Admin '.Auth::user()->name.' Berhasil Diubah');
    }

    public function changePic()
    {
        session()->push('history', [
            'route' => 'admin/changePic',
            'name' => 'Ganti Foto',
        ]);

        $user = null;
        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
        } else {
            $user = Auth::guard('user')->user();
        }

        $data = [
            'title' => 'Ganti Foto Profile',
            'user' => $user,
        ];

        return view('auth.change-photo', $data);
    }

    public function updatePic(Request $request)
    {
        $validatedData = $request->validate([
            'profile_picture' => 'image|file|max:10000',
        ]);

        if ($request->deleteImage == 'true') {
            Storage::delete($request->old_profile_picture);
            $validatedData['profile_picture'] = null;
        } else {
            if ($request->profile_picture !== $request->old_profile_picture) {
                if ($request->profile_picture) {
                    $validatedData['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
                }
                if ($request->old_profile_picture) {
                    Storage::delete($request->old_profile_picture);
                }
            } else {
                $validatedData['profile_picture'] = $request->profile_picture;
            }
        }

        $profilePic = $validatedData['profile_picture'];

        // Update user profile picture
        User::where('id', Auth::user()->id)->update([
            'profile_picture' => $profilePic,
        ]);

        return redirect('/admin/home')->with('success', 'Profile User Berhasil Diperbarui');
    }
}
