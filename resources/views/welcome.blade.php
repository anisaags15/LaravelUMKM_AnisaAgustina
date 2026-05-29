@php
    $cartCount = count(session('cart', []));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DimsumYummy - Website UMKM</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&family=Baloo+2:wght@400;600;700&family=Lora:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html { scroll-behavior: smooth; }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 4s ease-in-out infinite; }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        .swiper-pagination { position: static !important; text-align: center; }
        .swiper-pagination-bullet { background-color: #fb7e3c; opacity: 0.3; width: 8px; height: 8px; margin: 0 4px !important; transition: all 0.3s ease; }
        .swiper-pagination-bullet-active { opacity: 1; transform: scale(1.3); }
        /* Dropdown menu */
        .dropdown-menu {
            transition: all 0.2s ease;
            transform-origin: top right;
        }
        .dropdown-menu.show {
            display: block !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
{{-- ======================= NAVBAR ======================= --}}
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between relative">

        {{-- Logo --}}
        <div class="flex items-center space-x-3">
            <img src="{{ asset('img/logoyummy.jpeg') }}" alt="Logo" class="h-10 w-auto rounded-full shadow-md">
            <span class="text-xl font-bold text-[#FB7E3C] tracking-tight">DimsumYummy</span>
        </div>

        {{-- Menu Tengah (simetris) --}}
        <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 space-x-6 text-[#6B3F2D] font-medium">
            <a href="{{ route('welcome') }}" class="transition border-b-2 border-[#FB7E3C]">Beranda</a>
            <a href="{{ route('produk.index') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C]">Produk</a>
            <a href="{{ route('welcome') }}#kategori" class="transition hover:border-b-2 hover:border-[#FB7E3C]">Kategori</a>
            <a href="{{ route('tentang') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C]">Tentang Kami</a>
            <a href="{{ route('kontak') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C]">Kontak</a>
        </div>

        {{-- Kanan: Search + Cart + Auth --}}
        <div class="flex items-center space-x-4">

            {{-- Search dengan ikon cantik --}}
            <form method="GET" action="{{ route('produk.index') }}" class="hidden md:flex">
                <div class="relative">
                    <input type="text" name="q"
                           placeholder="Cari produk..."
                           value="{{ request('q') }}"
                           class="px-4 py-2 pl-10 border border-gray-200 rounded-l-full focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent w-48 transition-all">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white rounded-r-full hover:from-[#E56727] hover:to-[#d45a1a] transition shadow-md hover:shadow-lg transform hover:scale-105">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            {{-- Cart dengan badge dan lingkaran background --}}
@php
    $cartCount = count(session('cart', []));
@endphp
            <a href="{{ route('pelanggan.cart.index') }}" class="relative group">
                <div class="p-2 rounded-full bg-gray-100 group-hover:bg-orange-100 transition-all duration-300 transform group-hover:scale-110">
                    <i class="fas fa-shopping-cart text-xl text-[#6B3F2D] group-hover:text-[#FB7E3C] transition-colors"></i>
                </div>
                <span id="cart-count"
                      class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full shadow-md {{ $cartCount == 0 ? 'hidden' : '' }}">
                    {{ $cartCount }}
                </span>
            </a>

            {{-- Auth Section --}}
            @auth
            <div class="relative" id="profileDropdownContainer">
                <button id="profileDropdownBtn"
                        class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-200 hover:border-[#FB7E3C] transition-all duration-200 focus:outline-none shadow-md hover:shadow-lg transform hover:scale-105">
                    <img src="{{ Auth::user()->profile_picture
                                    ? asset('storage/' . Auth::user()->profile_picture)
                                    : asset('img/default_profile.png') }}"
                         alt="Profile" class="w-full h-full object-cover">
                </button>
                <div id="profileDropdownMenu"
                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-100 dropdown-menu z-20">
                    <a href="{{ route('pelanggan.profil.show') }}"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 transition">
                        <i class="fas fa-user mr-2 text-orange-500"></i> Profil Saya
                    </a>
                    <button id="logoutBtnDropdown"
                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 transition">
                        <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Logout
                    </button>
                </div>
            </div>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden md:block px-5 py-2 text-[#FB7E3C] border border-[#FB7E3C] rounded-full hover:bg-[#FB7E3C] hover:text-white transition-all font-semibold shadow-sm hover:shadow-md">
                   Login
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white rounded-full hover:from-[#E56727] hover:to-[#d45a1a] transition-all font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
                   Register
                </a>
            @endauth
        </div>
    </div>
</nav>
{{-- ======================= HERO CAROUSEL ======================= --}}
<section class="relative overflow-hidden bg-gradient-to-r from-[#FFF1E0] via-[#FFE8CC] to-[#FFD8A9]" id="heroPromo">
    <div id="mainCarousel">

        {{-- Slide 1: Hero --}}
        <div class="carousel-item w-full">
            <div class="bg-[#FFF1E0] py-24 px-6 md:py-32 md:px-12">
                <div class="container mx-auto flex flex-col md:flex-row items-center gap-12">
                    <div class="md:w-1/2 space-y-6">
                        <h1 class="text-4xl md:text-6xl font-extrabold text-[#A16207] leading-tight drop-shadow-lg font-serif">
                            Selamat datang di
                            <span class="text-[#FF7A00] font-extrabold italic">DimsumYummy</span> 🍤
                        </h1>
                        <p class="text-base md:text-xl text-[#7C2D12] leading-relaxed font-medium font-serif">
                            Nikmati dimsum fresh & berkualitas setiap hari.<br class="hidden md:block"/>
                            Sempurna untuk ngemil, sarapan, atau momen spesialmu. 😋💕
                        </p>
                        <a href="{{ route('produk.index') }}"
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#FF7A00] to-[#DD5A00] hover:from-[#DD5A00] hover:to-[#BB3A00] text-white text-lg font-semibold px-7 py-3 rounded-2xl shadow-xl transition duration-300 transform hover:scale-110 animate-bounce">
                            Belanja Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <div class="rounded-2xl shadow-xl border border-[#FFB26B] hover:scale-105 transition duration-300 overflow-hidden">
                            <img src="{{ asset('img/slide1.jpg') }}" alt="Dimsum" class="w-full max-w-xs md:max-w-md object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 2: Promo --}}
        <div class="carousel-item w-full" style="display:none;">
            <div class="bg-[#FFF7F0] py-24 px-6 md:py-32 md:px-12">
                <div class="container mx-auto flex flex-col md:flex-row items-center gap-12">
                    <div class="md:w-1/2 space-y-6">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-[#B91C1C] leading-tight font-serif">
                            Dimsum kami lagi banyak
                            <span class="text-[#FB7E3C] italic font-extrabold">diskon</span> ❤‍🔥
                        </h2>
                        <p class="text-base md:text-xl text-[#7C2D12] leading-relaxed font-medium font-serif">
                            Buruan serbuuu!!! <span class="font-semibold text-[#B91C1C]">Jangan sampai kehabisan!</span>
                        </p>
                        <a href="{{ route('welcome') }}#promo"
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#FB7E3C] to-[#DD5A00] hover:from-[#DD5A00] hover:to-[#BB3A00] text-white text-lg font-semibold px-7 py-3 rounded-2xl shadow-xl transition duration-300 transform hover:scale-110 animate-bounce">
                            Lihat Promo
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <div class="rounded-2xl shadow-xl border border-[#FFB26B] hover:scale-105 transition duration-300 overflow-hidden">
                            <img src="{{ asset('img/slide23.jpg') }}" alt="Promo" class="w-full max-w-xs md:max-w-md object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Carousel Controls --}}
    <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 z-10" onclick="prevSlide()">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 z-10" onclick="nextSlide()">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</section>

{{-- ======================= KATEGORI ======================= --}}
<section id="kategori" class="py-16 bg-gradient-to-b from-[#FFE5B4] via-[#FFDAB9] to-[#FFF1E5]">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-[#7C2D12] mb-8" style="font-family:'Lora',serif;">Kategori</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-white rounded-lg shadow-md p-6 text-center transition-transform transform hover:scale-105">
                <img src="{{ asset('img/original1.jpeg') }}" alt="Dimsum Kukus"
                     class="mx-auto mb-4 w-full h-56 md:h-64 object-cover rounded-md">
                <h3 class="text-xl font-semibold text-[#A16207] mb-4">Dimsum Kukus</h3>
                <p class="mb-3 text-[15px] text-[#5C4033] leading-relaxed pl-4 border-l-4 border-[#E6B17E] italic">
                    Pilihan klasik dengan tekstur lembut dan rasa autentik yang selalu jadi favorit.
                </p>
                <a href="{{ route('kategori.show', 'dimsum_kukus') }}"
                   class="mt-4 inline-block bg-[#FF7A00] text-white px-4 py-2 rounded-lg hover:bg-[#DD5A00] transition duration-300">
                   Lihat Kategori
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center transition-transform transform hover:scale-105">
                <img src="{{ asset('img/pangsitori.jpg') }}" alt="Pangsit"
                     class="mx-auto mb-4 w-full h-56 md:h-64 object-cover rounded-md">
                <h3 class="text-xl font-semibold text-[#A16207] mb-4">Pangsit</h3>
                <p class="mb-3 text-[15px] text-[#5C4033] leading-relaxed pl-4 border-l-4 border-[#E6B17E] italic">
                    Kerenyahan kulit tipis berpadu dengan isian gurih, sederhana namun istimewa.
                </p>
                <a href="{{ route('kategori.show', 'pangsit') }}"
                   class="mt-4 inline-block bg-[#FF7A00] text-white px-4 py-2 rounded-lg hover:bg-[#DD5A00] transition duration-300">
                   Lihat Kategori
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center transition-transform transform hover:scale-105">
                <img src="{{ asset('img/bdayjumboori.jpg') }}" alt="Dimsum Spesial"
                     class="mx-auto mb-4 w-full h-56 md:h-64 object-cover rounded-md">
                <h3 class="text-xl font-semibold text-[#A16207] mb-4">Dimsum Spesial</h3>
                <p class="mb-3 text-[15px] text-[#5C4033] leading-relaxed pl-4 border-l-4 border-[#E6B17E] italic">
                    Hadir dengan porsi lebih besar dan cita rasa premium untuk momen berkesan.
                </p>
                <a href="{{ route('kategori.show', 'dimsum_spesial') }}"
                   class="mt-4 inline-block bg-[#FF7A00] text-white px-4 py-2 rounded-lg hover:bg-[#DD5A00] transition duration-300">
                   Lihat Kategori
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ======================= PRODUK UNGGULAN ======================= --}}
<section class="py-16 bg-[#FFF1E0]">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-[#7C2D12] mb-8" style="font-family:'Lora',serif;">Produk Unggulan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($produkUnggulan as $item)
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-2">
                <div class="absolute top-3 left-3 bg-yellow-400 text-[#6B3F2D] text-xs font-bold px-3 py-1 rounded-full shadow-md animate-pulse">
                    ⭐⭐⭐ Best Seller
                </div>
                <img src="{{ asset('storage/' . $item->foto) }}"
                     alt="{{ $item->nama }}"
                     class="mx-auto mb-4 rounded-t-2xl w-full h-64 object-cover">
                <div class="p-5 flex flex-col">
                    <h3 class="text-lg font-semibold text-[#6B3F2D] mb-2 text-center">{{ $item->nama }}</h3>
                    <p class="mb-3 text-[15px] text-[#5C4033] leading-relaxed pl-4 border-l-4 border-[#E6B17E] italic">
                        {{ $item->deskripsi }}
                    </p>
                </div>
            </div>
            @empty
            <p class="col-span-3 text-center text-gray-400">Belum ada produk unggulan.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ======================= PROMO ======================= --}}
<section id="promo" class="py-20 bg-gradient-to-br from-orange-600 via-red-600 to-amber-700 text-white relative overflow-hidden">
    <!-- Background Pattern / Ornamen -->
    <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="%23ffffff"%3E%3Cpath d="M20,20 L30,20 L25,30 Z M70,70 L80,70 L75,80 Z M10,80 L20,80 L15,90 Z M80,10 L90,10 L85,20 Z M45,45 L55,45 L50,55 Z" stroke="white" stroke-width="1" fill="none"/%3E%3C/svg%3E')] bg-repeat"></div>
    
    <div class="container mx-auto px-4 max-w-screen-lg relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-extrabold mb-3 tracking-tight drop-shadow-lg animate-pulse">🎉 Promo Spesial Dimsum Yummy! 🎉</h2>
            <div class="w-24 h-1 bg-yellow-400 mx-auto rounded-full mb-4"></div>
            <p class="text-xl text-orange-100 font-medium">Nikmati berbagai promo menarik hanya di bulan ini!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1: Diskon 50% (ID 19) -->
            <div class="group bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-500 hover:scale-105 hover:shadow-orange-300/50 flex flex-col" data-aos="fade-up">
                <div class="relative">
                    <img src="{{ asset('img/sumkeju.jpg') }}" alt="Diskon 50%" class="w-full h-56 object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-500 to-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg transform -rotate-12">🔥 HOT DEAL</div>
                    <div class="absolute top-4 right-4 bg-red-600 text-white text-sm font-black px-2 py-1 rounded-full shadow-lg">-50%</div>
                </div>
                <div class="p-6 flex flex-col flex-1 bg-gradient-to-br from-white to-orange-50">
                    <h3 class="text-2xl font-black text-orange-600 mb-2">Diskon 50% <span class="text-stone-800">Dimsum Mentai</span></h3>
                    <p class="text-stone-600 text-sm mb-4 leading-relaxed">Berlaku setiap hari Jumat. Buruan sebelum kehabisan! 🍤</p>
                    <a href="{{ route('produk.index', ['promo' => 19]) }}"
                       class="mt-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-red-600 text-white px-5 py-2.5 rounded-full hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                       <i class="fas fa-eye"></i> Lihat Produk
                    </a>
                </div>
            </div>

            <!-- Card 2: Beli 2 Gratis 1 (ID 13) -->
            <div class="group bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-500 hover:scale-105 hover:shadow-orange-300/50 flex flex-col" data-aos="fade-up" data-aos-delay="100">
                <div class="relative">
                    <img src="{{ asset('img/pangsitchilioil.jpg') }}" alt="Beli 2 Gratis 1" class="w-full h-56 object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-500 to-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg transform -rotate-12">🎁 PROMO</div>
                    <div class="absolute top-4 right-4 bg-green-600 text-white text-xs font-black px-2 py-1 rounded-full shadow-lg">2+1</div>
                </div>
                <div class="p-6 flex flex-col flex-1 bg-gradient-to-br from-white to-orange-50">
                    <h3 class="text-2xl font-black text-orange-600 mb-2">Beli 2 <span class="text-stone-800">Gratis 1</span></h3>
                    <p class="text-stone-600 text-sm mb-4 leading-relaxed">Khusus hari ini, Pangsit Chili Oil gratis 1 lhoo! 🌶️</p>
                    <a href="{{ route('produk.index', ['promo' => 13]) }}"
                       class="mt-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-red-600 text-white px-5 py-2.5 rounded-full hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                       <i class="fas fa-eye"></i> Lihat Produk
                    </a>
                </div>
            </div>

            <!-- Card 3: Gratis Ongkir (ID 20) -->
            <div class="group bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-500 hover:scale-105 hover:shadow-orange-300/50 flex flex-col" data-aos="fade-up" data-aos-delay="200">
                <div class="relative">
                    <img src="{{ asset('img/dimsum3.jpg') }}" alt="Gratis Ongkir" class="w-full h-56 object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-500 to-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg transform -rotate-12">🚚 FREE</div>
                    <div class="absolute top-4 right-4 bg-blue-600 text-white text-xs font-black px-2 py-1 rounded-full shadow-lg">0 ONGKIR</div>
                </div>
                <div class="p-6 flex flex-col flex-1 bg-gradient-to-br from-white to-orange-50">
                    <h3 class="text-2xl font-black text-orange-600 mb-2">Diskon!</h3>
                    <p class="text-stone-600 text-sm mb-4 leading-relaxed">Khusus minggu ini untuk Varian Dimsum Bday Promo 20% . 📦</p>
                    <a href="{{ route('produk.index', ['promo' => 20]) }}"
                       class="mt-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-red-600 text-white px-5 py-2.5 rounded-full hover:from-orange-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                       <i class="fas fa-eye"></i> Lihat Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- ======================= CARA PEMESANAN (UPGRADE) ======================= --}}
<section class="py-20 bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 relative overflow-hidden">
    <div class="absolute top-20 left-10 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-pulse"></div>
    <div class="absolute bottom-10 right-0 w-80 h-80 bg-yellow-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-pulse delay-1000"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12">
            <span class="inline-block bg-orange-100 text-orange-600 text-sm font-bold px-4 py-1 rounded-full mb-3 shadow-sm">✨ Mudah & Cepat ✨</span>
            <h2 class="text-4xl md:text-5xl font-black text-stone-800 mb-3">Cara <span class="text-orange-500">Pemesanan</span></h2>
            <div class="w-24 h-1 bg-orange-400 mx-auto rounded-full"></div>
            <p class="text-stone-600 mt-3 max-w-xl mx-auto">Nikmati dimsum favoritmu dengan 3 langkah mudah</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-8 text-center border border-orange-100 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-100 rounded-full opacity-0 group-hover:opacity-30 transition duration-500"></div>
                <div class="text-6xl mb-4 group-hover:scale-110 transition-transform duration-300 inline-block">🛒</div>
                <div class="absolute top-3 left-3 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-black shadow-md">1</div>
                <h3 class="text-xl font-bold text-stone-800 mb-2">Pilih Produk</h3>
                <p class="text-stone-500">Pilih produk favorit dari menu kami. Ada banyak varian lezat!</p>
            </div>
            <!-- Step 2 -->
            <div class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-8 text-center border border-orange-100 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-100 rounded-full opacity-0 group-hover:opacity-30 transition duration-500"></div>
                <div class="text-6xl mb-4 group-hover:scale-110 transition-transform duration-300 inline-block">💳</div>
                <div class="absolute top-3 left-3 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-black shadow-md">2</div>
                <h3 class="text-xl font-bold text-stone-800 mb-2">Lakukan Pembayaran</h3>
                <p class="text-stone-500">Transfer, COD, atau e-wallet. Pilih metode yang mudah.</p>
            </div>
            <!-- Step 3 -->
            <div class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-8 text-center border border-orange-100 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-100 rounded-full opacity-0 group-hover:opacity-30 transition duration-500"></div>
                <div class="text-6xl mb-4 group-hover:scale-110 transition-transform duration-300 inline-block">🚚</div>
                <div class="absolute top-3 left-3 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-black shadow-md">3</div>
                <h3 class="text-xl font-bold text-stone-800 mb-2">Pesanan Dikirim</h3>
                <p class="text-stone-500">Dimsum fresh akan diantar ke alamat Anda. Siap disantap!</p>
            </div>
        </div>
    </div>
</section>

{{-- ======================= JAM OPERASIONAL (UPGRADE) ======================= --}}
<section class="py-20 bg-gradient-to-br from-amber-50 to-orange-100 relative overflow-hidden">
    <!-- Dekorasi -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-orange-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-pulse delay-700"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-block bg-white/60 backdrop-blur-sm text-orange-600 text-sm font-bold px-4 py-1 rounded-full mb-3 shadow-sm">🕒 Setiap Hari</span>
            <h2 class="text-5xl font-black text-stone-800 mb-4 flex items-center justify-center gap-3">
                <i class="fas fa-clock text-orange-500 text-4xl"></i> Jam Operasional
            </h2>
            <p class="text-lg text-orange-700 font-medium">Siap melayani Anda setiap hari dengan senyuman</p>
            <div class="w-24 h-1 bg-orange-400 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Card Jam Buka -->
            <div class="group">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-white/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-green-100 rounded-full -mr-10 -mt-10 opacity-30 group-hover:scale-150 transition duration-500"></div>
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl shadow-lg mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-sun text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-green-600 mb-1">Jam Buka</h3>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-gray-800 mb-1 tracking-tight">08:00</div>
                        <div class="text-sm text-gray-500 font-medium">WIB</div>
                        <div class="mt-3 inline-block bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded-full">
                            <i class="fas fa-coffee mr-1"></i> Sarapan Dimsum!
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Jam Tutup -->
            <div class="group">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-white/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-red-100 rounded-full -mr-10 -mt-10 opacity-30 group-hover:scale-150 transition duration-500"></div>
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl shadow-lg mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-moon text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-red-600 mb-1">Jam Tutup</h3>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-gray-800 mb-1 tracking-tight">21:00</div>
                        <div class="text-sm text-gray-500 font-medium">WIB</div>
                        <div class="mt-3 inline-block bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">
                            <i class="fas fa-utensils mr-1"></i> Makan Malam!
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Lokasi -->
            <div class="group">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-white/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-blue-100 rounded-full -mr-10 -mt-10 opacity-30 group-hover:scale-150 transition duration-500"></div>
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl shadow-lg mb-3 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-map-marker-alt text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-600 mb-1">Lokasi Kami</h3>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-800 mb-1">Jl. Angkasa No. 88</div>
                        <div class="text-sm text-gray-500 font-medium mb-2">Kota Cirebon</div>
                        <div class="mt-3 inline-block bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full">
                            <i class="fas fa-directions mr-1"></i> Google Maps Ready!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <div class="inline-flex flex-wrap items-center justify-center gap-4 bg-white/70 backdrop-blur-sm rounded-full px-8 py-4 shadow-lg border border-orange-200">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-gray-700">Buka Setiap Hari</span>
                </div>
                <div class="w-px h-6 bg-gray-300 hidden sm:block"></div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-phone text-orange-500"></i>
                    <span class="text-sm font-medium text-gray-700">+62 823-1759-20647</span>
                </div>
                <div class="w-px h-6 bg-gray-300 hidden sm:block"></div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shopping-cart text-orange-500"></i>
                    <a href="{{ route('produk.index') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700 transition">Pesan Online</a>
                </div>
            </div>
        </div>
    </div>
</section>{{-- ======================= KEUNGGULAN ======================= --}}
<section class="py-20 bg-gradient-to-b from-white to-orange-50">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-stone-800 mb-3">✨ Keunggulan <span class="text-orange-500">Produk Kami</span></h2>
            <div class="w-24 h-1 bg-orange-500 mx-auto rounded-full"></div>
            <p class="text-stone-500 mt-3">Kenapa harus memilih DimsumYummy?</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-orange-100" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-md group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-stone-800 mb-2">Halal Terjamin</h3>
                <p class="text-stone-500 text-sm leading-relaxed">Seluruh produk kami telah memiliki sertifikasi halal dan menggunakan bahan-bahan terbaik.</p>
            </div>
            <div class="group bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-orange-100" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-md group-hover:scale-110 transition">
                    <i class="fas fa-leaf text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-stone-800 mb-2">Bahan Fresh</h3>
                <p class="text-stone-500 text-sm leading-relaxed">Dimsum dibuat setiap hari dengan bahan segar pilihan, tanpa pengawet.</p>
            </div>
            <div class="group bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-orange-100" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-md group-hover:scale-110 transition">
                    <i class="fas fa-tags text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-stone-800 mb-2">Harga Terjangkau</h3>
                <p class="text-stone-500 text-sm leading-relaxed">Nikmati dimsum premium dengan harga yang ramah di kantong.</p>
            </div>
        </div>
    </div>
</section>

{{-- ======================= TESTIMONI ======================= --}}
<section class="py-20 bg-gradient-to-br from-orange-50 to-amber-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-stone-800 mb-3">💬 Testimoni <span class="text-orange-500">Pelanggan</span></h2>
            <div class="w-24 h-1 bg-orange-500 mx-auto rounded-full"></div>
            <p class="text-stone-500 mt-3">Apa kata mereka tentang DimsumYummy?</p>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @php
                $testimonials = [
                    ['img' => 'blonde2.jpg', 'text' => 'Gak pernah bosan beli disini, harga dan kualitas tidak diragukan lagi!', 'name' => 'Tom', 'rating' => 5],
                    ['img' => 'wolf.jpg',    'text' => 'Pelayanan yang cepat dan ramah. Sangat puas!', 'name' => 'Finn', 'rating' => 5],
                    ['img' => 'luwi.jpg',    'text' => 'Dimsum terbaik di dunia yang pernah saya coba!', 'name' => 'Louis', 'rating' => 5],
                    ['img' => 'jenn.jpg',    'text' => 'Rasa dan kualitas yang tidak bisa diragukan!', 'name' => 'Jenna', 'rating' => 5],
                    ['img' => 'emma.jpg',    'text' => 'Dimsumnya fresh banget, bumbunya enak, sukak!', 'name' => 'Harmi', 'rating' => 5],
                    ['img' => 'myers.jpg',   'text' => 'Porsi pas, rasa mantap, cocok untuk acara keluarga.', 'name' => 'Myers', 'rating' => 5],
                ];
                @endphp

                @foreach($testimonials as $t)
                <div class="swiper-slide">
                    <div class="bg-white rounded-2xl shadow-xl p-6 text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 border border-orange-100 mx-2">
                        <div class="relative mb-4">
                            <img src="{{ asset('img/' . $t['img']) }}" alt="{{ $t['name'] }}" class="w-20 h-20 mx-auto rounded-full object-cover ring-4 ring-orange-200 shadow-md">
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full whitespace-nowrap">
                                <i class="fas fa-quote-left mr-1"></i> Verified
                            </div>
                        </div>
                        <p class="text-stone-600 mb-4 italic text-sm leading-relaxed">"{{ $t['text'] }}"</p>
                        <div class="flex justify-center text-yellow-400 mb-2 text-sm">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star{{ $i <= $t['rating'] ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <h4 class="text-md font-bold text-orange-600">- {{ $t['name'] }}</h4>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-center mt-10">
                <div class="swiper-pagination !static"></div>
            </div>
        </div>
    </div>
</section>

{{-- ======================= FOOTER ======================= --}}
<footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-gray-300 py-12 mt-16 border-t border-orange-800/30">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Kolom 1: Brand -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <img src="{{ asset('img/logoyummy.jpeg') }}" alt="Logo" class="h-10 w-auto rounded-full border border-orange-500/30 shadow-md">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Dimsum<span class="text-orange-500">Yummy</span></h2>
                </div>
                <p class="text-sm leading-relaxed text-gray-400">
                    Rasakan kelezatan dimsum kami yang dibuat dengan bahan berkualitas tinggi & resep autentik. Siap memanjakan lidah Anda.
                </p>
                <div class="flex space-x-4 mt-6">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 hover:bg-orange-500 text-gray-300 hover:text-white flex items-center justify-center transition-all duration-300">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 hover:bg-orange-500 text-gray-300 hover:text-white flex items-center justify-center transition-all duration-300">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-700 hover:bg-orange-500 text-gray-300 hover:text-white flex items-center justify-center transition-all duration-300">
                        <i class="fab fa-whatsapp text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Kolom 2: Kontak & Alamat -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-orange-500 text-sm"></i> Alamat & Kontak
                </h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-location-dot text-orange-500 mt-1 w-4"></i>
                        <span>Jl. Angkasa Raya No. 123, Cirebon, Jawa Barat</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-orange-500 w-4"></i>
                        <span>+62 823-1759-20647</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-orange-500 w-4"></i>
                        <a href="mailto:yummydimsum@gmail.com" class="hover:text-orange-400 transition">yummydimsum@gmail.com</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-clock text-orange-500 w-4"></i>
                        <span>Buka: 08.00 - 21.00 WIB (Setiap Hari)</span>
                    </li>
                </ul>
            </div>

            <!-- Kolom 3: Link Cepat -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-link text-orange-500 text-sm"></i> Link Cepat
                </h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('tentang') }}" class="flex items-center gap-2 hover:text-orange-400 transition group"><i class="fas fa-angle-right text-orange-500 text-xs group-hover:translate-x-1 transition"></i> Tentang Kami</a></li>
                    <li><a href="{{ route('produk.index') }}" class="flex items-center gap-2 hover:text-orange-400 transition group"><i class="fas fa-angle-right text-orange-500 text-xs group-hover:translate-x-1 transition"></i> Semua Produk</a></li>
                    <li><a href="{{ route('welcome') }}#kategori" class="flex items-center gap-2 hover:text-orange-400 transition group"><i class="fas fa-angle-right text-orange-500 text-xs group-hover:translate-x-1 transition"></i> Kategori</a></li>
                    <li><a href="{{ route('kontak') }}" class="flex items-center gap-2 hover:text-orange-400 transition group"><i class="fas fa-angle-right text-orange-500 text-xs group-hover:translate-x-1 transition"></i> Hubungi Kami</a></li>
                    <li><a href="{{ route('pelanggan.cart.index') }}" class="flex items-center gap-2 hover:text-orange-400 transition group"><i class="fas fa-angle-right text-orange-500 text-xs group-hover:translate-x-1 transition"></i> Keranjang Belanja</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Newsletter -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-newspaper text-orange-500 text-sm"></i> Newsletter
                </h3>
                <p class="text-sm text-gray-400 mb-3">Dapatkan promo & update terbaru dari kami.</p>
                <div class="flex flex-col space-y-3">
                    <div class="flex">
                        <input type="email" placeholder="Email Anda" class="flex-1 p-2 rounded-l-lg bg-gray-800 border border-gray-700 text-white text-sm focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 rounded-r-lg transition font-semibold text-sm">Daftar</button>
                    </div>
                    <p class="text-xs text-gray-500">Kami tidak akan mengirim spam. Privasi Anda aman.</p>
                </div>
            </div>
        </div>

        <!-- Copyright & Payment -->
        <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
            <span>&copy; {{ date('Y') }} DimsumYummy. All rights reserved.</span>
            <div class="flex gap-3 mt-2 md:mt-0">
                <i class="fab fa-cc-visa text-lg"></i>
                <i class="fab fa-cc-mastercard text-lg"></i>
                <i class="fab fa-cc-paypal text-lg"></i>
                <i class="fas fa-money-bill-wave text-lg"></i>
            </div>
        </div>
    </div>
</footer>

{{-- ======================= SCRIPTS ======================= --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Carousel
    let currentIndex = 0;
    const slides = document.querySelectorAll('#mainCarousel .carousel-item');

    function showSlide(index) {
        slides.forEach((s, i) => s.style.display = i === index ? 'block' : 'none');
    }
    function nextSlide() { currentIndex = (currentIndex + 1) % slides.length; showSlide(currentIndex); }
    function prevSlide() { currentIndex = (currentIndex - 1 + slides.length) % slides.length; showSlide(currentIndex); }

    showSlide(0);
    let timer = setInterval(nextSlide, 5000);
    const heroPromo = document.getElementById('heroPromo');
    if (heroPromo) {
        heroPromo.addEventListener('mouseenter', () => clearInterval(timer));
        heroPromo.addEventListener('mouseleave', () => { timer = setInterval(nextSlide, 5000); });
    }

    // Swiper Testimoni
    new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        breakpoints: { 640: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    // AOS
    AOS.init({ duration: 1000, once: true });

    // Profile Dropdown Toggle
    const dropdownBtn = document.getElementById('profileDropdownBtn');
    const dropdownMenu = document.getElementById('profileDropdownMenu');
    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        // Close dropdown when clicking outside
        window.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Logout SweetAlert (for dropdown button)
    const logoutBtn = document.getElementById('logoutBtnDropdown');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout dari DimsumYummy?',
                text: 'Yakin mau logout? Pastikan semua pesananmu sudah aman ya!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FB7E3C',
                cancelButtonColor: '#6B3F2D',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                background: '#FFF8F0',
                color: '#6B3F2D',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    }
</script>

</body>
</html>