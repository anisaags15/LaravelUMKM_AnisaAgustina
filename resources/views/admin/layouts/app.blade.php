<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - DimsumYummy')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { 
            background-color: #FFF8E7; 
            margin: 0; 
            padding: 0;
            overflow: hidden;
            height: 100vh;
        }
        
        .nav-link { transition: all 0.2s ease; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); transform: translateX(4px); }
        .nav-link.active { background-color: #FB7E3C; box-shadow: 0 4px 12px rgba(251,126,60,0.4); }
        
        #sidebar { 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            overflow-y: auto; 
            overflow-x: hidden; 
            scrollbar-width: thin;
            height: 100vh;
            position: relative;
            z-index: 20;
        }
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 10px; }
        #sidebar::-webkit-scrollbar-thumb { background: #FB7E3C; border-radius: 10px; }
        
        .profile-card { transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1); }
        
        .sidebar-collapsed .profile-card { 
            background: transparent !important; 
            border-color: transparent !important;
            padding: 0.75rem 0 !important;
            justify-content: center;
        }
        .sidebar-collapsed .profile-card .menu-text { display: none; }
        .sidebar-collapsed .profile-card img { margin: 0 auto; }
        
        #sidebarIcon { transition: transform 0.3s ease; }
        .sidebar-collapsed #sidebarIcon { transform: rotate(180deg); }
        
        body.swal2-shown,
        body.swal2-shown > [aria-hidden="true"] {
            overflow: hidden !important;
            padding-right: 0 !important;
            margin-right: 0 !important;
        }
        
        .swal2-container { z-index: 9999 !important; }
        #sidebar { transition: width 0.3s ease; }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        .pagination .page-item { display: inline-flex; }
        .pagination .page-link,
        .pagination .page-item span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 0.75rem;
            border-radius: 12px;
            background-color: #f5f5f5;
            color: #4b5563;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .pagination .page-link:hover {
            background-color: #FB7E3C;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(251,126,60,0.3);
        }
        .pagination .active .page-link {
            background: linear-gradient(135deg, #FB7E3C, #D95C1A);
            color: white;
            box-shadow: 0 4px 12px rgba(251,126,60,0.4);
            font-weight: bold;
        }
        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #e5e7eb;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

{{-- ======================= SIDEBAR ======================= --}}
<div id="sidebar" class="w-64 flex flex-col p-5 text-white transition-all duration-300 relative flex-shrink-0"
     style="background: linear-gradient(180deg, #3E2723, #261714);">

    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none overflow-hidden">
        <i class="fas fa-utensils text-9xl absolute -bottom-10 -right-10 rotate-12"></i>
    </div>

    <div class="flex items-center justify-between mb-6 relative z-10" id="sidebarHeader">
        <div id="logoWrapper" class="flex items-center gap-2 overflow-hidden transition-all duration-300">
            <div class="bg-[#FB7E3C] p-1.5 rounded-lg flex-shrink-0">
                <i class="fas fa-bowl-food text-white text-lg"></i>
            </div>
            <h2 class="text-lg font-bold tracking-tight whitespace-nowrap">
                Dimsum<span class="text-[#FB7E3C]">Admin</span>
            </h2>
        </div>
        <button id="toggleSidebar"
                class="bg-[#FB7E3C] hover:bg-[#E56727] w-8 h-8 rounded-full flex items-center justify-center transition-all flex-shrink-0">
            <i id="sidebarIcon" class="fas fa-angle-left text-sm"></i>
        </button>
    </div>

    <a href="{{ route('admin.profil.show') }}" id="profileCard"
       class="profile-card mb-6 flex items-center gap-3 p-3 bg-white/5 rounded-2xl relative z-10 cursor-pointer group overflow-hidden">
        <div class="relative flex-shrink-0">
            @php
                $foto = Auth::user()->profile_picture
                    ? asset('storage/' . Auth::user()->profile_picture)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=FB7E3C&color=fff&size=128';
            @endphp
            <img src="{{ $foto }}" alt="Profile" class="rounded-xl w-11 h-11 shadow-lg object-cover border-2 border-[#FB7E3C]/30 group-hover:border-[#FB7E3C] transition">
            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-[#4A2C1F] rounded-full"></div>
        </div>
        <div class="flex flex-col truncate min-w-0 menu-text">
            <span class="font-bold text-white text-sm truncate">{{ Auth::user()->nama ?? Auth::user()->name }}</span>
            <span class="text-[10px] uppercase tracking-wider text-[#FB7E3C] font-bold">{{ Auth::user()->role }}</span>
        </div>
    </a>

    <nav class="flex-1 space-y-1 relative z-10">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-3 menu-text">Utama</p>
        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 p-3 rounded-xl {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie w-5 text-center flex-shrink-0"></i>
            <span class="menu-text font-semibold text-sm whitespace-nowrap">Dashboard</span>
        </a>

        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px] mt-5 mb-3 ml-3 menu-text">Manajemen</p>
        <a href="{{ route('admin.produk.create') }}" class="nav-link flex items-center gap-3 p-3 rounded-xl {{ Request::routeIs('admin.produk.create') ? 'active' : '' }}">
            <i class="fas fa-plus-circle w-5 text-center flex-shrink-0"></i>
            <span class="menu-text font-semibold text-sm whitespace-nowrap">Tambah Produk</span>
        </a>
        <a href="{{ route('admin.produk.index') }}" class="nav-link flex items-center gap-3 p-3 rounded-xl {{ Request::routeIs('admin.produk.index') || Request::routeIs('admin.produk.edit') ? 'active' : '' }}">
            <i class="fas fa-boxes w-5 text-center flex-shrink-0"></i>
            <span class="menu-text font-semibold text-sm whitespace-nowrap">Data Barang</span>
        </a>
        <a href="{{ route('admin.pelanggan.index') }}" class="nav-link flex items-center gap-3 p-3 rounded-xl {{ Request::is('admin/pelanggan*') ? 'active' : '' }}">
            <i class="fas fa-users w-5 text-center flex-shrink-0"></i>
            <span class="menu-text font-semibold text-sm whitespace-nowrap">Data Pelanggan</span>
        </a>

        {{-- MENU RIWAYAT TRANSAKSI DENGAN BADGE --}}
        @php
            $pendingVerifyCount = \App\Models\Transaksi::where('payment_method', 'transfer')
                                ->where('payment_status', 'pending')
                                ->whereNotNull('payment_proof')
                                ->count();
        @endphp
        <a href="{{ route('admin.transaksi.index') }}" class="nav-link flex items-center justify-between gap-3 p-3 rounded-xl {{ Request::is('admin/transaksi*') ? 'active' : '' }}">
            <div class="flex items-center gap-3">
                <i class="fas fa-receipt w-5 text-center flex-shrink-0"></i>
                <span class="menu-text font-semibold text-sm whitespace-nowrap">Riwayat Transaksi</span>
            </div>
            @if($pendingVerifyCount > 0)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-2 animate-pulse">
                    {{ $pendingVerifyCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.pesan.index') }}" class="nav-link flex items-center gap-3 p-3 rounded-xl {{ Request::is('admin/pesan*') ? 'active' : '' }}">
            <i class="fas fa-envelope-open-text w-5 text-center flex-shrink-0"></i>
            <span class="menu-text font-semibold text-sm whitespace-nowrap">Pesan Masuk</span>
        </a>

        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px] mt-5 mb-3 ml-3 menu-text">Akun</p>
        <a href="{{ route('admin.profil.show') }}" class="nav-link flex items-center gap-3 p-3 rounded-xl {{ Request::is('admin/profil*') ? 'active' : '' }}">
            <i class="fas fa-user-circle w-5 text-center flex-shrink-0"></i>
            <span class="menu-text font-semibold text-sm whitespace-nowrap">Profil Saya</span>
        </a>
    </nav>

    <div class="mt-auto pt-4 border-t border-white/10 relative z-10">
        <form method="POST" action="{{ route('logout') }}" id="adminLogoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-500/20 hover:text-red-400 transition text-left group">
                <i class="fas fa-power-off w-5 text-center flex-shrink-0"></i>
                <span class="menu-text font-semibold text-sm whitespace-nowrap">Keluar Sistem</span>
            </button>
        </form>
    </div>
</div>

<div class="flex-1 overflow-auto p-8">
    @yield('content')
</div>

<script>
    // Toggle sidebar (sama seperti sebelumnya)
    const sidebar = document.getElementById('sidebar');
    const logoWrapper = document.getElementById('logoWrapper');
    const sidebarHeader = document.getElementById('sidebarHeader');
    const menuTexts = document.querySelectorAll('.menu-text');
    const profileCard = document.getElementById('profileCard');
    const toggleBtn = document.getElementById('toggleSidebar');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('w-20');

            if (isCollapsed) {
                sidebar.classList.replace('w-20', 'w-64');
                sidebar.classList.remove('sidebar-collapsed');
                sidebarHeader.classList.replace('justify-center', 'justify-between');
                logoWrapper.classList.remove('hidden');
                menuTexts.forEach(t => t.classList.remove('hidden'));
                if(profileCard) profileCard.classList.remove('justify-center');
            } else {
                sidebar.classList.replace('w-64', 'w-20');
                sidebar.classList.add('sidebar-collapsed');
                sidebarHeader.classList.replace('justify-between', 'justify-center');
                logoWrapper.classList.add('hidden');
                menuTexts.forEach(t => t.classList.add('hidden'));
                if(profileCard) profileCard.classList.add('justify-center');
            }
        });
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar dari sistem?',
            text: 'Sesi admin akan diakhiri.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6B3F2D',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            background: '#FFF8E7',
            color: '#6B3F2D',
            allowOutsideClick: false,
            didOpen: () => {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '0';
            },
            willClose: () => {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        }).then(result => { 
            if (result.isConfirmed) {
                document.getElementById('adminLogoutForm').submit();
            }
        });
    }

    document.addEventListener('click', function() {
        if (document.body.classList.contains('swal2-shown')) {
            document.body.style.paddingRight = '0';
        }
    });

    // NOTIFIKASI SWEETALERT UNTUK FLASH MESSAGE
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            background: '#FFF9F0',
            color: '#5A2E1A'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            background: '#FFF9F0',
            color: '#5A2E1A'
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: "{{ session('warning') }}",
            background: '#FFF9F0',
            color: '#5A2E1A'
        });
    @endif
</script>

@stack('scripts')
</body>
</html>