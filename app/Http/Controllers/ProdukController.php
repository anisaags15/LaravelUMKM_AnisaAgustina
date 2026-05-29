<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('q');
        
        $produk = Produk::when($keyword, function($query, $keyword) {
                        $query->where('nama', 'LIKE', "%{$keyword}%")
                              ->orWhere('deskripsi', 'LIKE', "%{$keyword}%");
                    })->get();

        // Tambahkan ID promo yang baru (19,13,20) ke dalam array
        $promoProduk = [19, 13, 20, 39, 27, 34]; 

        return view('produk', compact('produk', 'keyword', 'promoProduk'));
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('detail', compact('produk'));
    }

    public function kategori($slug)
    {
        // Mapping slug ke ID kategori
        $mapKategori = [
            'dimsum_kukus'   => 1,
            'pangsit'        => 2,
            'dimsum_spesial' => 3
        ];

        if (!isset($mapKategori[$slug])) {
            return redirect()->route('produk.index');
        }

        $idKategori = $mapKategori[$slug];

        // REVISI DI SINI: Menggunakan 'kategori_id' sesuai database kamu
        $produk = Produk::where('kategori_id', $idKategori)
                        ->with('kategori')
                        ->get();

        $kategoriData = Kategori::find($idKategori);
        $namaKategori = $kategoriData ? $kategoriData->nama_kategori : 'Kategori';

        $categories = [
            'dimsum_kukus' => 'Dimsum Kukus',
            'pangsit' => 'Pangsit',
            'dimsum_spesial' => 'Dimsum Spesial'
        ];

        return view('kategori', compact('produk', 'namaKategori', 'slug', 'categories'));
    }
}