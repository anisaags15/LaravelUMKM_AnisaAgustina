<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesan;
use App\Models\User;
use App\Models\Transaksi; 
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // tambahkan untuk query builder

class AdminController extends Controller
{
    // ======================= DASHBOARD STATISTIK =======================
    public function dashboard()
    {
        $totalProduk      = Produk::count();
        $totalPelanggan   = User::where('role', 'pelanggan')->count();
        $totalTransaksi   = Transaksi::count();
        $totalPendapatan  = Transaksi::sum('total_harga');
        
        // Data transaksi per hari (7 hari terakhir)
        $startDate = now()->subDays(6)->startOfDay();
        $endDate   = now()->endOfDay();
        
        $transaksiList = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                                  ->get(['created_at', 'total_harga']);
        
        $grouped = $transaksiList->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('total_harga');
        });
        
        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('d M');
            $data[] = $grouped->get(now()->subDays($i)->format('Y-m-d'), 0);
        }
        
        // Top 5 Produk Terlaris
        $topProducts = TransaksiDetail::select('produk_id', DB::raw('SUM(qty) as total_terjual'))
                        ->with('produk')
                        ->groupBy('produk_id')
                        ->orderBy('total_terjual', 'desc')
                        ->limit(5)
                        ->get()
                        ->map(function($item) {
                            return [
                                'nama' => $item->produk->nama ?? 'Produk dihapus',
                                'total_terjual' => $item->total_terjual,
                            ];
                        });
        
        return view('admin.dashboard', compact(
            'totalProduk', 'totalPelanggan', 'totalTransaksi', 'totalPendapatan',
            'labels', 'data', 'topProducts'
        ));
    }

    // ======================= MANAJEMEN PRODUK =======================
    
    public function produkIndex()
    {
        $produkList = Produk::with('kategori')->get();
        $kategori   = Kategori::all();
        return view('admin.produk.index', compact('produkList', 'kategori'));
    }
    
    public function produkCreate()
    {
        $kategori = Kategori::all();
        return view('admin.produk.create', compact('kategori'));
    }
    
    public function produkStore(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'id_kategori' => 'required|exists:kategoris,id', 
            'deskripsi'   => 'required|string',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        $isFeatured = $request->has('is_featured') ? true : false;

        Produk::create([
            'nama'        => $request->nama,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'kategori_id' => $request->id_kategori, 
            'deskripsi'   => $request->deskripsi,
            'foto'        => $fotoPath,
            'is_featured' => $isFeatured,
        ]);

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }
    
    public function produkEdit($id)
    {
        $produk   = Produk::findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }
    
    public function produkUpdate(Request $request, $id)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'id_kategori' => 'required|exists:kategoris,id',
            'deskripsi'   => 'required|string',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $produk = Produk::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $produk->foto = $request->file('foto')->store('produk', 'public');
        }

        $isFeatured = $request->has('is_featured') ? true : false;

        $produk->update([
            'nama'        => $request->nama,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'kategori_id' => $request->id_kategori,
            'deskripsi'   => $request->deskripsi,
            'foto'        => $produk->foto,
            'is_featured' => $isFeatured,
        ]);

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil diupdate!');
    }
    
    public function produkDestroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    // ======================= PESAN MASUK =======================
    public function pesanIndex()
    {
        $pesans = Pesan::latest()->get();
        return view('admin.pesan.index', compact('pesans'));
    }

    public function pesanDestroy($id)
    {
        $pesan = Pesan::findOrFail($id);
        $pesan->delete();

        return redirect()->route('admin.pesan.index')
                         ->with('success', 'Pesan berhasil dihapus!');
    }

    // ======================= DATA PELANGGAN =======================
    public function pelangganIndex()
    {
        $pelanggans = User::where('role', 'pelanggan')->get();
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function pelangganDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.pelanggan.index')
                         ->with('success', 'Data pelanggan berhasil dihapus!');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => bcrypt('password123')
        ]);

        return redirect()->route('admin.pelanggan.index')
                         ->with('success', 'Password pelanggan berhasil direset ke: password123');
    }

    // ======================= VERIFIKASI PEMBAYARAN TRANSFER =======================
    public function verifyPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->payment_method != 'transfer') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya transaksi dengan metode transfer yang dapat diverifikasi.'
            ]);
        }

        if ($transaksi->payment_status == 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini sudah diverifikasi sebelumnya.'
            ]);
        }

$transaksi->update([
    'payment_status' => 'verified',
    'confirmed_by'   => Auth::id(),
    'confirmed_at'   => now(),
    'is_verified_notified' => false, 
]);
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi. Pelanggan sekarang dapat mencetak invoice.'
        ]);
    }

    public function rejectPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->payment_method != 'transfer') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya transaksi dengan metode transfer yang dapat ditolak.'
            ]);
        }

        if ($transaksi->payment_status == 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah diverifikasi, tidak bisa ditolak.'
            ]);
        }

        // Hapus file bukti transfer jika ada
        if ($transaksi->payment_proof && Storage::disk('public')->exists($transaksi->payment_proof)) {
            Storage::disk('public')->delete($transaksi->payment_proof);
        }

        $transaksi->update([
            'payment_status' => 'rejected',
            'payment_proof'  => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran ditolak. Pelanggan perlu upload ulang bukti transfer.'
        ]);
    }
}