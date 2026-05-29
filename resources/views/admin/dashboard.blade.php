@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin DimsumYummy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 pb-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8 pt-4">
        <div class="h-12 w-2 bg-gradient-to-b from-orange-500 to-red-500 rounded-full shadow-sm"></div>
        <div>
            <h1 class="text-3xl font-black text-stone-800 tracking-tight">Dashboard</h1>
            <p class="text-sm text-stone-500 font-medium mt-1">Selamat datang kembali di DimsumYummy! 🥟</p>
        </div>
    </div>

    {{-- BARIS 1: 4 Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-500 text-sm font-semibold mb-1">Total Produk</p>
                    <p class="text-3xl font-black text-stone-800">{{ $totalProduk ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 shadow-inner">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-500 text-sm font-semibold mb-1">Total Pelanggan</p>
                    <p class="text-3xl font-black text-stone-800">{{ $totalPelanggan ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 shadow-inner">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-500 text-sm font-semibold mb-1">Total Transaksi</p>
                    <p class="text-3xl font-black text-stone-800">{{ $totalTransaksi ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 shadow-inner">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-500 text-sm font-semibold mb-1">Pendapatan</p>
                    <p class="text-2xl font-black text-stone-800">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 shadow-inner">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 2: 2 Kolom (Grafik & Top Produk) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Grafik Pendapatan per Hari (Ambil 2 Kolom) --}}
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-stone-100 p-6 overflow-visible">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-sky-50 rounded-lg text-sky-500">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-800">Statistik Pendapatan</h3>
                </div>
                <span class="text-xs font-semibold text-stone-400 bg-stone-50 px-3 py-1.5 rounded-full">7 Hari Terakhir</span>
            </div>
            
            @if(isset($data) && array_sum($data) > 0)
                <div class="w-full overflow-visible pt-12">
                    <div class="flex items-end justify-between gap-2 h-56 min-w-[400px] border-b-2 border-stone-100 pb-2">
                        @foreach($labels as $index => $label)
                        @php
                            $maxValue = max($data) ?: 1;
                            // Skala tinggi diatur maksimal 80% supaya tooltip aman terkendali
                            $height = ($data[$index] / $maxValue) * 80; 
                        @endphp
                        <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                            
                            <div class="absolute -top-12 bg-stone-800 text-white text-[11px] font-semibold rounded-lg px-2.5 py-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200 transform group-hover:-translate-y-1 z-30 whitespace-nowrap shadow-md pointer-events-none">
                                Rp {{ number_format($data[$index], 0, ',', '.') }}
                                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-stone-800 rotate-45"></div>
                            </div>
                            
                            <div class="w-full max-w-[48px] bg-gradient-to-t from-orange-400 to-amber-300 rounded-t-xl transition-all duration-300 group-hover:from-orange-500 group-hover:to-amber-400 shadow-sm" 
                                 style="height: {{ max($height, 3) }}%;">
                            </div>
                            
                            <span class="text-xs font-medium text-stone-400 mt-3">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-stone-400">
                    <i class="fas fa-inbox text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Belum ada transaksi dalam 7 hari terakhir.</p>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: Top 5 Produk Terlaris --}}
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-red-50 rounded-lg text-red-500">
                    <i class="fas fa-fire"></i>
                </div>
                <h3 class="font-bold text-lg text-stone-800">Top 5 Dimsum</h3>
            </div>
            
            @if(isset($topProducts) && $topProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $index => $product)
                    <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-orange-50 transition-colors border border-transparent hover:border-orange-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : ($index == 1 ? 'bg-stone-200 text-stone-600' : ($index == 2 ? 'bg-orange-100 text-orange-600' : 'bg-stone-50 text-stone-400')) }} flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ $index+1 }}
                            </div>
                            <span class="font-semibold text-stone-700 text-sm">{{ $product['nama'] }}</span>
                        </div>
                        <span class="bg-orange-100 text-orange-600 font-bold text-[11px] px-3 py-1.5 rounded-full shadow-sm">
                            {{ $product['total_terjual'] }} pcs
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-stone-400">
                    <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Belum ada data produk.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection