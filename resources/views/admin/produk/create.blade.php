@extends('admin.layouts.app')

@section('title', 'Tambah Produk - Admin DimsumYummy')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-10 w-2 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full shadow-sm"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#2C1A12] tracking-tight">✨ Tambah Menu Baru</h1>
            <p class="text-stone-500 text-sm">Kelola inventaris dimsum kamu dengan mudah dan rapi.</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 space-y-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="group space-y-2">
                <label class="block font-bold text-[#2C1A12] ml-1">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama') }}" 
                       class="w-full border-2 border-stone-100 bg-stone-50/30 rounded-2xl p-4 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-[#FB7E3C] outline-none transition-all"
                       placeholder="Masukkan nama dimsum..." required>
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="group space-y-2">
                <label class="block font-bold text-[#2C1A12] ml-1">Kategori</label>
                <select name="id_kategori" class="w-full border-2 border-stone-100 bg-stone-50/30 rounded-2xl p-4 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-[#FB7E3C] outline-none transition-all" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('id_kategori') == $kat->id ? 'selected' : '' }}>
                            🥟 {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="group space-y-2">
                <label class="block font-bold text-[#2C1A12] ml-1">Harga (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-bold">Rp</span>
                    <input type="number" name="harga" value="{{ old('harga') }}" 
                           class="w-full border-2 border-stone-100 bg-stone-50/30 rounded-2xl p-4 pl-12 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-[#FB7E3C] outline-none transition-all" 
                           placeholder="0" required>
                </div>
                @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="group space-y-2">
                <label class="block font-bold text-[#2C1A12] ml-1">Stok Barang</label>
                <input type="number" name="stok" value="{{ old('stok') }}" 
                       class="w-full border-2 border-stone-100 bg-stone-50/30 rounded-2xl p-4 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-[#FB7E3C] outline-none transition-all" 
                       placeholder="0" required>
                @error('stok') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 space-y-2">
                <label class="block font-bold text-[#2C1A12] ml-1">Foto Produk</label>
                <div class="relative group cursor-pointer border-2 border-dashed border-orange-200 rounded-2xl bg-orange-50/20 hover:bg-orange-50/50 transition-all p-4 text-center">
                    <input type="file" name="foto" id="fotoInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    <div id="previewContainer" class="flex flex-col items-center justify-center space-y-2">
                        <div id="placeholderIcon" class="p-4 bg-white rounded-2xl shadow-sm">
                            <i class="fas fa-cloud-upload-alt text-3xl text-[#FB7E3C]"></i>
                        </div>
                        <p class="text-xs text-stone-400">Klik atau drop gambar di sini</p>
                        <img id="fotoPreview" class="h-32 w-32 object-cover rounded-2xl border-4 border-white shadow-md hidden mx-auto">
                    </div>
                </div>
                @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="block font-bold text-[#2C1A12] ml-1">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="5" 
                          class="w-full border-2 border-stone-100 bg-stone-50/30 rounded-2xl p-4 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-[#FB7E3C] outline-none transition-all" 
                          placeholder="Tuliskan deskripsi rasa dan bahan dimsum..." required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Checkbox Unggulan --}}
        <div class="flex items-center justify-end pt-2">
            <label class="flex items-center cursor-pointer select-none">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                       class="rounded border-stone-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-stone-600 font-medium">✨ Jadikan Produk Unggulan</span>
            </label>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.produk.index') }}" 
               class="px-6 py-3 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-2xl font-bold transition">Batal</a>
            <button type="submit" 
                    class="px-6 py-3 bg-[#6B3F2D] hover:bg-[#4A2C1F] text-white rounded-2xl font-bold shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoInput')?.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('fotoPreview');
                const placeholder = document.getElementById('placeholderIcon');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder?.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush