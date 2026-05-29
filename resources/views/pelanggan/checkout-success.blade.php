@extends('layouts.app')

@section('title', 'Checkout Berhasil - DimsumYummy')

@section('content')
<div class="bg-[#FFF5E1] min-h-screen py-8">
  <div class="container mx-auto px-4">
    <div class="w-full max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-8">

      {{-- Header --}}
      <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div class="flex items-center gap-3">
          <i class="fas fa-check-circle text-green-500 text-2xl"></i>
          <h1 class="text-xl font-bold text-orange-600">Checkout Berhasil! 🎉</h1>
        </div>
        <span class="text-sm text-gray-500">
          {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y H:i') }}
        </span>
      </div>

      {{-- Info Pelanggan & Ringkasan --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
          <h2 class="text-orange-600 font-semibold mb-3">👤 Data Pemesan</h2>
          <p class="text-sm mb-1"><b>Nama:</b> {{ $transaksi->nama_pemesan }}</p>
          <p class="text-sm mb-1"><b>No. HP:</b> {{ $transaksi->no_hp }}</p>
          <p class="text-sm mb-1"><b>Alamat:</b> {{ $transaksi->alamat }}</p>
          <p class="text-sm mb-1"><b>Pembayaran:</b>
            <span class="capitalize font-semibold text-orange-600">{{ $transaksi->payment_method }}</span>
          </p>
          <p class="text-sm"><b>Pengiriman:</b>
            <span class="uppercase font-semibold text-orange-600">{{ $transaksi->shipping_method }}</span>
          </p>
          @if(!empty($transaksi->notes))
          <p class="text-sm mt-2 pt-2 border-t border-orange-200"><b>📝 Catatan:</b> {{ $transaksi->notes }}</p>
          @endif
        </div>

        <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
          <h2 class="text-orange-600 font-semibold mb-3">📋 Ringkasan</h2>
          <p class="text-sm mb-1"><b>ID Transaksi:</b> #{{ $transaksi->id }}</p>
          <p class="text-sm mb-1"><b>Tanggal:</b>
            {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y H:i') }}
          </p>
          <p class="text-sm mt-2"><b>Total Bayar:</b>
            <span class="text-orange-600 font-bold text-lg">
              Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
            </span>
          </p>
        </div>
      </div>

      {{-- Informasi Transfer (jika memilih transfer) --}}
      @if($transaksi->payment_method == 'transfer')
      <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 mb-6">
        <h3 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
          <i class="fas fa-info-circle"></i> Informasi Pembayaran Transfer
        </h3>
        <p class="text-sm text-blue-700">Silakan lakukan transfer ke rekening berikut:</p>
        <div class="mt-2 space-y-1 text-sm">
          <p><strong>🏦 Bank BCA</strong> : 1234567890 a.n. DimsumYummy</p>
          <p><strong>🏦 Bank Mandiri</strong> : 9876543210 a.n. DimsumYummy</p>
          <p class="text-xs mt-2 text-blue-600">Setelah transfer, konfirmasi melalui WhatsApp ke nomor +62 823-1759-20647</p>
        </div>
      </div>
      @endif

      {{-- Tabel Produk --}}
      <div class="overflow-x-auto mb-6">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-orange-100 text-orange-700">
              <th class="py-3 px-4 text-left rounded-tl-lg">Foto</th>
              <th class="py-3 px-4 text-left">Produk</th>
              <th class="py-3 px-4 text-center">Qty</th>
              <th class="py-3 px-4 text-center">Harga Satuan</th>
              <th class="py-3 px-4 text-right rounded-tr-lg">Subtotal</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach($transaksi->details as $detail)
            <tr class="hover:bg-orange-50 transition">
              <td class="py-2 px-4">
                <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                     alt="{{ $detail->produk->nama }}"
                     class="h-12 w-12 object-cover rounded-lg">
              </td>
              <td class="py-3 px-4 font-medium">{{ $detail->produk->nama }}</td>
              <td class="py-3 px-4 text-center">{{ $detail->qty }}</td>
              <td class="py-3 px-4 text-center">
                Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
              </td>
              <td class="py-3 px-4 text-right font-semibold">
                Rp{{ number_format($detail->harga_satuan * $detail->qty, 0, ',', '.') }}
              </td>
            </tr>
            @endforeach

            <tr class="bg-orange-200 font-bold">
              <td colspan="4" class="py-3 px-4 text-right">Total</td>
              <td class="py-3 px-4 text-right">
                Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      {{-- Tombol --}}
      <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('welcome') }}"
           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition">
          <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
        <a href="{{ route('pelanggan.invoice', $transaksi->id) }}" target="_blank"
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition">
          <i class="fas fa-print"></i> Cetak Invoice
        </a>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
Swal.fire({
    icon: 'success',
    title: 'Checkout Berhasil! 🎉',
    text: 'Pesanan kamu sudah tercatat. Silakan lakukan pembayaran sesuai metode yang dipilih.',
    confirmButtonColor: '#FB7E3C',
    background: '#FFF5E1',
    color: '#A16207',
});
</script>
@endpush