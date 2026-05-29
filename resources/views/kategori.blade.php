@extends('layouts.app')

@section('title', 'Kategori ' . $namaKategori)

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
    .product-card:hover .harga {
        transform: scale(1.1);
        color: #E56727;
    }
    .attention-effect { animation: pulse .8s ease-in-out 0s 2; }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(251,126,60,.5); }
        50%       { box-shadow: 0 0 10px 5px rgba(251,126,60,.7); }
    }
</style>

<main class="container mx-auto px-4 py-10 bg-[#FFF1E0] min-h-screen">
    {{-- Judul --}}
    <h1 class="text-2xl md:text-3xl font-bold text-center text-[#6B3F2D] mb-8">
        <span class="inline-block pb-1 border-b-2 border-[#FB7E3C]">
            Produk Kategori: {{ ucwords($namaKategori) }}
        </span>
    </h1>

    @if($produk->isEmpty())
        <div class="text-center py-20">
            <p class="text-red-600 text-lg">Produk kategori ini belum tersedia.</p>
            <a href="{{ route('produk.index') }}" class="mt-4 inline-block text-[#FB7E3C] hover:underline">Lihat semua produk</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($produk as $item)
            <div id="produk-{{ $item->id }}"
                 class="product-card relative bg-white rounded-lg shadow-md p-6 flex flex-col transition-transform transform hover:scale-105 border border-gray-200 hover:shadow-lg">

                {{-- Ribbon Promo (jika termasuk promo) --}}
                @if(isset($promoProduk) && in_array($item->id, $promoProduk))
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
                    {{ $item->deskripsi }}
                </p>

                {{-- Harga & Aksi --}}
                <div class="flex justify-between items-center mb-4 mt-auto">
                    <p class="harga font-extrabold text-lg text-[#FB7E3C] drop-shadow-sm transition-transform duration-200">
                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                    <div class="flex space-x-2 items-center">
                        {{-- Detail --}}
                        <a href="{{ route('produk.show', $item->id) }}"
                           class="text-[#FB7E3C] hover:text-[#E56727] p-2 rounded-full hover:bg-[#FB7E3C] hover:bg-opacity-10 transition">
                            <i class="fas fa-eye text-xl"></i>
                        </a>

                        {{-- Tambah Keranjang --}}
                        @auth
                            <button type="button"
                                    class="tambah-keranjang text-green-500 hover:text-green-600 p-2 rounded-full hover:bg-green-500 hover:bg-opacity-10 transition"
                                    data-id="{{ $item->id }}"
                                    data-qty="1">
                                <i class="fas fa-cart-plus text-xl"></i>
                            </button>
                            <span class="text-sm font-semibold text-[#5C4033] qty-display">
                                {{ session('cart')[$item->id] ?? 0 }}
                            </span>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-green-500 hover:text-green-600 p-2 rounded-full hover:bg-green-500 hover:bg-opacity-10 transition">
                                <i class="fas fa-cart-plus text-xl"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===================== KONFIGURASI TEMA =====================
    const swalTheme = {
        background: '#FFF9F0',
        color: '#5A2E1A',
        confirmButtonColor: '#FB7E3C',
        cancelButtonColor: '#6B7280',
        iconColor: '#FB7E3C'
    };

    // ===================== TAMBAH KERANJANG AJAX =====================
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
                    // Update badge cart di navbar
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.innerText = data.cart_count;
                        cartCount.classList.remove('hidden');
                    }

                    // Update qty di card produk
                    const card = document.querySelector(`#produk-${id}`);
                    if (card) {
                        const qtyEl = card.querySelector('.qty-display');
                        if (qtyEl) qtyEl.innerText = data.qty;
                    }

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
                    Swal.fire({
                        icon: 'error',
                        title: 'oopss!! 🥺',
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
                    title: '😖 Error koneksi',
                    text: 'Internetmu aman? Coba lagi ya.',
                    confirmButtonText: 'Tutup',
                    ...swalTheme
                });
            });
        });
    });

    // ===================== HIGHLIGHT PRODUK PROMO (opsional) =====================
    const params  = new URLSearchParams(window.location.search);
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