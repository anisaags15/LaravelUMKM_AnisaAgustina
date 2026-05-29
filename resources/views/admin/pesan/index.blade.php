@extends('admin.layouts.app')

@section('title', 'Pesan Masuk - Admin DimsumYummy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- HEADER dengan aksen gradasi --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="h-8 w-1.5 bg-gradient-to-b from-[#FB7E3C] to-[#D95C1A] rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-[#2C1A12]">
                    📩 Pesan Masuk
                </h1>
            </div>
            <p class="text-sm text-stone-500 ml-5 flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-[#FB7E3C] text-xs"></i>
                Total pesan:
                <span class="font-extrabold text-[#FB7E3C] bg-orange-50 px-2 py-0.5 rounded-full text-sm">
                    {{ $pesans->count() }} Pesan
                </span>
            </p>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="bg-white rounded-3xl shadow-lg shadow-stone-200/50 border border-stone-100 overflow-hidden transition hover:shadow-xl">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-orange-200">
            <table class="min-w-full divide-y divide-stone-100">
                <thead class="bg-gradient-to-r from-stone-50 to-orange-50/30">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500 rounded-tl-xl">Nama</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Email</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Isi Pesan</th>
                        <th class="px-6 py-5 text-left text-xs font-black uppercase tracking-wider text-stone-500">Tanggal</th>
                        <th class="px-6 py-5 text-center text-xs font-black uppercase tracking-wider text-stone-500 rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($pesans as $pesan)
                    <tr class="hover:bg-orange-50/40 transition duration-150 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FB7E3C] to-[#D95C1A] flex items-center justify-center text-white text-xs font-bold shadow">
                                    {{ strtoupper(substr($pesan->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-[#3A2418]">{{ $pesan->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">
                            <a href="mailto:{{ $pesan->email }}" class="hover:text-[#FB7E3C] transition">
                                {{ $pesan->email }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600 max-w-[300px] truncate" title="{{ $pesan->message }}">
                            {{ Str::limit($pesan->message, 60) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-500 whitespace-nowrap">
                            <i class="far fa-calendar-alt text-[#FB7E3C] text-xs mr-1"></i>
                            {{ $pesan->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="confirmDelete({{ $pesan->id }})"
                                    class="p-2 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm"
                                    title="Hapus Pesan">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $pesan->id }}" action="{{ route('admin.pesan.destroy', $pesan->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300"></i>
                                </div>
                                <p class="text-stone-400 font-medium">Belum ada pesan masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION (jika menggunakan paginate) --}}
    @if(method_exists($pesans, 'links') && $pesans->hasPages())
        <div class="mt-6">
            {{ $pesans->links() }}
        </div>
    @endif

    {{-- MOBILE CARD VIEW --}}
    <div class="md:hidden mt-6 space-y-3">
        @foreach($pesans as $pesan)
        <div class="bg-white rounded-2xl shadow p-4 border border-stone-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FB7E3C] to-[#D95C1A] flex items-center justify-center text-white text-xs font-bold shadow">
                        {{ strtoupper(substr($pesan->name, 0, 1)) }}
                    </div>
                    <p class="font-bold text-[#2C1A12]">{{ $pesan->name }}</p>
                </div>
                <span class="text-xs text-stone-400">{{ $pesan->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <p class="text-sm text-stone-600 break-words mt-1">{{ $pesan->message }}</p>
            <div class="flex justify-end mt-3">
                <button onclick="confirmDelete({{ $pesan->id }})"
                        class="px-3 py-1.5 bg-rose-50 text-rose-500 rounded-xl text-xs font-semibold hover:bg-rose-500 hover:text-white transition">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus pesan?',
            text: "Pesan ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#78716C',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: '#FFFCF9',
            color: '#2C1A12'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

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
</script>
@endpush