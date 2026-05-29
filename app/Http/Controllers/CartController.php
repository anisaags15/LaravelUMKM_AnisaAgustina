<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart       = session('cart', []);
        $products   = [];
        $grandTotal = 0;

        if (!empty($cart)) {
            $produkList = Produk::whereIn('id', array_keys($cart))->get()->keyBy('id');

            foreach ($cart as $id => $qty) {
                if ($produkList->has($id)) {
                    $produk    = $produkList[$id];
                    $subtotal  = $produk->harga * $qty;
                    $grandTotal += $subtotal;

                    $products[] = [
                        'produk'   => $produk,
                        'qty'      => $qty,
                        'subtotal' => $subtotal,
                        'id'       => $id,
                    ];
                }
            }
        }

        return view('pelanggan.keranjang', compact('products', 'grandTotal', 'cart'));
    }

    public function add(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $qtyToAdd = max(1, (int) $request->get('qty', 1));
        $cart = session('cart', []);
        $currentQty = $cart[$id] ?? 0;
        $newQty = $currentQty + $qtyToAdd;

        if ($newQty > $produk->stok) {
            return response()->json([
                'status'  => 'error',
                'message' => "Stok tidak cukup. Stok tersedia: {$produk->stok}",
            ], 422);
        }

        $cart[$id] = $newQty;
        session(['cart' => $cart]);

        return response()->json([
            'status'     => 'success',
            'message'    => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => count($cart),
            'qty'        => $cart[$id],
        ]);
    }

    public function update(Request $request)
    {
        $id     = $request->id;
        $action = $request->action; // 'plus' atau 'minus'
        $cart   = session('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk tidak ditemukan di keranjang.',
            ], 404);
        }

        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $currentQty = $cart[$id];
        $newQty = $currentQty;

        if ($action === 'plus') {
            $newQty = $currentQty + 1;
            if ($newQty > $produk->stok) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Stok tidak cukup. Maksimal {$produk->stok}",
                ], 422);
            }
        } elseif ($action === 'minus') {
            $newQty = $currentQty - 1;
            if ($newQty <= 0) {
                unset($cart[$id]);
                session(['cart' => $cart]);

                $grandTotal = 0;
                if (!empty($cart)) {
                    $produkList = Produk::whereIn('id', array_keys($cart))->get()->keyBy('id');
                    foreach ($cart as $pid => $qty) {
                        if ($produkList->has($pid)) {
                            $grandTotal += $produkList[$pid]->harga * $qty;
                        }
                    }
                }
                return response()->json([
                    'status'      => 'success',
                    'cart_count'  => count($cart),
                    'qty'         => 0,
                    'grand_total' => $grandTotal,
                ]);
            }
        }

        $cart[$id] = $newQty;
        session(['cart' => $cart]);

        $grandTotal = 0;
        if (!empty($cart)) {
            $produkList = Produk::whereIn('id', array_keys($cart))->get()->keyBy('id');
            foreach ($cart as $pid => $qty) {
                if ($produkList->has($pid)) {
                    $grandTotal += $produkList[$pid]->harga * $qty;
                }
            }
        }

        return response()->json([
            'status'      => 'success',
            'cart_count'  => count($cart),
            'qty'         => $cart[$id],
            'grand_total' => $grandTotal,
        ]);
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return redirect()->route('pelanggan.cart.index')
                         ->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}