@extends('layouts.app')

@section('title', 'Kontak - DimsumYummy')

@section('content')
<style>
    .contact-card {
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    }
    .contact-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
    }
    .fade-up {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.7s ease-out;
    }
    .fade-up.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-amber-50 to-orange-100 py-16 text-center fade-up">
    <div class="container mx-auto px-4">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-4 shadow-md">
            <i class="fas fa-envelope-open-text text-orange-500 text-3xl"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-stone-800 tracking-tight mb-3">💌 Hubungi <span class="text-orange-500">Kami</span></h1>
        <p class="text-lg text-stone-600 max-w-2xl mx-auto">Sahabat Yummy, yuk ngobrol sama kami! 🍤</p>
    </div>
</section>

<!-- Konten Utama -->
<div class="container mx-auto px-4 py-16 max-w-6xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Info Kontak (kiri) -->
        <div class="contact-card bg-white rounded-3xl p-8 shadow-lg border border-orange-100 fade-up">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-address-card text-orange-500 text-lg"></i>
                </div>
                <h3 class="text-2xl font-black text-stone-800">Informasi Kontak</h3>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                    <i class="fas fa-map-marker-alt w-5 text-orange-500"></i>
                    <span class="text-stone-700">Jl. Angkasa Raya No. 123, Cirebon</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                    <i class="fas fa-phone-alt w-5 text-orange-500"></i>
                    <span class="text-stone-700">+62 823-1759-20647</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-xl">
                    <i class="fas fa-envelope w-5 text-orange-500"></i>
                    <span class="text-stone-700">yummydimsum@gmail.com</span>
                </div>
            </div>
            
            <!-- Sosial Media -->
            <div class="mt-8">
                <p class="text-sm font-semibold text-stone-500 mb-3">Ikuti kami</p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://wa.me/62823175920647" target="_blank" class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Kirim Pesan (kanan) -->
        <div class="contact-card bg-white rounded-3xl p-8 shadow-lg border border-orange-100 fade-up">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-paper-plane text-orange-500 text-lg"></i>
                </div>
                <h3 class="text-2xl font-black text-stone-800">Kirim Pesan</h3>
            </div>

            @if(session('success'))
                <div class="mb-5 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-xl text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('kontak.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-stone-600 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm"></i>
                        <input type="text" name="name" placeholder="Masukkan nama Anda" required 
                               class="w-full pl-11 pr-4 py-3 border border-stone-200 rounded-xl focus:border-orange-400 focus:ring-2 focus:ring-orange-200 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-600 mb-1">Alamat Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 text-sm"></i>
                        <input type="email" name="email" placeholder="email@contoh.com" required 
                               class="w-full pl-11 pr-4 py-3 border border-stone-200 rounded-xl focus:border-orange-400 focus:ring-2 focus:ring-orange-200 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-600 mb-1">Pesan</label>
                    <div class="relative">
                        <i class="fas fa-comment-dots absolute left-4 top-4 text-stone-400 text-sm"></i>
                        <textarea name="message" rows="4" placeholder="Tulis pesanmu di sini..." required 
                               class="w-full pl-11 pr-4 py-3 border border-stone-200 rounded-2xl focus:border-orange-400 focus:ring-2 focus:ring-orange-200 outline-none transition resize-none"></textarea>
                    </div>
                </div>
                <button type="submit" 
                        class="w-full md:w-auto px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Fade-up animation
    const fadeElements = document.querySelectorAll('.fade-up');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.2 });
    fadeElements.forEach(el => observer.observe(el));
</script>
@endsection