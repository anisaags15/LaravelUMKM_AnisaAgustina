@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $transaksi->id)

@section('content')
<div class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-5xl">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="h-8 w-1.5 bg-gradient-to-b from-orange-500 to-orange-700 rounded-full"></div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight text-stone-800">
                        📄 Detail Transaksi #{{ $transaksi->id }}
                    </h1>
                </div>
                <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                    <i class="fas fa-receipt text-orange-500 text-xs"></i>
                    Informasi lengkap pesanan
                </p>
            </div>
            <a href="{{ route('pelanggan.history') }}"
               class="px-5 py-3 bg-orange-500 text-white rounded-2xl font-bold text-sm hover:bg-orange-600 transition-all shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Riwayat
            </a>
        </div>

        {{-- GRID INFO: Pelanggan & Transaksi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- CARD PELANGGAN --}}
            <div class="bg-white rounded-3xl shadow-lg border border-stone-100 p-6 transition hover:shadow-xl">
                <div class="flex items-center gap-3 mb-4 pb-2 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center text-white shadow">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <h2 class="text-lg font-black text-stone-800">Data Pelanggan</h2>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">Nama</span>
                        <span class="font-bold text-stone-800 text-right">{{ $transaksi->nama_pemesan ?? $transaksi->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">Email</span>
                        <span class="text-stone-700 text-right">{{ $transaksi->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">No. HP</span>
                        <span class="text-stone-700 text-right">{{ $transaksi->no_hp ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-stone-500 font-medium">Alamat</span>
                        <span class="text-stone-700 text-right max-w-[220px]">{{ $transaksi->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- CARD INFO TRANSAKSI --}}
            <div class="bg-white rounded-3xl shadow-lg border border-stone-100 p-6 transition hover:shadow-xl">
                <div class="flex items-center gap-3 mb-4 pb-2 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center text-white shadow">
                        <i class="fas fa-info-circle text-sm"></i>
                    </div>
                    <h2 class="text-lg font-black text-stone-800">Info Transaksi</h2>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">ID Transaksi</span>
                        <span class="font-mono font-black text-orange-600">#{{ $transaksi->id }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">Tanggal</span>
                        <span class="text-stone-700">{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">Pembayaran</span>
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-credit-card mr-1 text-[10px]"></i> {{ ucfirst($transaksi->payment_method ?? '-') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">Pengiriman</span>
                        <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded-full text-xs font-semibold uppercase">
                            <i class="fas fa-truck mr-1 text-[10px]"></i> {{ $transaksi->shipping_method ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center border-b border-stone-50 pb-2">
                        <span class="text-stone-500 font-medium">Status Pembayaran</span>
                        @php
                            $statusLabel = [
                                'pending' => 'Menunggu Verifikasi',
                                'verified' => 'Lunas & Terverifikasi',
                                'rejected' => 'Ditolak'
                            ];
                            $statusColor = [
                                'pending' => 'bg-yellow-50 text-yellow-700',
                                'verified' => 'bg-green-50 text-green-700',
                                'rejected' => 'bg-red-50 text-red-700'
                            ];
                            $statusText = $statusLabel[$transaksi->payment_status] ?? ucfirst($transaksi->payment_status);
                            $colorClass = $statusColor[$transaksi->payment_status] ?? 'bg-gray-50 text-gray-700';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ $statusText }}
                        </span>
                    </div>
                    @if(!empty($transaksi->notes))
                    <div class="flex justify-between items-start pt-1">
                        <span class="text-stone-500 font-medium">Catatan</span>
                        <span class="text-stone-700 text-right max-w-[200px]">{{ $transaksi->notes }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-stone-500 font-medium">Total Bayar</span>
                        <span class="text-xl font-black text-orange-600">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD DETAIL PRODUK --}}
        <div class="bg-white rounded-3xl shadow-lg border border-stone-100 p-6 mb-6 transition hover:shadow-xl">
            <div class="flex items-center gap-3 mb-4 pb-2 border-b border-stone-100">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center text-white shadow">
                    <i class="fas fa-box text-sm"></i>
                </div>
                <h2 class="text-lg font-black text-stone-800">Detail Produk</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-gradient-to-r from-stone-50 to-orange-50/30">
                        <tr>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500 rounded-tl-xl">Foto</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-wider text-stone-500">Nama Produk</th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">Qty</th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-stone-500">Harga Satuan</th>
                            <th class="px-4 py-4 text-right text-xs font-black uppercase tracking-wider text-stone-500 rounded-tr-xl">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($transaksi->details as $detail)
                        <tr class="hover:bg-orange-50/40 transition">
                            <td class="px-4 py-3">
                                @if($detail->produk && $detail->produk->foto)
                                    <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                                         alt="{{ $detail->produk->nama }}"
                                         class="w-14 h-14 object-cover rounded-xl border border-stone-100 shadow-sm">
                                @else
                                    <div class="w-14 h-14 bg-stone-100 rounded-xl flex items-center justify-center text-stone-400">
                                        <i class="fas fa-image text-xl"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-bold text-stone-800">
                                {{ $detail->produk->nama ?? 'Produk dihapus' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-stone-700">
                                {{ $detail->qty }}
                            </td>
                            <td class="px-4 py-3 text-center text-stone-600">
                                Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-black text-orange-600">
                                Rp{{ number_format($detail->harga_satuan * $detail->qty, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gradient-to-r from-stone-50 to-orange-50/30 font-extrabold">
                            <td colspan="4" class="px-4 py-4 text-right text-sm text-stone-600 rounded-bl-xl">Grand Total</td>
                            <td class="px-4 py-4 text-right text-lg font-black text-orange-600 rounded-br-xl">
                                Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- TOMBOL AKSI + UPLOAD BUKTI (jika transfer & belum verified) --}}
        <div class="flex flex-wrap justify-end gap-3">
            @if($transaksi->payment_method == 'transfer' && $transaksi->payment_status != 'verified')
                <a href="{{ route('pelanggan.upload.proof.form', $transaksi->id) }}" 
                   class="flex items-center gap-2 px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-bold text-sm shadow-md transition hover:scale-105">
                    <i class="fas fa-upload text-xs"></i> Upload Bukti Transfer
                </a>
            @endif
            <a href="{{ route('pelanggan.invoice', $transaksi->id) }}" target="_blank"
               class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-bold text-sm shadow-md transition hover:scale-105">
                <i class="fas fa-print text-xs"></i> Cetak Invoice
            </a>
        </div>
    </div>
</div>
@endsection