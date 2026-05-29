@extends('layouts.app')

@section('title', 'Profil Saya - DimsumYummy')

@section('content')
<div class="min-h-screen py-10"
     style="background: linear-gradient(180deg, #ffd280, #ffa751);">
  <div class="container mx-auto px-4 max-w-4xl">

    <div class="flex flex-col md:flex-row gap-6">

      {{-- ===== KONTEN KIRI ===== --}}
      <div class="flex-1 flex flex-col gap-6">

        {{-- Profile Card --}}
        <div class="bg-[#fffdf8] rounded-2xl shadow-xl p-8">

          {{-- Foto & Info --}}
          <div class="flex flex-col items-center text-center mb-6">
            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('img/default_profile.png') }}"
                 alt="Foto Profil"
                 class="w-28 h-28 rounded-full border-4 border-[#f57c00] object-cover shadow-lg mb-3 hover:scale-105 transition">
            <p class="text-2xl font-extrabold text-[#f57c00]">{{ $user->name }}</p>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <p class="text-gray-400 text-xs mt-1">Member sejak {{ $user->created_at->format('Y') }}</p>
          </div>

          {{-- Upload Foto --}}
          <form action="{{ route('pelanggan.profil.foto') }}" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div class="flex flex-col gap-2">
                <input type="file" name="profile_picture" accept="image/*"
                       class="w-full border border-dashed border-[#f0c89a] rounded-xl p-3 text-sm text-gray-600
                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                              file:bg-[#f57c00] file:text-white file:font-bold hover:file:bg-[#ef6c00] transition">
                @error('profile_picture')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
                <button type="submit"
                        class="w-full bg-[#f57c00] text-white py-2 rounded-full font-bold hover:bg-[#ef6c00] transition shadow">
                    Ganti Foto Profil
                </button>
            </div>
          </form>

          {{-- Statistik --}}
          <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-4 text-center shadow border border-gray-100 hover:-translate-y-1 transition">
                <p class="text-2xl font-black text-[#f57c00]">{{ $totalTransaksi }}</p>
                <p class="text-xs text-gray-500 mt-1">🛒 Transaksi</p>
            </div>
            <div class="bg-white rounded-2xl p-4 text-center shadow border border-gray-100 hover:-translate-y-1 transition">
                <p class="text-2xl font-black text-[#f57c00]">{{ (int) $lamaKeanggotaan }}</p>
                <p class="text-xs text-gray-500 mt-1">📅 Tahun</p>
            </div>
            <div class="bg-white rounded-2xl p-4 text-center shadow border border-gray-100 hover:-translate-y-1 transition">
                <p class="text-2xl font-black text-[#f57c00]">⭐</p>
                <p class="text-xs text-gray-500 mt-1">Rating</p>
            </div>
          </div>

          {{-- Welcome --}}
          <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
            <h2 class="font-extrabold text-[#f57c00] text-lg mb-1">
                Selamat datang, {{ $user->name }} 👋
            </h2>
            <p class="text-sm text-gray-600">
                Gunakan menu <b>⚙️ Pengaturan</b> di sidebar untuk mengelola akunmu.
            </p>
          </div>
        </div>

      </div>

      {{-- ===== SIDEBAR KANAN ===== --}}
      <aside class="w-full md:w-64 bg-[#f57c00] text-white rounded-2xl shadow-lg p-6 flex flex-col gap-3 h-fit">
        <h2 class="text-xl font-extrabold text-center mb-2">Dimsum Yummy</h2>

        <a href="{{ route('welcome') }}" class="flex items-center gap-2 p-3 rounded-xl hover:bg-white hover:text-[#f57c00] transition font-bold">
           🏠 Beranda
        </a>
        <a href="{{ route('produk.index') }}" class="flex items-center gap-2 p-3 rounded-xl hover:bg-white hover:text-[#f57c00] transition font-bold">
           🍜 Produk
        </a>
        <a href="{{ route('pelanggan.cart.index') }}" class="flex items-center gap-2 p-3 rounded-xl hover:bg-white hover:text-[#f57c00] transition font-bold">
           🛒 Keranjang
        </a>
        <a href="{{ route('pelanggan.profil.show') }}" class="flex items-center gap-2 p-3 rounded-xl bg-white text-[#f57c00] font-extrabold">
           👤 Profil
        </a>

        <hr class="border-white/40 my-2">
        <p class="text-xs font-bold text-white/70 px-3">⚙️ Pengaturan</p>

        <button onclick="showEditModal()" class="flex items-center gap-2 p-3 rounded-xl hover:bg-white hover:text-[#f57c00] transition font-bold text-left w-full">
            ✏️ Edit Profil
        </button>
        {{-- Tombol Ganti Password dihapus --}}
        <a href="{{ route('pelanggan.history') }}" class="flex items-center gap-2 p-3 rounded-xl hover:bg-white hover:text-[#f57c00] transition font-bold">
           📜 Riwayat Transaksi
        </a>

        <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()" class="w-full flex items-center gap-2 p-3 rounded-xl hover:bg-red-500 hover:text-white transition font-bold text-left">
                🚪 Logout
            </button>
        </form>
      </aside>

    </div>
  </div>
</div>

{{-- ===== MODAL EDIT PROFIL ===== --}}
<div id="modalEditProfil" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
  <div class="bg-[#fffdf8] rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
    <button onclick="hideEditModal()" class="absolute top-3 right-4 text-2xl text-[#f57c00] font-bold">&times;</button>
    <h3 class="text-xl font-extrabold text-[#f57c00] text-center mb-5">✏️ Edit Profil</h3>
    <form action="{{ route('pelanggan.profil.update') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-4">
            <label class="block font-bold text-gray-600 mb-1 text-sm">Nama Lengkap</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#f57c00]"
                   required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-5">
            <label class="block font-bold text-gray-600 mb-1 text-sm">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#f57c00]"
                   required>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="hideEditModal()"
                    class="flex-1 border border-gray-300 rounded-xl py-2 font-bold text-gray-600 hover:bg-gray-100 transition">
                Batal
            </button>
            <button type="submit"
                    class="flex-1 bg-[#f57c00] text-white rounded-xl py-2 font-extrabold hover:bg-[#ef6c00] transition shadow">
                Simpan
            </button>
        </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
// ===== MODAL EDIT =====
function showEditModal() {
    document.getElementById('modalEditProfil').classList.remove('hidden');
    document.getElementById('modalEditProfil').classList.add('flex');
}
function hideEditModal() {
    document.getElementById('modalEditProfil').classList.add('hidden');
    document.getElementById('modalEditProfil').classList.remove('flex');
}

// Tutup modal klik di luar
window.addEventListener('click', function(e) {
    const modal = document.getElementById('modalEditProfil');
    if (e.target === modal) {
        hideEditModal();
    }
});

// Logout SweetAlert
function confirmLogout() {
    Swal.fire({
        title: 'Logout dari DimsumYummy?',
        text: 'Pastikan pesananmu sudah aman!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f57c00',
        cancelButtonColor: '#6B3F2D',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        background: '#FFF8F0',
        color: '#6B3F2D'
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('sidebarLogoutForm').submit();
        }
    });
}

// Flash message sukses
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false,
        background: '#FFF8E7',
        color: '#5A2E1A'
    });
@endif
</script>
@endpush