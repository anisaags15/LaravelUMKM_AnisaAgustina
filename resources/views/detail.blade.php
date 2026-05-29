@extends('layouts.app')

@section('title', $produk->nama . ' - Detail Produk')

@section('content')
<div class="bg-[#FFF1E0] min-h-screen text-[#6B3F2D] font-sans">
    <!-- Detail Produk Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="bg-gradient-to-b from-white via-[#FFF8F3] to-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2 gap-6 p-6 hover:shadow-2xl transition duration-500 max-w-4xl mx-auto">

                <!-- Gambar Produk -->
                <div class="flex items-center justify-center bg-[#FFF8F3] rounded-2xl p-3 transform hover:scale-105 transition duration-500">
                    @if($produk->foto)
                        <img src="{{ asset('storage/' . $produk->foto) }}" 
                             alt="{{ $produk->nama }}" 
                             class="rounded-2xl shadow-md w-full max-h-[350px] object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg">Gambar tidak tersedia</div>
                    @endif
                </div>

                <!-- Info Produk -->
                <div class="flex flex-col justify-between px-2 md:px-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mb-2 md:mb-3">{{ $produk->nama }}</h1>
                        <p class="text-[#FB7E3C] text-2xl md:text-3xl font-semibold mb-4">Rp{{ number_format($produk->harga, 0, ',', '.') }}</p>

                        {{-- STOK BADGE --}}
                        @php
                            $stok = $produk->stok;
                            $stokClass = $stok <= 0 ? 'bg-red-100 text-red-700' : ($stok <= 5 ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700');
                        @endphp
                        <div class="mb-3">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold {{ $stokClass }}">
                                <i class="fas fa-boxes"></i>
                                Stok: 
                                @if($stok <= 0)
                                    Habis
                                @else
                                    {{ $stok }} tersisa
                                @endif
                            </span>
                        </div>

                        <p class="mb-3 text-[15px] text-[#5C4033] leading-relaxed relative pl-4 border-l-4 border-[#E6B17E] italic">
                            {!! nl2br(e($produk->deskripsi)) !!}
                        </p>
                    </div>

                    <!-- Qty + Tombol -->
                    <div class="flex items-center gap-3 mt-4 flex-wrap w-full">
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden {{ $stok <= 0 ? 'opacity-50 pointer-events-none' : '' }}">
                            <button type="button" id="decreaseQty" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-lg font-bold">-</button>
                            <input type="text" id="quantity" value="1" readonly class="w-12 text-center border-x border-gray-300 bg-white font-semibold">
                            <button type="button" id="increaseQty" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-lg font-bold">+</button>
                        </div>

                        @if($stok <= 0)
                            <button type="button" disabled
                                class="flex-1 flex items-center justify-center gap-2 bg-gray-400 text-white px-4 py-2 rounded-xl shadow-md cursor-not-allowed font-medium text-base">
                                <i class="fas fa-ban"></i> Stok Habis
                            </button>
                        @else
                            <button type="button" id="addToCartBtn" data-id="{{ $produk->id }}"
                                class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white px-4 py-2 rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 font-medium text-base">
                                <i class="fas fa-cart-plus"></i> Tambah
                            </button>
                        @endif

                        <a href="{{ url('/produk') }}" class="flex items-center justify-center gap-2 border-2 border-[#FB7E3C] text-[#FB7E3C] px-4 py-2 rounded-xl hover:bg-[#FB7E3C] hover:text-white transition-all duration-300 font-medium text-base">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tema hangat DimsumYummy
    const swalTheme = {
        background: '#FFF9F0',      // krem
        color: '#5A2E1A',           // coklat tua
        confirmButtonColor: '#FB7E3C',
        cancelButtonColor: '#6B7280',
        iconColor: '#FB7E3C'
    };

    const qtyInput = document.getElementById('quantity');
    const maxStock = {{ $produk->stok }};
    const isStockAvailable = maxStock > 0;

    function updateQuantityButtons() {
        const val = parseInt(qtyInput.value);
        const inc = document.getElementById('increaseQty');
        const dec = document.getElementById('decreaseQty');
        if (inc) inc.disabled = (val >= maxStock);
        if (dec) dec.disabled = (val <= 1);
    }

    if (document.getElementById('increaseQty')) {
        document.getElementById('increaseQty').addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val < maxStock) {
                qtyInput.value = val + 1;
                updateQuantityButtons();
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Stok habis 🥟',
                    text: `Dimsum ini cuma tersisa ${maxStock}. Kurangi jumlahnya ya.`,
                    ...swalTheme,
                    confirmButtonText: 'Mengerti'
                });
            }
        });
    }

    if (document.getElementById('decreaseQty')) {
        document.getElementById('decreaseQty').addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) {
                qtyInput.value = val - 1;
                updateQuantityButtons();
            }
        });
    }

    if (isStockAvailable) updateQuantityButtons();

    const addBtn = document.getElementById('addToCartBtn');
    if (addBtn && isStockAvailable) {
        const isLoggedIn = @json(Auth::check());

        addBtn.addEventListener('click', function() {
            if (!isLoggedIn) {
                window.location.href = "{{ route('login') }}";
                return;
            }

            const productId = this.dataset.id;
            const qty = parseInt(qtyInput.value);

            if (qty > maxStock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kebanyakan 🫢',
                    text: `Stok cuma ${maxStock}. Kurangi belinya biar semua kebagian.`,
                    ...swalTheme,
                    confirmButtonText: 'Oke'
                });
                return;
            }

            const url = "{{ route('pelanggan.cart.add', ':id') }}".replace(':id', productId);
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ qty: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Masuk keranjang! 🛒',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000,
                        background: swalTheme.background,
                        color: swalTheme.color,
                        iconColor: swalTheme.iconColor
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'oopss!! 🥺',
                        text: data.message || 'Coba refresh atau cek stoknya ya.',
                        ...swalTheme,
                        confirmButtonText: 'Okay!'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error koneksi ⚡',
                    text: 'Dimsum gagal masuk keranjang. Cek internetmu.',
                    ...swalTheme,
                    confirmButtonText: 'Tutup'
                });
            });
        });
    }
</script>
@endpush