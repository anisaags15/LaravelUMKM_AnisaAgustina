<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - DimsumYummy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background-color: #FB7E3C; border-radius: 3px; }
        ::-webkit-scrollbar-track { background-color: #FFE8D1; }
    </style>
</head>
<body class="bg-gradient-to-r from-[#FFF1E0] to-[#FFD7B5] flex items-center justify-center min-h-screen font-sans py-10">

    <div id="registerCard" class="bg-white/95 backdrop-blur-md p-10 rounded-3xl shadow-xl w-full max-w-md transition-all duration-700 opacity-0 transform translate-y-4">
        <div class="text-center mb-6">
            <img src="{{ asset('img/logoyummy.jpeg') }}" alt="DimsumYummy Logo" class="mx-auto w-20 h-20 rounded-full shadow-md mb-3">
            <h2 class="text-3xl font-extrabold text-[#FB7E3C]">Daftar Akun</h2>
            <p class="text-sm text-[#6B3F2D] mt-1">Buat akun untuk mulai belanja dimsum favoritmu</p>
        </div>

        <!-- Validasi Error Laravel -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4 text-xs font-medium">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" autocomplete="off" class="space-y-4">
            @csrf

            <!-- Nama Lengkap -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-user"></i></span>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none" 
                       required autofocus>
            </div>

            <!-- Email -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none" 
                       required>
            </div>
<!-- Username (SUDAH RAPI) -->
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-user-tag"></i></span>
        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username"
               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none"
               required>
    </div>
                <!-- Password -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" placeholder="Password" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none" 
                       required autocomplete="new-password">
            </div>

            <!-- Confirm Password (WAJIB di Breeze) -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-check-circle"></i></span>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none" 
                       required>
            </div>

            {{-- Info Tambahan (Opsional: Pastikan field ini sudah ada di migration users kamu) --}}
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-phone"></i></span>
                <input type="text" name="hp" value="{{ old('hp') }}" placeholder="Nomor HP" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none">
            </div>

            <div class="relative">
                <span class="absolute left-3 top-3 text-[#FB7E3C]"><i class="fas fa-map-marker-alt"></i></span>
                <textarea name="alamat" placeholder="Alamat" rows="2"
                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition resize-none outline-none">{{ old('alamat') }}</textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white font-bold rounded-xl shadow-md hover:scale-105 hover:shadow-lg transition-all duration-300 uppercase tracking-wide">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-[#6B3F2D] mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-[#FB7E3C] font-semibold hover:underline">Login di sini</a>
        </p>
    </div>

    <script>
        window.onload = () => {
            const card = document.getElementById('registerCard');
            setTimeout(() => {
                card.classList.remove('opacity-0', 'translate-y-4');
                card.classList.add('opacity-100', 'translate-y-0');
            }, 100);
        };
    </script>
</body>
</html>