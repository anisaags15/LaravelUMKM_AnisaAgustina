@extends('admin.layouts.app')

@section('title', 'Detail Transaksi #' . $transaksi->id)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1.5 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#2C1A12]">
                    📄 Detail Transaksi #{{ $transaksi->id }}
                </h1>
            </div>
            <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                <i class="fas fa-receipt text-[#FB7E3C] text-xs"></i>
                Informasi lengkap pesanan
            </p>
        </div>
        <a href="{{ route('admin.transaksi.index') }}"
           class="px-5 py-3 bg-[#FB7E3C] text-white rounded-2xl font-bold text-sm hover:bg-[#D95C1A] transition-all shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    {{-- GRID INFO: Pelanggan & Transaksi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    </div>

    {{-- ======================= VERIFIKASI PEMBAYARAN (untuk transfer) ======================= --}}
    @if($transaksi->payment_method == 'transfer')
    <div id="verification" class="bg-white rounded-3xl shadow-lg border border-stone-100 p-6 mb-6 transition hover:shadow-xl">
        <div class="flex items-center gap-3 mb-4 pb-2 border-b border-stone-100">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white shadow">
                <i class="fas fa-check-circle text-sm"></i>
            </div>
            <h2 class="text-lg font-black text-stone-800">Verifikasi Pembayaran</h2>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-stone-500">Status saat ini:</p>
                    @if($transaksi->payment_status == 'verified')
                        <span class="inline-block mt-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">✅ Sudah Diverifikasi</span>
                        @if($transaksi->confirmed_by)
                            <p class="text-xs text-stone-500 mt-2">Diverifikasi oleh: {{ $transaksi->confirmer->name ?? '-' }} pada {{ \Carbon\Carbon::parse($transaksi->confirmed_at)->format('d M Y H:i') }}</p>
                        @endif
                    @elseif($transaksi->payment_status == 'rejected')
                        <span class="inline-block mt-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">❌ Ditolak</span>
                    @else
                        <span class="inline-block mt-1 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">⏳ Menunggu Verifikasi</span>
                    @endif
                </div>

                @if($transaksi->payment_proof)
                <div>
                    <p class="text-sm text-stone-500">Bukti Transfer:</p>
                    <a href="{{ asset('storage/' . $transaksi->payment_proof) }}" target="_blank" class="inline-flex items-center gap-2 mt-1 text-orange-600 hover:text-orange-700">
                        <i class="fas fa-image"></i> Lihat Bukti Transfer
                    </a>
                </div>
                @endif
            </div>

            @if($transaksi->payment_status == 'pending')
            <div class="flex justify-end gap-3 pt-2 border-t border-stone-100">
                <button onclick="verifyPayment({{ $transaksi->id }}, 'reject')"
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold text-sm shadow transition">
                    ❌ Tolak Pembayaran
                </button>
                <button onclick="verifyPayment({{ $transaksi->id }}, 'verify')"
                        class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm shadow transition">
                    ✅ Verifikasi Pembayaran
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- CARD DETAIL PRODUK --}}
    ... (sama seperti sebelumnya) ...

    {{-- TOMBOL AKSI --}}
    <div class="flex flex-wrap justify-end gap-3">
        <a href="{{ route('admin.transaksi.invoice', $transaksi->id) }}" target="_blank"
           class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-bold text-sm shadow-md transition hover:scale-105">
            <i class="fas fa-print text-xs"></i> Cetak Invoice
        </a>
        <button onclick="confirmDelete({{ $transaksi->id }})"
                class="flex items-center gap-2 px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-sm shadow-md transition hover:scale-105">
            <i class="fas fa-trash-alt text-xs"></i> Hapus Transaksi
        </button>
        <form id="delete-form-{{ $transaksi->id }}"
              action="{{ route('admin.transaksi.destroy', $transaksi->id) }}"
              method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: 'Transaksi ini akan dihapus permanen beserta semua detailnya!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E11D48',
        cancelButtonColor: '#78716C',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        background: '#FFFCF9',
        color: '#2C1A12'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

function verifyPayment(id, action) {
    let title, text, confirmText, url;
    if (action === 'verify') {
        title = 'Verifikasi Pembayaran?';
        text = 'Setelah diverifikasi, pelanggan dapat mencetak invoice.';
        confirmText = 'Ya, Verifikasi';
        url = "{{ route('admin.transaksi.verify', ':id') }}".replace(':id', id);
    } else {
        title = 'Tolak Pembayaran?';
        text = 'Pembayaran akan ditolak. Pelanggan harus upload ulang.';
        confirmText = 'Ya, Tolak';
        url = "{{ route('admin.transaksi.reject', ':id') }}".replace(':id', id);
    }
    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FB7E3C',
        cancelButtonColor: '#6B3F2D',
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        background: '#FFFCF9',
        color: '#2C1A12'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#FEF5E7',
                        color: '#5A2E1A'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        background: '#FFFCF9',
                        color: '#2C1A12'
                    });
                }
            });
        }
    });
}
</script>
@endpush