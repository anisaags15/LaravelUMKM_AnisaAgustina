@extends('admin.layouts.app')

@section('title', 'Riwayat Transaksi - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1.5 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#2C1A12]">
                    📜 Riwayat Transaksi
                </h1>
            </div>
            <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                <i class="fas fa-receipt text-[#FB7E3C] text-xs"></i>
                Total transaksi:
                <span class="font-extrabold text-[#FB7E3C] bg-orange-50 px-2 py-0.5 rounded-full text-sm">
                    {{ $transaksi->total() }} Transaksi
                </span>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center w-full md:w-auto">
            <div class="relative group flex-1 sm:w-72">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-[#FB7E3C] transition text-sm"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()"
                       placeholder="Cari nama, email, metode..."
                       class="w-full pl-10 pr-4 py-3 bg-white/80 backdrop-blur-sm border border-stone-200 rounded-2xl focus:border-[#FB7E3C] focus:ring-2 focus:ring-orange-200 outline-none transition shadow-sm hover:shadow">
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="px-5 py-3 bg-[#FB7E3C] text-white rounded-2xl font-bold text-sm hover:bg-[#D95C1A] transition-all shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-arrow-left text-xs"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 overflow-hidden transition hover:shadow-xl">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-orange-200" style="max-height: 600px; overflow-y: auto;">
            <table class="min-w-full divide-y divide-stone-100" id="transTable">
                <thead class="sticky top-0 z-10 bg-white">
                    <tr class="bg-gradient-to-r from-stone-50 to-orange-50/30">
                        <th class="w-12 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">No</th>
                        <th class="w-28 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Tanggal</th>
                        <th class="w-36 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Nama</th>
                        <th class="w-44 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Email</th>
                        <th class="w-28 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Total</th>
                        <th class="w-28 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Pembayaran</th>
                        <th class="w-28 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Pengiriman</th>
                        <th class="w-28 px-4 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Status</th>
                        <th class="w-36 px-4 py-5 text-center text-xs font-black uppercase tracking-wider text-stone-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($transaksi as $index => $trx)
                    <tr class="hover:bg-orange-50/40 transition duration-150 group">
                        <td class="px-4 py-4 text-sm text-stone-400 font-medium">{{ $transaksi->firstItem() + $index }}</td>
                        <td class="px-4 py-4 text-sm text-stone-600 whitespace-nowrap">
                            <i class="far fa-calendar-alt text-[#FB7E3C] text-xs mr-1"></i>
                            {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#FB7E3C] to-[#D95C1A] flex items-center justify-center text-white text-xs font-bold shadow flex-shrink-0">
                                    {{ strtoupper(substr($trx->user->name ?? $trx->user->nama ?? '-', 0, 1)) }}
                                </div>
                                <span class="font-bold text-[#3A2418] truncate max-w-[120px] block" title="{{ $trx->user->nama ?? $trx->user->name ?? '-' }}">
                                    {{ $trx->user->nama ?? $trx->user->name ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-stone-600 truncate max-w-[160px]" title="{{ $trx->user->email ?? '-' }}">
                            {{ $trx->user->email ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-sm font-black text-[#D95C1A] whitespace-nowrap">
                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                <i class="fas fa-credit-card mr-1 text-[10px]"></i> {{ $trx->payment_method ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                <i class="fas fa-truck mr-1 text-[10px]"></i> {{ $trx->shipping_method ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($trx->payment_method == 'transfer')
                                @if($trx->payment_status == 'verified')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Diverifikasi</span>
                                @elseif($trx->payment_status == 'rejected')
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                                @endif
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-semibold">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('admin.transaksi.show', $trx->id) }}"
                                   class="p-1.5 rounded-lg bg-sky-100 text-sky-600 hover:bg-sky-500 hover:text-white transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                {{-- Tombol Verifikasi (hanya untuk transfer yang belum diverifikasi) --}}
                                @if($trx->payment_method == 'transfer' && $trx->payment_status != 'verified')
                                    <a href="{{ route('admin.transaksi.show', $trx->id) }}#verification"
                                       class="p-1.5 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition"
                                       title="Verifikasi Pembayaran">
                                        <i class="fas fa-check-double text-sm"></i>
                                    </a>
                                @endif

                                {{-- Tombol Cetak Invoice --}}
                                <a href="{{ route('admin.transaksi.invoice', $trx->id) }}" target="_blank"
                                   class="p-1.5 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-500 hover:text-white transition"
                                   title="Cetak Invoice">
                                    <i class="fas fa-print text-sm"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <button onclick="confirmDelete({{ $trx->id }})"
                                        class="p-1.5 rounded-lg bg-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white transition"
                                        title="Hapus Transaksi">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                                <form id="delete-form-{{ $trx->id }}" action="{{ route('admin.transaksi.destroy', $trx->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300"></i>
                                </div>
                                <p class="text-stone-400 font-medium">Belum ada transaksi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $transaksi->links() }}
    </div>

    <div class="md:hidden mt-6 space-y-3">
        @foreach($transaksi as $trx)
        <div class="bg-white rounded-2xl shadow p-4 border border-stone-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FB7E3C] to-[#D95C1A] flex items-center justify-center text-white text-xs font-bold shadow">
                        {{ strtoupper(substr($trx->user->name ?? $trx->user->nama ?? '-', 0, 1)) }}
                    </div>
                    <p class="font-bold text-[#2C1A12] truncate max-w-[150px]" title="{{ $trx->user->nama ?? $trx->user->name ?? '-' }}">
                        {{ $trx->user->nama ?? $trx->user->name ?? '-' }}
                    </p>
                </div>
                <span class="text-xs text-stone-400">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</span>
            </div>
            <p class="text-sm"><i class="fas fa-money-bill-wave text-[#FB7E3C] w-4"></i> Total: <b class="text-orange-600">Rp{{ number_format($trx->total_harga, 0, ',', '.') }}</b></p>
            <p class="text-xs mt-1">
                Status:
                @if($trx->payment_method == 'transfer')
                    @if($trx->payment_status == 'verified') Diverifikasi
                    @elseif($trx->payment_status == 'rejected') Ditolak
                    @else Menunggu
                    @endif
                @else -
                @endif
            </p>
            <div class="flex gap-2 mt-3">
                <a href="{{ route('admin.transaksi.show', $trx->id) }}" class="flex-1 text-center py-2 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-600 transition">Detail</a>
                <button onclick="confirmDelete({{ $trx->id }})" class="flex-1 py-2 bg-rose-500 text-white rounded-xl text-xs font-bold hover:bg-rose-600 transition">Hapus</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#transTable tbody tr');
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus transaksi?',
        text: "Data transaksi akan dihapus permanen!",
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

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false,
        background: '#FEF5E7',
        color: '#5A2E1A'
    });
@endif
</script>
@endpush