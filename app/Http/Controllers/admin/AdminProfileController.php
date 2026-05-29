<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    // ======================= SHOW PROFIL ADMIN =======================
    public function show()
    {
        $user = Auth::user();
        return view('admin.profil', compact('user'));
    }

    // ======================= UPLOAD FOTO =======================
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'profile_picture.required' => 'Pilih foto terlebih dahulu.',
            'profile_picture.image'    => 'File harus berupa gambar.',
            'profile_picture.mimes'    => 'Format harus JPG, PNG, atau GIF.',
            'profile_picture.max'      => 'Ukuran maksimal 2MB.',
        ]);

        $user = Auth::user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $ext  = $request->file('profile_picture')->extension();
        $path = $request->file('profile_picture')->storeAs(
            'profile_pictures',
            'admin_' . $user->id . '_' . time() . '.' . $ext,
            'public'
        );

        $user->update(['profile_picture' => $path]);

        return redirect()->route('admin.profil.show')
                         ->with('success', 'Foto profil berhasil diperbarui!');
    }

    // ======================= UPDATE NAMA & EMAIL =======================
    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',   // <-- PERUBAHAN: name bukan nama
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ], [
            'name.required'  => 'Nama tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        Auth::user()->update([
            'name'  => $request->name,   // <-- PERUBAHAN: name
            'email' => $request->email,
        ]);

        return redirect()->route('admin.profil.show')
                         ->with('success', 'Profil berhasil diperbarui!');
    }

    // ======================= UPDATE PASSWORD =======================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error_password', 'Password lama salah!');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profil.show')
                         ->with('success', 'Password berhasil diperbarui!');
    }
}