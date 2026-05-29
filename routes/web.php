<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\KategoriController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ======================= PUBLIC ROUTES =======================

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/tentang', fn() => view('tentang'))->name('tentang');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])->name('kontak.store');
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');
Route::get('/kategori/{slug}', [ProdukController::class, 'kategori'])->name('kategori.show');

// ======================= REDIRECT SETELAH LOGIN =======================
Route::get('/redirect-after-login', function () {
    $role = Auth::user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('pelanggan.home');
})->middleware('auth')->name('redirect.after.login');

// ======================= ADMIN ROUTES =======================
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard (statistik)
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Produk - Data Barang (index tabel)
        Route::get('/produk', [AdminController::class, 'produkIndex'])->name('produk.index');

        // Produk - Tambah (create)
        Route::get('/produk/create', [AdminController::class, 'produkCreate'])->name('produk.create');
        Route::post('/produk', [AdminController::class, 'produkStore'])->name('produk.store');

        // Produk - Edit & Update & Delete
        Route::get('/produk/{id}/edit', [AdminController::class, 'produkEdit'])->name('produk.edit');
        Route::put('/produk/{id}', [AdminController::class, 'produkUpdate'])->name('produk.update');
        Route::delete('/produk/{id}', [AdminController::class, 'produkDestroy'])->name('produk.destroy');

        // Kategori
        Route::resource('kategori', KategoriController::class);

        // Transaksi
        Route::get('/transaksi', [TransaksiController::class, 'adminIndex'])->name('transaksi.index');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'adminShow'])->name('transaksi.show');
        Route::delete('/transaksi/{id}', [TransaksiController::class, 'adminDestroy'])->name('transaksi.destroy');
        Route::get('/transaksi/{id}/invoice', [TransaksiController::class, 'cetakInvoice'])->name('transaksi.invoice');
        
        // Verifikasi pembayaran transfer
        Route::post('/transaksi/{id}/verify', [AdminController::class, 'verifyPayment'])->name('transaksi.verify');
        Route::post('/transaksi/{id}/reject', [AdminController::class, 'rejectPayment'])->name('transaksi.reject');        

        // Pelanggan
        Route::get('/pelanggan', [AdminController::class, 'pelangganIndex'])->name('pelanggan.index');
        Route::post('/pelanggan/reset/{id}', [AdminController::class, 'resetPassword'])->name('pelanggan.reset');
        Route::delete('/pelanggan/destroy/{id}', [AdminController::class, 'pelangganDestroy'])->name('pelanggan.destroy');

        // Pesan Masuk
        Route::get('/pesan', [AdminController::class, 'pesanIndex'])->name('pesan.index');
        Route::delete('/pesan/{id}', [AdminController::class, 'pesanDestroy'])->name('pesan.destroy');

        // Profil Admin
        Route::get('/profil', [AdminProfileController::class, 'show'])->name('profil.show');
        Route::post('/profil/foto', [AdminProfileController::class, 'uploadFoto'])->name('profil.foto');
        Route::patch('/profil', [AdminProfileController::class, 'update'])->name('profil.update');
        Route::patch('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profil.password');
    });

// ======================= PELANGGAN ROUTES =======================
Route::middleware(['auth', 'role:pelanggan'])
    ->name('pelanggan.')
    ->group(function () {

    // Home pelanggan (sama seperti welcome tapi sudah login)
    Route::get('/home', [WelcomeController::class, 'index'])->name('home');

    // Cart
    Route::get('/cart',                [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}',      [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update',        [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout',            [TransaksiController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',           [TransaksiController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{id}', [TransaksiController::class, 'success'])->name('checkout.success');

    // Invoice & History
    Route::get('/invoice/{id}',        [TransaksiController::class, 'invoice'])->name('invoice');
    Route::get('/history',             [TransaksiController::class, 'history'])->name('history');
    Route::get('/transaksi/{id}',      [TransaksiController::class, 'pelangganDetail'])->name('transaksi.detail');

    // Upload bukti transfer
    Route::get('/transaksi/{id}/upload', [TransaksiController::class, 'showUploadForm'])->name('upload.proof.form');
    Route::post('/transaksi/{id}/upload', [TransaksiController::class, 'uploadProof'])->name('upload.proof');

    // Profil
    Route::get('/profil',              [ProfileController::class, 'show'])->name('profil.show');
    Route::get('/profil/edit',         [ProfileController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil',            [ProfileController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password',   [ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::post('/profil/foto',        [ProfileController::class, 'uploadFoto'])->name('profil.foto');
    Route::delete('/profil',           [ProfileController::class, 'destroy'])->name('profil.destroy');
});
require __DIR__.'/auth.php';