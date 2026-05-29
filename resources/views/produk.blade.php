@extends('layouts.app')

@section('title', $keyword ? 'Hasil Pencarian: ' . $keyword : 'Semua Produk')

@section('content')

<style>
    .ribbon-promo {
        position: absolute; top: 12px; right: -35px;
        transform: rotate(45deg); z-index: 40;
        background: linear-gradient(135deg, #ff4d4d, #ff9966);
        color: #fff; font-size: .75rem; font-weight: bold;
        padding: .4rem 3rem;
        box-shadow: 0 4px 10px rgba(0,0,0,.25);
        border-radius: 4px; overflow: hidden;
    }
    .ribbon-promo::before {
        content: ""; position: absolute; top: 0; left: -50%;
        width: 200%; height: 100%;
        background: linear-gradient(120deg, rgba(255,255,255,.7) 0%, rgba(255,255,255,0) 60%);
        transform: skewX(-20deg);
        animation: shine 3s infinite;
    }
    @keyframes shine {
        0%   { transform: translateX(-150%) skewX(-20deg); }
        50%  { transform: translateX(150%)  skewX(-20deg); }
        100% { transform: translateX(-150%) skewX(-20deg); }
    }
    .attention-effect { animation: pulse .8s ease-in-out 0s 2; }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(251,126,60,.5); }
        50%       { box-shadow: 0 0 10px 5px rgba(251,126,60,.7); }
    }
    .product-card:hover .harga {
        transform: scale(1.1);
        color: #E56727;
    }
</style>

<section class="py-10 bg-[#FFF1E0] min-h-screen">
    <div class="container mx-auto px-4">

        <!-- {{-- ===================== SEARCH BAR ===================== --}}
        <div class="flex justify-center mb-8">
            <form method="GET" action="{{ route('produk.index') }}" class="flex">
                <input type="text" name="q"
                       placeholder="Cari produk..."
                       value="{{ $keyword ?? '' }}"
                       class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] w-64">
                <button type="submit"
                        class="px-4 bg-[#FB7E3C] text-white rounded-r-md hover:bg-[#E56727] transition">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        {{-- ===================== JUDUL ===================== --}}
        <h2 class="text-3xl font-bold text-center mb-6 text-[#6B3F2D]">
            {{ $keyword ? 'Hasil pencarian: ' . $keyword : 'Semua Produk' }}
        </h2>
 -->
        {{-- ===================== GRID PRODUK ===================== --}}
        @if($produk->isEmpty())
            <p class="text-center text-red-600 text-lg">Produk tidak ditemukan.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
@foreach($produk as $item)
<div id="produk-{{ $item->id }}"
     class="product-card relative bg-white rounded-lg shadow-md p-6 flex flex-col transition-transform transform hover:scale-105 border border-gray-200 hover:shadow-lg">

    {{-- Ribbon Promo --}}
    @if(in_array($item->id, $promoProduk))
        <div class="ribbon-promo">PROMO</div>
    @endif

    {{-- Foto --}}
    @if($item->foto)
        <div class="relative mb-4 overflow-hidden rounded">
            <img src="{{ asset('storage/' . $item->foto) }}"
                 alt="{{ $item->nama }}"
                 class="h-48 sm:h-56 md:h-64 w-full object-cover transition-transform duration-300 hover:scale-110">
        </div>
    @else
        <div class="h-48 sm:h-56 md:h-64 w-full bg-gray-200 flex items-center justify-center rounded mb-4 text-sm text-gray-500">
            Gambar tidak tersedia
        </div>
    @endif

    {{-- Nama --}}
    <h3 class="text-xl font-semibold mb-2 text-[#6B3F2D] hover:text-[#E56727] transition-colors duration-200">
        {{ $item->nama }}
    </h3>

    {{-- Deskripsi --}}
    <p class="mb-3 text-[15px] text-[#5C4033] leading-relaxed pl-4 border-l-4 border-[#E6B17E] italic">
        {{ Str::limit($item->deskripsi, 100) }}
    </p>

{{-- Harga & Aksi --}}
<div class="flex justify-between items-center mb-4 mt-auto">
    <p class="harga font-extrabold text-lg text-[#FB7E3C] drop-shadow-sm transition-transform duration-200">
        Rp{{ number_format($item->harga, 0, ',', '.') }}
    </p>
    <div class="flex space-x-2 items-center">
        {{-- Detail (mata) tetap oranye --}}
        <a href="{{ route('produk.show', $item->id) }}"
           class="text-[#FB7E3C] hover:text-white p-2 rounded-full hover:bg-[#FB7E3C] transition-all duration-200"
           title="Lihat detail">
            <i class="fas fa-eye text-xl"></i>
        </a>

        {{-- Tambah Keranjang warna hijau --}}
        @auth
            <div class="flex items-center gap-1">
                <button type="button"
                        class="tambah-keranjang text-green-600 hover:text-white p-2 rounded-full hover:bg-green-600 transition-all duration-200 transform hover:scale-110"
                        data-id="{{ $item->id }}"
                        data-qty="1"
                        title="Tambah ke keranjang">
                    <i class="fas fa-cart-plus text-xl"></i>
                </button>
                <span class="qty-display text-xs font-semibold bg-green-50 text-green-700 px-2 py-1 rounded-full min-w-[28px] text-center">
                    {{ session('cart')[$item->id] ?? 0 }}
                </span>
            </div>
        @else
            <a href="{{ route('login') }}"
               class="text-green-600 hover:text-white p-2 rounded-full hover:bg-green-600 transition-all duration-200 transform hover:scale-110"
               title="Login untuk menambah ke keranjang">
                <i class="fas fa-cart-plus text-xl"></i>
            </a>
        @endauth
    </div>
</div>
</div>
@endforeach          
  </div>
@endif

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Konfigurasi SweetAlert tema
    const swalTheme = {
        background: '#FFF9F0',
        color: '#5A2E1A',
        confirmButtonColor: '#FB7E3C',
        cancelButtonColor: '#6B7280',
        iconColor: '#FB7E3C'
    };

    // Tambah ke keranjang AJAX
    document.querySelectorAll('.tambah-keranjang').forEach(btn => {
        btn.addEventListener('click', function () {
            const id  = this.dataset.id;
            const qty = this.dataset.qty || 1;

            fetch(`/cart/add/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ qty: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update badge navbar
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.innerText = data.cart_count;
                        cartCount.classList.remove('hidden');
                    }

                    // Update qty di card
                    const card = document.querySelector(`#produk-${id}`);
                    if (card) {
                        const qtyEl = card.querySelector('.qty-display');
                        if (qtyEl) qtyEl.innerText = data.qty;
                    }

                    // Toast sukses
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '🍤 Masuk keranjang!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1800,
                        timerProgressBar: true,
                        ...swalTheme
                    });
                } else {
                    // Error dari server (misal stok habis)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!! 🥺',
                        text: data.message || 'Coba refresh atau cek stoknya ya.',
                        confirmButtonText: 'Okay!',
                        ...swalTheme
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal konek ⚡',
                    text: 'Cek koneksi internetmu, ya.',
                    confirmButtonText: 'Tutup',
                    ...swalTheme
                });
            });
        });
    });

    // Highlight produk promo
    const params = new URLSearchParams(window.location.search);
    const promoId = params.get('promo');
    if (promoId) {
        const target = document.querySelector(`#produk-${promoId}`);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            target.classList.add('attention-effect');
            setTimeout(() => target.classList.remove('attention-effect'), 2000);
        }
    }
});
</script>
@endpush