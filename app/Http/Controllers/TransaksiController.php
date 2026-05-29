<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    // ======================= HALAMAN CHECKOUT =======================
    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('pelanggan.cart.index')
                             ->with('error', 'Keranjang kosong!');
        }

        $products   = Produk::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $grandTotal = 0;
        $items      = [];

        foreach ($cart as $id => $qty) {
            if ($products->has($id)) {
                $produk    = $products[$id];
                $subtotal  = $produk->harga * $qty;
                $grandTotal += $subtotal;
                $items[]   = [
                    'produk'   => $produk,
                    'qty'      => $qty,
                    'subtotal' => $subtotal,
                ];
            }
        }

        return view('pelanggan.checkout', compact('items', 'grandTotal'));
    }

    // ======================= PROSES CHECKOUT =======================
    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'alamat'          => 'required|string',
            'no_hp'           => 'required|string|max:15',
            'payment_method'  => 'required|in:cod,transfer',          
            'shipping_method' => 'required|in:reguler,ekspres',
            'notes'           => 'nullable|string|max:500',
        ], [
            'nama.required'            => 'Nama wajib diisi.',
            'alamat.required'          => 'Alamat wajib diisi.',
            'no_hp.required'           => 'Nomor HP wajib diisi.',
            'payment_method.required'  => 'Pilih metode pembayaran.',
            'shipping_method.required' => 'Pilih metode pengiriman.',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('pelanggan.cart.index')
                             ->with('error', 'Keranjang kosong!');
        }

        $user     = Auth::user();
        $products = Produk::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Hitung total
        $totalHarga = 0;
        foreach ($cart as $id => $qty) {
            if ($products->has($id)) {
                $totalHarga += $products[$id]->harga * $qty;
            }
        }

        // Simpan pakai DB transaction supaya aman
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'user_id'         => $user->id,
                'tanggal'         => now(),
                'total_harga'     => $totalHarga,
                'nama_pemesan'    => $request->nama,
                'alamat'          => $request->alamat,
                'no_hp'           => $request->no_hp,
                'payment_method'  => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'notes'           => $request->notes,
                'payment_status'  => 'pending',
            ]);

            // Insert detail transaksi & kurangi stok
            foreach ($cart as $id => $qty) {
                if (!$products->has($id)) continue;

                $produk = Produk::find($id);
                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$id} tidak ditemukan.");
                }
                if ($produk->stok < $qty) {
                    throw new \Exception("Stok produk {$produk->nama} tidak mencukupi (tersisa {$produk->stok}).");
                }

                TransaksiDetail::create([
                    'transaksi_id'  => $transaksi->id,
                    'produk_id'     => $id,
                    'qty'           => $qty,
                    'harga_satuan'  => $products[$id]->harga,
                ]);

                $produk->decrement('stok', $qty);
            }

            DB::commit();
            session()->forget('cart');

            // Redirect sesuai metode pembayaran
            if ($transaksi->payment_method == 'transfer') {
                return redirect()->route('pelanggan.upload.proof.form', $transaksi->id)
                                 ->with('success', 'Pesanan berhasil! Silakan upload bukti transfer.');
            }

            return redirect()->route('pelanggan.checkout.success', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pelanggan.cart.index')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ======================= HALAMAN SUKSES =======================
    public function success($id)
    {
        $transaksi = Transaksi::with('details.produk')->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) abort(403);
        return view('pelanggan.checkout-success', compact('transaksi'));
    }

    // ======================= UPLOAD BUKTI TRANSFER =======================
    public function showUploadForm($id)
    {
        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($id);
        if ($transaksi->payment_method != 'transfer') {
            return redirect()->route('pelanggan.history')->with('error', 'Metode pembayaran bukan transfer.');
        }
        if ($transaksi->payment_status == 'verified') {
            return redirect()->route('pelanggan.invoice', $id)->with('success', 'Pembayaran sudah diverifikasi.');
        }
        return view('pelanggan.upload-proof', compact('transaksi'));
    }

    public function uploadProof(Request $request, $id)
    {
        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($id);
        if ($transaksi->payment_method != 'transfer') {
            return redirect()->back()->with('error', 'Metode pembayaran bukan transfer.');
        }
        if ($transaksi->payment_status == 'verified') {
            return redirect()->route('pelanggan.invoice', $id)->with('success', 'Pembayaran sudah diverifikasi.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $transaksi->update([
            'payment_proof' => $path,
            'payment_status' => 'pending'
        ]);

        return redirect()->route('pelanggan.history')->with('success', 'Bukti transfer berhasil diupload. Menunggu verifikasi admin.');
    }

    // ======================= INVOICE / CETAK (dengan cek verifikasi) =======================
    public function invoice($id)
    {
        $transaksi = Transaksi::with(['details.produk', 'user'])->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) abort(403);

        if ($transaksi->payment_method == 'transfer' && $transaksi->payment_status != 'verified') {
            return redirect()->route('pelanggan.upload.proof.form', $id)
                             ->with('error', 'Silakan upload bukti transfer terlebih dahulu.');
        }

        return view('pelanggan.invoice', compact('transaksi'));
    }

    // ======================= RIWAYAT TRANSAKSI =======================
    public function history()
    {
        $transaksi = Transaksi::with('details.produk')
                               ->where('user_id', Auth::id())
                               ->orderBy('created_at', 'desc')
                               ->get();

        // Notifikasi untuk transaksi transfer yang baru diverifikasi (menggunakan session)
        $verifiedNotNotified = [];
        $notifiedIds = session('verified_notified_ids', []);
        foreach ($transaksi as $trx) {
            if ($trx->payment_method == 'transfer' && $trx->payment_status == 'verified' && !in_array($trx->id, $notifiedIds)) {
                $verifiedNotNotified[] = $trx->id;
            }
        }

        if (!empty($verifiedNotNotified)) {
            session()->put('verified_notified_ids', array_merge($notifiedIds, $verifiedNotNotified));
            session()->flash('verified_transactions', $verifiedNotNotified);
        }

        return view('pelanggan.history', compact('transaksi'));
    }

    // ======================= DETAIL TRANSAKSI PELANGGAN =======================
    public function pelangganDetail($id)
    {
        $transaksi = Transaksi::with(['details.produk', 'user'])->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) abort(403);
        return view('pelanggan.transaksi-detail', compact('transaksi'));
    }

    // ======================= ADMIN: INDEX =======================
    public function adminIndex()
    {
        $transaksi = Transaksi::with(['user', 'details.produk'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);
        return view('admin.transaksi.index', compact('transaksi'));
    }

    // ======================= ADMIN: SHOW DETAIL =======================
    public function adminShow($id)
    {
        $transaksi = Transaksi::with(['user', 'details.produk'])->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    // ======================= ADMIN: HAPUS =======================
    public function adminDestroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->details()->delete();
        $transaksi->delete();
        return redirect()->route('admin.transaksi.index')
                         ->with('success', 'Transaksi berhasil dihapus!');
    }

    // ======================= ADMIN: CETAK INVOICE =======================
    public function cetakInvoice($id)
    {
        $transaksi = Transaksi::with(['user', 'details.produk'])->findOrFail($id);
        return view('pelanggan.invoice', compact('transaksi'));
    }
}