<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaksi->id }} - Dimsum Yummy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Tetap mempertahankan beberapa reset print esensial untuk browser */
        @media print {
            body { background: white !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-6 md:py-12 print:bg-white print:min-h-0 print:py-0">

<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">

        <div class="flex justify-end gap-3 mb-6 print:hidden">
            <a href="{{ url()->previous() }}" class="bg-stone-400 hover:bg-stone-500 text-white px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition">
                <i class="fas fa-print"></i> Cetak Invoice
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-6 md:p-10 border border-orange-100/50 print:border-stone-200 print:shadow-none print:rounded-none print:p-2">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-stone-100 pb-6 mb-8">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logoyummy.jpeg') }}" alt="Dimsum Yummy" class="h-14 w-14 rounded-2xl shadow-sm border border-orange-100 object-cover">
                    <div>
                        <h1 class="text-2xl font-black text-stone-800 tracking-tight">Dimsum <span class="text-orange-500">Yummy</span></h1>
                        <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mt-0.5">Bukti Transaksi Resmi</p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <span class="bg-orange-50 text-orange-600 print:bg-stone-100 print:text-stone-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                        Invoice
                    </span>
                    <h2 class="text-xl font-black text-stone-800 mt-2">#{{ $transaksi->id }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                <div class="space-y-2">
                    <h3 class="text-stone-800 font-extrabold text-xs uppercase tracking-wider flex items-center gap-2 mb-3 text-orange-500">
                        <i class="fas fa-user text-sm"></i> Informasi Pelanggan
                    </h3>
                    <div class="text-sm text-stone-600 space-y-1.5">
                        <p><span class="font-medium text-stone-400 inline-block w-20">Nama</span>: <strong class="text-stone-800">{{ $transaksi->user->name ?? $transaksi->nama_pemesan ?? '-' }}</strong></p>
                        <p><span class="font-medium text-stone-400 inline-block w-20">Email</span>: {{ $transaksi->user->email ?? '-' }}</p>
                        <p><span class="font-medium text-stone-400 inline-block w-20">No. HP</span>: {{ $transaksi->no_hp ?? '-' }}</p>
                        <p class="flex items-start"><span class="font-medium text-stone-400 inline-block w-20 flex-shrink-0">Alamat</span><span class="mr-1">:</span> <span class="text-stone-700 font-medium">{{ $transaksi->alamat ?? '-' }}</span></p>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-stone-800 font-extrabold text-xs uppercase tracking-wider flex items-center gap-2 mb-3 text-orange-500">
                        <i class="fas fa-credit-card text-sm"></i> Detail Pesanan
                    </h3>
                    <div class="text-sm text-stone-600 space-y-1.5">
                        <p><span class="font-medium text-stone-400 inline-block w-28">Tanggal</span>: {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y H:i') }}</p>
                        <p><span class="font-medium text-stone-400 inline-block w-28">Metode Bayar</span>: <span class="capitalize bg-stone-50 print:bg-transparent px-2.5 py-0.5 rounded-md font-bold text-stone-700 border border-stone-200/60 print:border-none print:p-0">{{ $transaksi->payment_method ?? '-' }}</span></p>
                        <p><span class="font-medium text-stone-400 inline-block w-28">Kurir Ekspedisi</span>: <span class="uppercase bg-orange-50 print:bg-transparent px-2.5 py-0.5 rounded-md font-bold text-orange-600 border border-orange-100 print:border-none print:p-0">{{ $transaksi->shipping_method ?? '-' }}</span></p>
                    </div>
                </div>
            </div>

            @if(!empty($transaksi->notes))
            <div class="mb-8 p-4 bg-amber-50/60 border-l-4 border-orange-500 rounded-xl text-sm text-stone-700 print:bg-stone-50 print:border-stone-400">
                <span class="font-bold text-orange-600 print:text-stone-800 flex items-center gap-2 mb-1">
                    <i class="fas fa-comment-dots"></i> Catatan dari Pemesan:
                </span>
                <p class="italic text-stone-600">"{{ $transaksi->notes }}"</p>
            </div>
            @endif

            <div class="overflow-x-auto border border-stone-100 rounded-2xl mb-6 print:border-stone-200 print:rounded-none">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-stone-50 text-stone-600 border-b border-stone-100 font-semibold print:bg-stone-100 print:border-stone-200">
                            <th class="py-3 px-4 text-left w-20">Foto</th>
                            <th class="py-3 px-4 text-left">Item Menu Dimsum</th>
                            <th class="py-3 px-4 text-center w-20">Qty</th>
                            <th class="py-3 px-4 text-right w-36">Harga Satuan</th>
                            <th class="py-3 px-4 text-right w-36">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 print:divide-stone-200">
                        @foreach($transaksi->details as $detail)
                        @php $subtotal = $detail->qty * $detail->harga_satuan; @endphp
                        <tr class="hover:bg-stone-50/50 transition duration-150">
                            <td class="py-3 px-4">
                                <img src="{{ asset('storage/' . ($detail->produk->foto ?? '')) }}" 
                                     onerror="this.src='{{ asset('img/no-image.png') }}'" 
                                     class="w-10 h-10 object-cover rounded-xl border border-stone-100 shadow-inner print:w-12 print:h-12 print:rounded-lg">
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-stone-800">{{ $detail->produk->nama }}</span>
                            </td>
                            <td class="py-3 px-4 text-center font-semibold text-stone-600">{{ $detail->qty }}x</td>
                            <td class="py-3 px-4 text-right text-stone-500">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-bold text-stone-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end p-4 sm:p-0">
                <div class="w-full sm:w-80 space-y-3 text-sm">
                    <div class="flex justify-between items-center border-t-2 border-dashed border-stone-200 pt-4">
                        <span class="text-base font-black text-stone-800">Total Pembayaran</span>
                        <span class="text-xl font-black text-orange-600 print:text-stone-900">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center text-stone-400 text-xs border-t border-stone-100 pt-6 print:border-stone-200">
                <p class="font-medium">Terima kasih banyak telah berbelanja di <span class="text-orange-500 font-bold print:text-stone-700">Dimsum Yummy</span>! 🧡</p>
                <p class="text-[10px] text-stone-400 mt-1">Dokumen ini sah dikeluarkan secara sistem komputerisasi tanpa tanda tangan basah.</p>
            </div>

        </div>
    </div>
</div>

</body>
</html>