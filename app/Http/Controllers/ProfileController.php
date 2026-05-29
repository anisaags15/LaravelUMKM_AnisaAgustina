<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi;
use Carbon\Carbon;

class ProfileController extends Controller
{
    // ======================= SHOW PROFIL =======================
    public function show()
    {
        $user = Auth::user();

        $transaksi = Transaksi::where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->get();

        $totalTransaksi  = $transaksi->count();
        
        // PASTIKAN: diffInYears() menghasilkan integer bulat
        $lamaKeanggotaan = Carbon::parse($user->created_at)->diffInYears(now());

        return view('pelanggan.profil', compact(
            'user',
            'transaksi',
            'totalTransaksi',
            'lamaKeanggotaan'
        ));
    }

    // ======================= EDIT (Auto buka modal) =======================
    public function edit()
    {
        $user            = Auth::user();
        $transaksi       = Transaksi::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $totalTransaksi  = $transaksi->count();
        $lamaKeanggotaan = Carbon::parse($user->created_at)->diffInYears(now());

        return view('pelanggan.profil', compact(
            'user', 'transaksi', 'totalTransaksi', 'lamaKeanggotaan'
        ))->with('openEditModal', true);
    }

    // ======================= UPDATE DATA (PERBAIKAN: pakai kolom 'name') =======================
    public function update(Request $request)
    {
        // Validasi kolom 'name' (bukan 'nama')
        $request->validate([
            'name'  => 'required|string|max:255',      // ← name
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ], [
            'name.required'  => 'Nama Lengkap nggak boleh kosong ya!',
            'email.required' => 'Email wajib diisi biar bisa dapet promo dimsum.',
            'email.email'    => 'Duh, format emailnya salah tuh.',
            'email.unique'   => 'Email ini sudah dipakai akun lain.',
        ]);

        // Update ke kolom 'name' (default Laravel)
        Auth::user()->update([
            'name'  => $request->name,   // ← simpan ke name
            'email' => $request->email,
        ]);

        return redirect()->route('pelanggan.profil.show')
                         ->with('success', 'Profil berhasil diperbarui!');
    }

    // ======================= UPDATE PASSWORD =======================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error_password', 'Yah, password lama kamu salah!');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('pelanggan.profil.show')
                         ->with('success', 'Password sudah aman diperbarui!');
    }

    // ======================= UPLOAD FOTO =======================
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $ext = strtolower($request->file('profile_picture')->getClientOriginalExtension());     
        $path = $request->file('profile_picture')->storeAs(
            'profile_pictures',
            'profile_' . $user->id . '_' . time() . '.' . $ext,
            'public'
        );

        $user->update(['profile_picture' => $path]);

        return redirect()->route('pelanggan.profil.show')
                         ->with('success', 'Wih, foto profil baru sudah aktif!');
    }

    // ======================= HAPUS AKUN =======================
    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required']);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah! Akun gagal dihapus.');
        }

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun kamu resmi dihapus. See you!');
    }
}