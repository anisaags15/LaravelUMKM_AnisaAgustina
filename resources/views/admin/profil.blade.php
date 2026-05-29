@extends('admin.layouts.app')

@section('title', 'Profil Admin - DimsumYummy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- HEADER dengan aksen gradasi --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1.5 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#2C1A12]">
                    👤 Profil Admin
                </h1>
            </div>
            <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                <i class="fas fa-user-shield text-[#FB7E3C] text-xs"></i>
                Kelola data diri dan keamanan akun
            </p>
        </div>
    </div>

    {{-- GRID 2 kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CARD FOTO PROFIL --}}
        <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 p-6 transition hover:shadow-xl">
            <div class="flex flex-col items-center text-center">
                {{-- Foto --}}
                <div class="relative mb-4 group">
                    <img id="fotoPreview"
                         src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=FB7E3C&color=fff&size=200' }}"
                         alt="Foto Admin"
                         class="w-36 h-36 rounded-full object-cover border-4 border-white shadow-xl ring-2 ring-[#FB7E3C]">
                    <label for="uploadFotoInput"
                           class="absolute bottom-1 right-1 bg-white text-[#FB7E3C] w-9 h-9 rounded-full flex items-center justify-center shadow-md border border-stone-200 cursor-pointer hover:bg-[#FB7E3C] hover:text-white transition-all duration-200">
                        <i class="fas fa-camera text-sm"></i>
                    </label>
                </div>

                {{-- Info --}}
                <h3 class="text-xl font-extrabold text-[#2C1A12] mb-1">
                    {{ $user->nama ?? $user->name }}
                </h3>
                <span class="px-3 py-1 bg-gradient-to-r from-[#FB7E3C]/10 to-[#D95C1A]/10 text-[#D95C1A] text-xs font-bold rounded-full uppercase tracking-wider mb-4 inline-flex items-center gap-1">
                    <i class="fas fa-shield-alt text-[10px]"></i> {{ $user->role }}
                </span>

                <div class="w-full space-y-2 text-sm text-left">
                    <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                        <i class="fas fa-envelope text-[#FB7E3C] w-4"></i>
                        <span class="text-stone-700 truncate">{{ $user->email }}</span>
                    </div>
                    @if($user->hp ?? false)
                    <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                        <i class="fas fa-phone text-[#FB7E3C] w-4"></i>
                        <span class="text-stone-700">{{ $user->hp }}</span>
                    </div>
                    @endif
                    @if($user->alamat ?? false)
                    <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                        <i class="fas fa-map-marker-alt text-[#FB7E3C] w-4"></i>
                        <span class="text-stone-700 text-xs">{{ $user->alamat }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                        <i class="fas fa-calendar-alt text-[#FB7E3C] w-4"></i>
                        <span class="text-stone-700 text-xs">
                            Bergabung {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Form upload foto --}}
                <form action="{{ route('admin.profil.foto') }}" method="POST" enctype="multipart/form-data" id="uploadFotoForm" class="w-full mt-4">
                    @csrf
                    <input type="file" id="uploadFotoInput" name="profile_picture" accept="image/jpg,image/jpeg,image/png,image/gif" class="hidden">
                    @error('profile_picture')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <button type="submit" id="uploadFotoBtn"
                            class="hidden w-full bg-[#FB7E3C] text-white py-2 rounded-xl font-bold text-sm hover:bg-[#D95C1A] transition mt-2 shadow-sm">
                        <i class="fas fa-upload mr-1"></i> Upload Foto
                    </button>
                </form>
            </div>
        </div>

        {{-- FORM EDIT & PASSWORD --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Edit Data Diri --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 p-6 transition hover:shadow-xl">
                <div class="flex items-center gap-3 mb-4 pb-2 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FB7E3C] to-[#D95C1A] flex items-center justify-center text-white shadow">
                        <i class="fas fa-user-edit text-sm"></i>
                    </div>
                    <h2 class="text-lg font-black text-[#2C1A12]">Edit Data Diri</h2>
                </div>

                <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama"
                                   value="{{ old('nama', $user->nama ?? $user->name) }}"
                                   class="w-full border border-stone-200 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent transition @error('nama') border-red-400 @enderror"
                                   required>
                            @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-1">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full border border-stone-200 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent transition @error('email') border-red-400 @enderror"
                                   required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-[#FB7E3C] text-white rounded-xl font-bold text-sm hover:bg-[#D95C1A] transition shadow-md hover:scale-105">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 p-6 transition hover:shadow-xl">
                <div class="flex items-center gap-3 mb-4 pb-2 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-stone-400 to-stone-600 flex items-center justify-center text-white shadow">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <h2 class="text-lg font-black text-[#2C1A12]">Ganti Password</h2>
                </div>

                <form action="{{ route('admin.profil.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-bold text-stone-600 mb-1">Password Lama</label>
                        <input type="password" name="current_password"
                               class="w-full border border-stone-200 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent transition"
                               required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-1">Password Baru</label>
                            <input type="password" name="password"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full border border-stone-200 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent transition"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-600 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full border border-stone-200 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent transition"
                                   required>
                        </div>
                    </div>

                    @if(session('error_password'))
                        <p class="text-red-500 text-sm flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ session('error_password') }}</p>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-stone-700 text-white rounded-xl font-bold text-sm hover:bg-stone-800 transition shadow-md hover:scale-105">
                            <i class="fas fa-key mr-1"></i> Ganti Password
                        </button>
                    </div>
                </form>
            </div>
<!-- 
            {{-- Tombol Kembali ke Dashboard --}}
            <div class="flex justify-start">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-[#6B3F2D] text-white rounded-xl font-semibold text-sm hover:bg-[#4A2C1F] transition shadow-md hover:scale-105">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
                </a>
            </div> -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('uploadFotoInput').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('fotoPreview').src = e.target.result;
                document.getElementById('uploadFotoBtn').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

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

    @if(session('error_password'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error_password') }}",
            confirmButtonColor: '#E11D48',
            background: '#FFFCF9',
            color: '#2C1A12'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Periksa Form!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#FB7E3C',
            background: '#FFFCF9',
            color: '#2C1A12'
        });
    @endif
</script>
@endpush