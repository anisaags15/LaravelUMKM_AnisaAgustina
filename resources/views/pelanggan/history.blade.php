@extends('layouts.app')

@section('title', 'Riwayat Transaksi - DimsumYummy')

@section('content')
<div class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="h-10 w-1.5 bg-gradient-to-b from-orange-500 to-orange-700 rounded-full"></div>
            <h1 class="text-3xl font-black text-stone-800 tracking-tight">📜 Riwayat Transaksi</h1>
        </div>

        {{-- Info ringkas user --}}
        <div class="bg-white rounded-2xl shadow-md p-4 mb-6 flex flex-wrap justify-between items-center gap-3 text-sm border border-orange-100">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-circle text-orange-500 text-xl"></i>
                <span class="font-semibold text-stone-700">{{ Auth::user()->nama ?? Auth::user()->name }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-orange-500"></i>
                <span class="text-stone-600">{{ Auth::user()->email }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-receipt text-orange-500"></i>
                <span class="font-bold text-orange-600">{{ $transaksi->count() }} Transaksi</span>
            </div>
        </div>

        {{-- Tabel Riwayat Transaksi --}}
        @if($transaksi->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-orange-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200">
                    <thead class="bg-gradient-to-r from-stone-50 to-orange-50/30">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">No</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">ID</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">Tanggal</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">Pembayaran</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">Pengiriman</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">Total</th>
                            <th class="px-5 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($transaksi as $index => $trx)
                        <tr class="hover:bg-orange-50/40 transition">
                            <td class="px-5 py-4 text-sm text-stone-500">{{ $index + 1 }}</td>
                            <td class="px-5 py-4 text-sm font-mono font-bold text-orange-600">#{{ $trx->id }}</td>
                            <td class="px-5 py-4 text-sm text-stone-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ ucfirst($trx->payment_method ?? '-') }}
                                    </span>
                                    @if($trx->payment_method == 'transfer' && $trx->payment_status == 'pending' && $trx->payment_proof)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                             Menunggu Verifikasi
                                        </span>
                                    @elseif($trx->payment_method == 'transfer' && $trx->payment_status == 'verified')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                             Diverifikasi
                                        </span>
                                    @elseif($trx->payment_method == 'transfer' && $trx->payment_status == 'rejected')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                             Ditolak
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold uppercase">
                                    {{ $trx->shipping_method ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm font-bold text-orange-600 whitespace-nowrap">
                                Rp{{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Detail (mata) --}}
                                    <a href="{{ route('pelanggan.transaksi.detail', $trx->id) }}" 
                                       class="p-2 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white transition" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    {{-- Tombol Aksi Sesuai Status --}}
                                    @if($trx->payment_method == 'transfer')
                                        @if($trx->payment_status == 'verified')
                                            <a href="{{ route('pelanggan.invoice', $trx->id) }}" target="_blank"
                                               class="p-2 rounded-xl bg-green-50 text-green-600 hover:bg-green-500 hover:text-white transition" title="Cetak Invoice">
                                                <i class="fas fa-print text-sm"></i>
                                            </a>
                                        @elseif($trx->payment_status == 'pending' && $trx->payment_proof)
                                            <span class="p-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed" title="Menunggu verifikasi admin">
                                                <i class="fas fa-clock text-sm"></i>
                                            </span>
                                        @elseif($trx->payment_status == 'rejected')
                                            <a href="{{ route('pelanggan.upload.proof.form', $trx->id) }}"
                                               class="p-2 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition" title="Upload Ulang Bukti Transfer">
                                                <i class="fas fa-upload text-sm"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('pelanggan.upload.proof.form', $trx->id) }}"
                                               class="p-2 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition" title="Upload Bukti Transfer">
                                                <i class="fas fa-upload text-sm"></i>
                                            </a>
                                        @endif
                                    @else
                                        {{-- COD langsung cetak --}}
                                        <a href="{{ route('pelanggan.invoice', $trx->id) }}" target="_blank"
                                           class="p-2 rounded-xl bg-green-50 text-green-600 hover:bg-green-500 hover:text-white transition" title="Cetak Invoice">
                                            <i class="fas fa-print text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <i class="fas fa-receipt text-5xl text-stone-300 mb-3"></i>
            <h3 class="text-xl font-bold text-stone-600 mb-2">Belum ada transaksi</h3>
            <p class="text-stone-400 text-sm mb-6">Yuk mulai belanja dimsum favoritmu!</p>
            <a href="{{ route('produk.index') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-orange-600 transition">Belanja Sekarang</a>
        </div>
        @endif

        @if($transaksi->isNotEmpty())
        <div class="flex justify-center mt-6">
            <a href="{{ route('welcome') }}" class="text-sm text-stone-500 hover:text-orange-500 transition">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            background: '#FFF9F0',
            color: '#5A2E1A'
        });
    @endif

    @if(session('verified_transactions'))
        let ids = @json(session('verified_transactions'));
        Swal.fire({
            icon: 'success',
            title: 'Pembayaran Diverifikasi!',
            html: 'Transaksi <strong>#' + ids.join(', #') + '</strong> telah diverifikasi.<br>Silakan cetak invoice.',
            confirmButtonColor: '#FB7E3C',
            background: '#FFF9F0',
            color: '#5A2E1A',
            timer: 5000,
            timerProgressBar: true
        });
    @endif
</script>
@endpush