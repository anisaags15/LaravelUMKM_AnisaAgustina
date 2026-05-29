@extends('admin.layouts.app')

@section('title', 'Data Barang - Admin DimsumYummy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- Header dengan aksen --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1.5 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#2C1A12]">
                    📦 Data Barang
                </h1>
            </div>
            <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                <i class="fas fa-boxes text-[#FB7E3C] text-xs"></i>
                Total produk:
                <span class="font-extrabold text-[#FB7E3C] bg-orange-50 px-2 py-0.5 rounded-full text-sm">
                    {{ $produkList->count() }} Item
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.produk.create') }}"
               class="px-5 py-3 bg-[#FB7E3C] text-white rounded-2xl font-bold text-sm hover:bg-[#D95C1A] transition-all shadow-md flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    {{-- Card Tabel --}}
    <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 overflow-hidden transition hover:shadow-xl">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-orange-200">
            <table class="min-w-full divide-y divide-stone-100">
                <thead class="bg-gradient-to-r from-stone-50 to-orange-50/30">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500 rounded-tl-xl">No</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Nama Produk</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Harga</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Stok</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Kategori</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Deskripsi</th>
                        <th class="px-6 py-5 text-center text-xs font-black uppercase tracking-wider text-stone-500 rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($produkList as $index => $item)
                    <tr class="hover:bg-orange-50/40 transition duration-150 group">
                        <td class="px-6 py-4 text-sm text-stone-400 font-medium whitespace-nowrap">
                            {{ sprintf('%02d', $index + 1) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('storage/' . $item->foto) }}" 
                                     class="w-12 h-12 rounded-xl object-cover border border-stone-100 shadow-sm">
                                <div class="flex flex-col">
                                    <span class="font-bold text-[#3A2418]">{{ $item->nama }}</span>
                                    @if($item->is_featured)
                                    <span class="text-[10px] text-orange-500 font-semibold mt-0.5 flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-xs"></i> Unggulan
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-black text-[#D95C1A] whitespace-nowrap">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">
                            <span class="px-2 py-1 bg-stone-100 rounded-full text-xs font-semibold">{{ $item->stok }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">
                            {{ $item->kategori->nama_kategori ?? 'Umum' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-stone-500 max-w-[200px] truncate" title="{{ $item->deskripsi }}">
                            {{ Str::limit($item->deskripsi, 60) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.produk.edit', $item->id) }}"
                                   class="p-2 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white transition-all duration-200 shadow-sm"
                                   title="Edit Produk">
                                    <i class="fas fa-pencil-alt text-sm"></i>
                                </a>
                                <button type="button" onclick="deleteProduct('{{ $item->id }}')"
                                        class="p-2 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm"
                                        title="Hapus Produk">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                            <form id="deleteForm-{{ $item->id }}" action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box-open text-2xl text-stone-300"></i>
                                </div>
                                <p class="text-stone-400 font-medium">Belum ada produk yang terdaftar.</p>
                                <a href="{{ route('admin.produk.create') }}" class="text-orange-500 hover:underline">Tambah produk sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteProduct(id) {
        Swal.fire({
            title: 'Hapus Menu Ini?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#78716C',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#FFFCF9',
            color: '#2C1A12'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        });
    }
</script>
@endpush