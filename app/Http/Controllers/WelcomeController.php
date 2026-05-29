<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil produk unggulan berdasarkan is_featured = true
        $produkUnggulan = Produk::where('is_featured', true)
                                ->latest()
                                ->take(6)
                                ->get();

        return view('welcome', compact('produkUnggulan'));
    }
}