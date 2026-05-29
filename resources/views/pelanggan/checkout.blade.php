@extends('layouts.app')

@section('title', 'Checkout - DimsumYummy')

@section('content')
<style>
    .checkout-step {
        transition: all 0.2s ease;
    }
    .step-active {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .step-completed {
        background-color: #e5e7eb;
        color: #4b5563;
    }
</style>

<div class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Progress Step --}}
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center space-x-4 md:space-x-8">
                <div class="flex items-center">
                    <div class="checkout-step step-completed w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">1</div>
                    <span class="ml-2 text-sm font-medium text-stone-500">Keranjang</span>
                </div>
                <div class="w-12 h-0.5 bg-stone-300"></div>
                <div class="flex items-center">
                    <div class="checkout-step step-active w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                    <span class="ml-2 text-sm font-bold text-orange-600">Checkout</span>
                </div>
                <div class="w-12 h-0.5 bg-stone-300"></div>
                <div class="flex items-center">
                    <div class="checkout-step step-completed w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                    <span class="ml-2 text-sm font-medium text-stone-500">Selesai</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">

            {{-- KOLOM KIRI: RINGKASAN PESANAN --}}
            <div class="lg:w-2/5 space-y-4">
                <div class="bg-white rounded-2xl shadow-lg p-5 border border-orange-100 sticky top-6">
                    <h2 class="font-bold text-stone-800 border-b border-orange-200 pb-3 mb-3 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-orange-500"></i> Ringkasan Pesanan
                    </h2>

                    {{-- Daftar produk --}}
                    <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1 mb-3">
                        @foreach($items as $item)
                        <div class="flex gap-3">
                            <img src="{{ asset('storage/' . $item['produk']->foto) }}"
                                 alt="{{ $item['produk']->nama }}"
                                 class="w-12 h-12 object-cover rounded-xl shadow-sm ring-1 ring-orange-200">
                            <div class="flex-1">
                                <p class="font-semibold text-stone-700 text-sm">{{ $item['produk']->nama }}</p>
                                <p class="text-orange-600 text-xs">{{ $item['qty'] }} x Rp {{ number_format($item['produk']->harga, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-orange-700 text-sm">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Rincian biaya --}}
                    <div class="border-t border-orange-200 pt-3 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-stone-500">Subtotal</span>
                            <span class="font-semibold text-stone-700">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-500 flex items-center gap-1">
                                <i class="fas fa-truck text-orange-400 text-xs"></i> Ongkos Kirim
                            </span>
                            <span class="font-semibold text-stone-700">Rp 0</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-orange-200">
                            <span class="font-bold text-stone-800">Total</span>
                            <span class="text-orange-600 text-xl font-black">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-stone-400 mt-2 italic">* Tidak perlu membayar ongkos kirim ke kurir.</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM CHECKOUT --}}
            <div class="lg:w-3/5">
                <form action="{{ route('pelanggan.checkout.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-6 border border-orange-100 space-y-5">
                    @csrf

                    {{-- Data Pemesan --}}
                    <div>
                        <h3 class="font-bold text-stone-800 flex items-center gap-2 mb-3">
                            <i class="fas fa-user text-orange-500"></i> Data Pemesan
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-stone-600 mb-1">Nama Lengkap</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-sm"></i>
                                    <input type="text" name="nama"
                                           value="{{ old('nama', Auth::user()->nama ?? Auth::user()->name) }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-stone-200 rounded-xl focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition"
                                           placeholder="Masukkan nama lengkap">
                                </div>
                                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-600 mb-1">No. HP</label>
                                <div class="relative">
                                    <i class="fas fa-phone-alt absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-sm"></i>
                                    <input type="tel" name="no_hp"
                                           value="{{ old('no_hp') }}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-stone-200 rounded-xl focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition"
                                           placeholder="08xxxxxxxxxx">
                                </div>
                                @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stone-600 mb-1">Alamat Lengkap</label>
                                <div class="relative">
                                    <i class="fas fa-map-marker-alt absolute left-3 top-3 text-stone-400 text-sm"></i>
                                    <textarea name="alamat" rows="2"
                                              class="w-full pl-10 pr-4 py-2.5 border border-stone-200 rounded-xl focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition resize-none"
                                              placeholder="Jalan, RT/RW, kelurahan, kecamatan, kota">{{ old('alamat') }}</textarea>
                                </div>
                                @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                 {{-- Metode Pembayaran & Pengiriman --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    {{-- Pembayaran (dropdown) --}}
    <div>
        <label class="block text-sm font-medium text-stone-600 mb-1 flex items-center gap-1">
            <i class="fas fa-credit-card text-orange-400"></i> Pembayaran
        </label>
        <select name="payment_method"
                class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition">
            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>💵 COD (Bayar di Tempat)</option>
            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank (BCA/BNI/Mandiri)</option>
        </select>
        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Pengiriman (tetap JNE, bisa pilih atau statis?) --}}
    <div>
        <label class="block text-sm font-medium text-stone-600 mb-1 flex items-center gap-1">
            <i class="fas fa-shipping-fast text-orange-400"></i> Pengiriman
        </label>
        @php
            // Anda bisa tetap pakai dropdown atau hidden. Biarkan dropdown agar user bisa pilih.
            // Tapi jika ingin hanya JNE, ubah jadi statis seperti sebelumnya.
        @endphp
<select name="shipping_method" class="...">
    <option value="reguler" {{ old('shipping_method') == 'reguler' ? 'selected' : '' }}>📦 Reguler (Gratis) - Estimasi 3-4 hari</option>
    <option value="ekspres" {{ old('shipping_method') == 'ekspres' ? 'selected' : '' }}>⚡ Ekspres (Gratis) - Estimasi 1-2 hari</option>
</select>        @error('shipping_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

                    {{-- Catatan Pesanan (opsional) --}}
                    <div>
                        <label class="block text-sm font-medium text-stone-600 mb-1 flex items-center gap-1">
                            <i class="fas fa-pen text-orange-400"></i> Catatan untuk Penjual (opsional)
                        </label>
                        <textarea name="notes" rows="2"
                                  class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition resize-none"
                                  placeholder="Contoh: Tolong tambah sambal, atau jangan pakai plastik berlebih...">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-wrap justify-end gap-3 pt-2">
                        <a href="{{ route('pelanggan.cart.index') }}"
                           class="px-5 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Keranjang
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2 transform hover:scale-105">
                            <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@if($errors->any())
    Swal.fire({
        icon: 'warning',
        title: 'Periksa Form!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonColor: '#f97316',
        background: '#fff9f0',
        color: '#4a3b2c',
    });
@endif
</script>
@endpush