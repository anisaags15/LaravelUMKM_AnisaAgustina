<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - DimsumYummy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background-color: #FB7E3C; border-radius: 3px; }
        ::-webkit-scrollbar-track { background-color: #FFE8D1; }
        
        /* Animasi yang sama dengan native kamu */
        .animate-fadeIn {
            transition: opacity 0.7s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-[#FFF1E0] to-[#FFD7B5] flex items-center justify-center min-h-screen font-sans">

    <div id="loginCard" class="bg-white/95 backdrop-blur-md p-10 rounded-3xl shadow-xl w-full max-w-md animate-fadeIn opacity-0">
        <div class="text-center mb-6">
            <img src="{{ asset('img/logoyummy.jpeg') }}" alt="DimsumYummy Logo" class="mx-auto w-20 h-20 rounded-full shadow-md mb-3">
            <h2 class="text-3xl font-extrabold text-[#FB7E3C]">Login</h2>
            <p class="text-sm text-[#6B3F2D] mt-1">Masuk untuk melanjutkan belanja dimsum favoritmu</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4 text-center text-xs font-medium">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded-xl mb-4 text-center text-xs font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" autocomplete="off" class="space-y-4">
            @csrf

            <div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-user"></i></span>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Email Address" 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none" 
                           required 
                           autofocus
                           autocomplete="username">
                </div>
            </div>

            <div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#FB7E3C]"><i class="fas fa-lock"></i></span>
                    <input type="password" 
                           name="password" 
                           placeholder="Password" 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-[#FB7E3C] focus:ring-2 focus:ring-[#FB7E3C] transition outline-none" 
                           required
                           autocomplete="current-password">
                </div>
            </div>

            <div class="flex items-center justify-between px-1">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#FB7E3C] focus:ring-[#FB7E3C]">
                    <span class="ml-2 text-xs text-[#6B3F2D]">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-[#FB7E3C] hover:underline">Lupa Password?</a>
                @endif
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3 bg-gradient-to-r from-[#FB7E3C] to-[#E56727] text-white font-bold rounded-xl shadow-md hover:scale-105 hover:shadow-lg transition-all duration-300">
                    Login
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-[#6B3F2D] mt-6">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-[#FB7E3C] font-semibold hover:underline">Daftar di sini yuk!</a>
        </p>
    </div>

    <script>
        window.onload = () => {
            const loginCard = document.getElementById('loginCard');
            setTimeout(() => {
                loginCard.classList.remove('opacity-0');
                loginCard.classList.add('opacity-100');
            }, 100);
        };
    </script>
</body>
</html>