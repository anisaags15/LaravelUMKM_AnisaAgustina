@extends('layouts.app')

@section('title', 'Tentang Kami - DimsumYummy')

@section('content')
<style>
    .fade-up {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1);
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
            <i class="fas fa-utensils text-orange-500 text-3xl"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-stone-800 tracking-tight mb-3">Tentang <span class="text-orange-500">DimsumYummy</span></h1>
        <p class="text-lg md:text-xl text-stone-600 max-w-2xl mx-auto">Hadir dengan cita rasa istimewa & bahan berkualitas 🍤</p>
    </div>
</section>

<!-- About Section (Intro) -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex flex-col md:flex-row items-center gap-10">
            <div class="md:w-1/2 fade-up">
                <img src="{{ asset('img/logo.jpg') }}" alt="DimsumYummy" 
                     class="rounded-3xl shadow-xl max-h-[400px] w-full object-cover hover:scale-105 transition-transform duration-500 border-4 border-orange-100">
            </div>
            <div class="md:w-1/2 text-center md:text-left fade-up">
                <div class="inline-block px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-xs font-bold mb-4">✨ Tentang Kami</div>
                <h2 class="text-3xl md:text-4xl font-black text-stone-800 mb-4">Setiap Gigitan <span class="text-orange-500">Penuh Cinta</span></h2>
                <p class="text-stone-600 leading-relaxed mb-6">
                    Selamat datang di <span class="font-bold text-orange-500">DimsumYummy</span>!  
                    Kami adalah UMKM lokal yang menghadirkan dimsum lezat dengan bahan berkualitas, 
                    resep autentik, dan penuh cinta. Setiap gigitan adalah perpaduan sempurna rasa gurih 
                    dan lembut, dibuat segar setiap hari untuk kamu yang istimewa.
                </p>
                <a href="{{ route('produk.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition-transform hover:scale-105">
                    Lihat Produk Kami <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Cerita UMKM (Card) -->
<section class="py-12 bg-gradient-to-br from-amber-50 to-orange-50">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="bg-white rounded-3xl shadow-xl p-8 text-center fade-up border border-orange-100">
            <i class="fas fa-store text-orange-400 text-4xl mb-3"></i>
            <h2 class="text-2xl md:text-3xl font-black text-stone-800 mb-3">Cerita Kami</h2>
            <p class="text-stone-600 leading-relaxed max-w-2xl mx-auto">
                DimsumYummy lahir dari dapur kecil di Cirebon dengan mimpi besar menghadirkan dimsum lezat dan sehat bagi semua orang.
                Dibuat dengan bahan segar, tanpa pengawet, dan diolah dengan cinta — kami percaya setiap gigitan adalah kebahagiaan.
            </p>
        </div>
    </div>
</section>

<!-- Visi & Misi -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-orange-50 rounded-3xl p-8 shadow-md hover:shadow-xl transition-all fade-up border border-orange-100">
                <div class="w-14 h-14 bg-orange-200 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-bullseye text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-stone-800 mb-2">Visi</h3>
                <p class="text-stone-600 leading-relaxed">Membawa cita rasa dimsum autentik ke setiap meja makan di Indonesia dengan kualitas terbaik dan harga terjangkau.</p>
            </div>
            <div class="bg-stone-50 rounded-3xl p-8 shadow-md hover:shadow-xl transition-all fade-up border border-stone-100">
                <div class="w-14 h-14 bg-stone-200 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-lightbulb text-stone-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-stone-800 mb-2">Misi</h3>
                <ul class="text-stone-600 space-y-2 list-disc list-inside">
                    <li>Menggunakan bahan segar & alami tanpa pengawet</li>
                    <li>Memberikan pelayanan terbaik untuk setiap pelanggan</li>
                    <li>Menghadirkan variasi rasa yang kreatif dan inovatif</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Keunggulan -->
<section class="py-16 bg-gradient-to-br from-amber-50 to-orange-50">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-10 fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-stone-800 mb-2">Kenapa Pilih <span class="text-orange-500">DimsumYummy</span>?</h2>
            <p class="text-stone-500">Nikmati kelezatan dengan berbagai keunggulan kami</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all fade-up border border-orange-100">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-orange-500 text-2xl"></i>
                </div>
                <h4 class="text-xl font-bold text-stone-800 mb-2">Bahan Segar</h4>
                <p class="text-stone-500 text-sm">Dipilih langsung dari sumber terbaik, dikirim setiap hari.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all fade-up border border-orange-100">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-orange-500 text-2xl"></i>
                </div>
                <h4 class="text-xl font-bold text-stone-800 mb-2">Dibuat dengan Cinta</h4>
                <p class="text-stone-500 text-sm">Setiap dimsum dibuat manual dengan perhatian penuh.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all fade-up border border-orange-100">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-orange-500 text-2xl"></i>
                </div>
                <h4 class="text-xl font-bold text-stone-800 mb-2">Rasa Istimewa</h4>
                <p class="text-stone-500 text-sm">Resep turun-temurun dengan bumbu rahasia khas keluarga.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl text-center fade-up">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-3xl p-10 shadow-xl">
            <h2 class="text-3xl md:text-4xl font-black text-white mb-3">Siap Menikmati DimsumYummy?</h2>
            <p class="text-orange-100 mb-6">Pesan sekarang dan rasakan kelezatannya di rumah Anda!</p>
            <a href="{{ route('produk.index') }}" 
               class="inline-flex items-center gap-2 px-8 py-3 bg-white text-orange-600 font-bold rounded-xl shadow-md hover:bg-orange-50 transition-transform hover:scale-105">
                <i class="fas fa-shopping-cart"></i> Pesan Sekarang
            </a>
        </div>
    </div>
</section>

<script>
    // Fade-up animation on scroll
    const fadeElements = document.querySelectorAll('.fade-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });
    fadeElements.forEach(el => observer.observe(el));
</script>
@endsection