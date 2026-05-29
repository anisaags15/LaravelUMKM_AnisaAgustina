@extends('layouts.app')

@section('title', 'Keranjang Belanja - DimsumYummy')

@section('content')
<style>
    .cart-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cart-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    .quantity-btn {
        transition: all 0.2s ease;
    }
    .quantity-btn:active {
        transform: scale(0.92);
    }
    .checkout-btn {
        background: linear-gradient(135deg, #f97316, #ea580c);
        box-shadow: 0 4px 14px 0 rgba(249, 115, 22, 0.4);
    }
    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px 0 rgba(249, 115, 22, 0.5);
    }
    @keyframes fadeSlide {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeSlide {
        animation: fadeSlide 0.4s ease-out;
    }
</style>

<div class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-5xl">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="h-10 w-1.5 bg-gradient-to-b from-orange-500 to-orange-700 rounded-full"></div>
            <h1 class="text-3xl font-black text-stone-800 tracking-tight">
                🛒 Keranjang Belanjamu
            </h1>
            <span class="ml-auto text-sm text-stone-500 bg-white/60 backdrop-blur px-3 py-1 rounded-full shadow-sm">
                {{ count($products) }} item
            </span>
        </div>

        @if(empty($products))
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-12 text-center border border-orange-100 animate-fadeSlide">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-cart text-orange-400 text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-stone-700">Keranjang kosong</h2>
                <p class="text-stone-500 mt-1">Yuk, tambahkan dimsum favoritmu!</p>
                <a href="{{ route('produk.index') }}"
                   class="inline-block mt-6 px-6 py-3 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 transition shadow-md hover:shadow-lg">
                    <i class="fas fa-store mr-2"></i> Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4 animate-fadeSlide">
                @foreach($products as $item)
                <div id="cart-item-{{ $item['id'] }}"
                     class="cart-item bg-white rounded-2xl shadow-md overflow-hidden border border-orange-100">
                    <div class="p-5 flex flex-wrap items-center gap-4">
                        <div class="w-20 h-20 flex-shrink-0">
                            <img src="{{ asset('storage/' . $item['produk']->foto) }}"
                                 alt="{{ $item['produk']->nama }}"
                                 class="w-full h-full object-cover rounded-xl shadow-sm ring-1 ring-orange-200">
                        </div>

                        <div class="flex-1 min-w-[150px]">
                            <h3 class="font-bold text-stone-800 text-lg">{{ $item['produk']->nama }}</h3>
                            <p class="text-orange-600 font-semibold text-sm mt-0.5">
                                Rp {{ number_format($item['produk']->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-4 ml-auto">
                            <div class="flex items-center gap-2 bg-stone-100 rounded-full px-1 py-1 shadow-inner">
                                <button onclick="updateCart({{ $item['id'] }}, 'minus')"
                                        class="quantity-btn w-8 h-8 rounded-full bg-white text-orange-500 hover:bg-orange-500 hover:text-white transition shadow-sm flex items-center justify-center font-bold text-lg">
                                    −
                                </button>
                                <span id="qty-{{ $item['id'] }}" class="min-w-[32px] text-center font-semibold text-stone-700">
                                    {{ $item['qty'] }}
                                </span>
                                <button onclick="updateCart({{ $item['id'] }}, 'plus')"
                                        class="quantity-btn w-8 h-8 rounded-full bg-white text-orange-500 hover:bg-orange-500 hover:text-white transition shadow-sm flex items-center justify-center font-bold text-lg">
                                    +
                                </button>
                            </div>

                            <div class="w-28 text-right">
                                <span id="subtotal-{{ $item['id'] }}" class="font-bold text-orange-700 text-base">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <button onclick="hapusItem({{ $item['id'] }})"
                                    class="text-stone-400 hover:text-red-500 transition text-sm p-2 hover:bg-red-50 rounded-full">
                                <i class="fas fa-trash-alt text-base"></i>
                            </button>
                        </div>

                        <form id="hapus-form-{{ $item['id'] }}"
                              action="{{ route('pelanggan.cart.remove', $item['id']) }}"
                              method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="sticky bottom-4 mt-6 bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-orange-200 p-5 flex flex-wrap items-center justify-between gap-3 animate-fadeSlide">
                <div class="flex items-baseline gap-2">
                    <span class="text-stone-500 text-sm">Total pesanan:</span>
                    <span id="grand-total" class="text-2xl font-black text-orange-600 tracking-tight">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </span>
                </div>
                <a href="{{ route('pelanggan.checkout.store') }}"
                   class="checkout-btn flex items-center gap-2 px-8 py-3 bg-orange-500 text-white rounded-xl font-bold text-sm transition hover:shadow-lg active:scale-95">
                    <i class="fas fa-arrow-right"></i> Checkout Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';

function updateCart(id, action) {
    fetch('{{ route('pelanggan.cart.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: id, action: action })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const qtyEl = document.getElementById(`qty-${id}`);
            const subtotalEl = document.getElementById(`subtotal-${id}`);
            const cartBadge = document.getElementById('cart-count');
            const grandTotalSpan = document.getElementById('grand-total');

            if (data.qty <= 0) {
                document.getElementById(`cart-item-${id}`)?.remove();
                if (data.cart_count === 0) location.reload();
                else updateGrandTotalDisplay(data.grand_total);
                return;
            }

            if (qtyEl) qtyEl.innerText = data.qty;

            const priceText = document.querySelector(`#cart-item-${id} .text-orange-600`)?.innerText || '0';
            const price = parseInt(priceText.replace(/[^0-9]/g, '')) || 0;
            const newSubtotal = price * data.qty;
            if (subtotalEl) subtotalEl.innerText = formatRupiah(newSubtotal);

            if (cartBadge) {
                cartBadge.innerText = data.cart_count;
                if (data.cart_count === 0) cartBadge.classList.add('hidden');
                else cartBadge.classList.remove('hidden');
            }

            updateGrandTotalDisplay(data.grand_total);
        } else {
            // Tampilkan error dari server (misal stok habis)
            Swal.fire({
                icon: 'error',
                title: 'Oops!! 🥺',
                text: data.message || 'Terjadi kesalahan',
                background: '#fff9f0',
                color: '#4a3b2c',
                confirmButtonColor: '#f97316'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal menghubungi server',
            background: '#fff9f0',
            color: '#4a3b2c',
            confirmButtonColor: '#f97316'
        });
    });
}

function updateGrandTotalDisplay(grandTotal) {
    const grandSpan = document.getElementById('grand-total');
    if (grandSpan) grandSpan.innerText = formatRupiah(grandTotal);
}

function formatRupiah(angka) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
}

function hapusItem(id) {
    Swal.fire({
        title: 'Yakin hapus?',
        text: "Item akan dihapus dari keranjang",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        background: '#fff9f0',
        color: '#4a3b2c',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`hapus-form-${id}`).submit();
        }
    });
}

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 1500,
        showConfirmButton: false,
        background: '#fff9f0',
        color: '#4a3b2c',
    });
@endif
</script>
@endpush