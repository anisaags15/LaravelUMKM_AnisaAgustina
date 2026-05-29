<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DimsumYummy')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        html { scroll-behavior: smooth; }
        .dropdown-menu {
            transition: all 0.2s ease;
            transform-origin: top right;
        }
        .dropdown-menu.show {
            display: block !important;
        }
    </style>
</head>
<body class="bg-[#FFF1E0] text-[#6B3F2D] font-sans">

{{-- ======================= NAVBAR ======================= --}}
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between relative">

        {{-- Logo --}}
        <div class="flex items-center space-x-2">
            <img src="{{ asset('img/logoyummy.jpeg') }}" alt="Logo" class="h-10 w-auto rounded-full shadow-md">
            <span class="text-xl font-bold text-[#FB7E3C] tracking-tight">DimsumYummy</span>
        </div>

        {{-- Menu Tengah --}}
        <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 space-x-6 text-[#6B3F2D] font-medium">
            <a href="{{ route('welcome') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C] {{ request()->routeIs('welcome') ? 'border-b-2 border-[#FB7E3C]' : '' }}">Beranda</a>
            <a href="{{ route('produk.index') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C] {{ request()->routeIs('produk.*') ? 'border-b-2 border-[#FB7E3C]' : '' }}">Produk</a>
            <a href="{{ route('welcome') }}#kategori" class="transition hover:border-b-2 hover:border-[#FB7E3C]">Kategori</a>
            <a href="{{ route('tentang') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C] {{ request()->routeIs('tentang') ? 'border-b-2 border-[#FB7E3C]' : '' }}">Tentang Kami</a>
            <a href="{{ route('kontak') }}" class="transition hover:border-b-2 hover:border-[#FB7E3C] {{ request()->routeIs('kontak') ? 'border-b-2 border-[#FB7E3C]' : '' }}">Kontak</a>
        </div>

        {{-- Kanan: Search + Cart + Auth --}}
        <div class="flex items-center space-x-4">

            {{-- Search dengan ikon lebih menarik --}}
            <form method="GET" action="{{ route('produk.index') }}" class="hidden md:flex">
                <div class="relative">
                    <input type="text" name="q"
                           placeholder="Cari produk..."
                           value="{{ request('q') }}"
                           class="px-4 py-2 pl-10 border border-gray-200 rounded-l-full focus:outline-none focus:ring-2 focus:ring-[#FB7E3C] focus:border-transparent w-48 transition-all duration-200">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white rounded-r-full hover:from-[#E56727] hover:to-[#d45a1a] transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            {{-- Cart dengan ikon cantik + badge --}}
           @php $cartCount = count(session('cart', [])); @endphp
            <a href="{{ route('pelanggan.cart.index') }}" class="relative group">
                <div class="p-2 rounded-full bg-gray-100 group-hover:bg-orange-100 transition-all duration-300 transform group-hover:scale-110">
                    <i class="fas fa-shopping-cart text-xl text-[#6B3F2D] group-hover:text-[#FB7E3C] transition-colors"></i>
                </div>
                <span id="cart-count"
                      class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full shadow-md {{ $cartCount == 0 ? 'hidden' : '' }}">
                    {{ $cartCount }}
                </span>
            </a>

            {{-- Auth --}}
            @auth
            <div class="relative" id="profileDropdownContainer">
                {{-- Avatar trigger dengan efek ring cahaya --}}
                <button id="profileDropdownBtn"
                        class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-200 hover:border-[#FB7E3C] transition-all duration-200 focus:outline-none shadow-md hover:shadow-lg transform hover:scale-105">
                    <img src="{{ Auth::user()->profile_picture
                                    ? asset('storage/' . Auth::user()->profile_picture)
                                    : asset('img/default_profile.png') }}"
                         alt="Profile" class="w-full h-full object-cover">
                </button>

                {{-- Dropdown menu tetap sama --}}
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

            <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>

            @else
                {{-- Tombol login & register lebih elegan --}}
                <a href="{{ route('login') }}"
                   class="hidden md:block px-5 py-2 text-[#FB7E3C] border border-[#FB7E3C] rounded-full hover:bg-[#FB7E3C] hover:text-white transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                   Login
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white rounded-full hover:from-[#E56727] hover:to-[#d45a1a] transition-all duration-200 font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
                   Register
                </a>
            @endauth
        </div>
    </div>
</nav>
{{-- ======================= CONTENT ======================= --}}
@yield('content')

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

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });

    // Dropdown toggle
    const dropdownBtn = document.getElementById('profileDropdownBtn');
    const dropdownMenu = document.getElementById('profileDropdownMenu');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Tutup dropdown jika klik di luar
        window.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Logout via SweetAlert (untuk dropdown button)
    const logoutDropdownBtn = document.getElementById('logoutBtnDropdown');
    if (logoutDropdownBtn) {
        logoutDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout dari DimsumYummy?',
                text: 'Yakin mau logout?',
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

@stack('scripts')
</body>
</html>