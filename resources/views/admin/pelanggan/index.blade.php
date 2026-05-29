@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- HEADER dengan aksen --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1.5 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#2C1A12]">
                    👥 Data Pelanggan
                </h1>
            </div>
            <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                <i class="fas fa-users text-[#FB7E3C] text-xs"></i>
                Total terdaftar: 
                <span class="font-extrabold text-[#FB7E3C] bg-orange-50 px-2 py-0.5 rounded-full text-sm">
                    {{ $pelanggans->count() }} Orang
                </span>
            </p>
        </div>

        {{-- Search dengan icon lebih halus --}}
        <form action="{{ route('admin.pelanggan.index') }}" method="GET" class="relative group w-full md:w-80">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-[#FB7E3C] transition text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau username..."
                   class="w-full pl-10 pr-4 py-3 bg-white/80 backdrop-blur-sm border border-stone-200 rounded-2xl focus:border-[#FB7E3C] focus:ring-2 focus:ring-orange-200 outline-none transition shadow-sm hover:shadow">
        </form>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                background: '#FEF5E7',
                color: '#5A2E1A'
            });
        </script>
    @endif

    {{-- CARD UTAMA TABEL --}}
    <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 overflow-hidden transition hover:shadow-xl">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-orange-200">
            <table class="min-w-full divide-y divide-stone-100">
                <thead>
                    <tr class="bg-gradient-to-r from-stone-50 to-orange-50/30">
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">No</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Nama</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Email</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Username</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">No. HP</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Alamat</th>
                        <th class="px-6 py-5 text-center text-xs font-black uppercase tracking-wider text-stone-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($pelanggans as $index => $user)
                    <tr class="hover:bg-orange-50/40 transition duration-150 group">
                        <td class="px-6 py-4 text-sm text-stone-400 font-medium">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FB7E3C] to-[#D95C1A] flex items-center justify-center text-white text-xs font-bold shadow">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-[#3A2418]">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-500">
                            <span class="bg-stone-100 px-2 py-1 rounded-full text-xs font-mono">@ {{ $user->username ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">
                            {{ $user->hp ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-500 max-w-[220px] truncate" title="{{ $user->alamat }}">
                            <i class="fas fa-location-dot text-[#FB7E3C] text-xs mr-1"></i>
                            {{ $user->alamat ?: '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                {{-- Reset Password --}}
                                <button onclick="confirmReset('{{ $user->id }}', '{{ $user->name }}')"
                                        class="p-2.5 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 shadow-sm group/btn"
                                        title="Reset password">
                                    <i class="fas fa-key text-sm group-hover/btn:rotate-12 transition"></i>
                                </button>

                                {{-- Hapus Pelanggan --}}
                                <button onclick="confirmDelete('{{ $user->id }}')"
                                        class="p-2.5 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm"
                                        title="Hapus pelanggan">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>

                                <form id="reset-form-{{ $user->id }}" action="{{ route('admin.pelanggan.reset', $user->id) }}" method="POST" class="hidden">@csrf</form>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.pelanggan.destroy', $user->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-slash text-2xl text-stone-300"></i>
                                </div>
                                <p class="text-stone-400 font-medium">Tidak ada pelanggan ditemukan.</p>
                                @if(request('search'))
                                    <a href="{{ route('admin.pelanggan.index') }}" class="text-sm text-[#FB7E3C] hover:underline">↺ Reset filter</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tambahan pagination jika nanti ada --}}
    @if(method_exists($pelanggans, 'links') && $pelanggans->hasPages())
        <div class="mt-6">
            {{ $pelanggans->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmReset(id, nama) {
        Swal.fire({
            title: 'Reset password?',
            html: `Pengguna <strong>${nama}</strong> akan mendapatkan password default: <code class="bg-stone-100 px-1 rounded">password123</code>.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#FB7E3C',
            cancelButtonColor: '#78716C',
            confirmButtonText: 'Ya, reset',
            cancelButtonText: 'Batal',
            background: '#FFFCF9',
            color: '#2C1A12'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById(`reset-form-${id}`).submit();
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus pelanggan?',
            text: "Data akan dihapus permanen dan tidak bisa dikembalikan.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#78716C',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: '#FFFCF9',
            color: '#2C1A12'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById(`delete-form-${id}`).submit();
        });
    }
</script>
@endpush
@endsection