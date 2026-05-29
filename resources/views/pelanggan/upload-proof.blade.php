@extends('layouts.app')

@section('title', 'Upload Bukti Transfer - DimsumYummy')

@section('content')
<div class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-2xl">

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-orange-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-1.5 bg-gradient-to-b from-orange-500 to-orange-700 rounded-full"></div>
                <h1 class="text-2xl font-black text-stone-800">📤 Upload Bukti Transfer</h1>
            </div>

            <div class="bg-blue-50 p-4 rounded-xl mb-6">
                <p class="text-sm text-blue-800">Transaksi #{{ $transaksi->id }}</p>
                <p class="text-xs text-blue-600 mt-1">Total: Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
            </div>

            @if($transaksi->payment_proof)
            <div class="mb-4 p-3 bg-green-50 rounded-xl border border-green-200">
                <p class="text-sm text-green-700 font-semibold flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Bukti sudah diupload
                </p>
                <a href="{{ asset('storage/' . $transaksi->payment_proof) }}" target="_blank" class="text-xs text-green-600 underline mt-1 inline-block">
                    Lihat Bukti Sekarang
                </a>
            </div>
            @endif

            <form action="{{ route('pelanggan.upload.proof', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">Foto / Screenshot Bukti Transfer</label>
                    <div class="border-2 border-dashed border-orange-200 rounded-xl p-6 text-center hover:border-orange-400 transition cursor-pointer" onclick="document.getElementById('proofInput').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-orange-300 mb-2"></i>
                        <p class="text-sm text-stone-500">Klik untuk upload atau drag & drop</p>
                        <p class="text-xs text-stone-400 mt-1">JPG, PNG, maks 2MB</p>
                        <input type="file" name="payment_proof" id="proofInput" class="hidden" accept="image/jpeg,image/png" required>
                    </div>
                    <div id="previewContainer" class="mt-3 hidden">
                        <p class="text-sm text-green-600"><i class="fas fa-file-image"></i> <span id="fileName"></span></p>
                    </div>
                    @error('payment_proof')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('pelanggan.history') }}" class="px-5 py-2 bg-stone-200 text-stone-700 rounded-xl text-sm font-semibold hover:bg-stone-300 transition">Batal</a>
                    <button type="submit" class="px-5 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition">Upload Bukti</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('proofInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('previewContainer');
            const fileNameSpan = document.getElementById('fileName');
            fileNameSpan.innerText = file.name;
            preview.classList.remove('hidden');
        }
    });
</script>
@endpush