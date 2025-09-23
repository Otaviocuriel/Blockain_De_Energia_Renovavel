<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name','Blockchain Verde') }} – Acesso</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Fallback temporário: CDN do Tailwind e Alpine para quando o Vite não estiver rodando -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-950 text-gray-100 font-sans antialiased flex flex-col relative overflow-hidden">
        <!-- Background / Hero -->
        <div class="absolute inset-0 -z-10">
            <img src="{{ asset('Imagens/foto3.jpg') }}" class="w-full h-full object-cover" alt="Energia Renovável" />
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
            <div class="absolute top-[-6rem] right-[-6rem] w-[32rem] h-[32rem] bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-4rem] left-[-4rem] w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Minimal top link -->
        <div class="px-6 pt-6 flex items-center justify-between max-w-7xl mx-auto w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-bold text-white/90 hover:text-white transition">
                <img src="{{ asset('Imagens/minha_logo.png') }}" alt="Logo" class="w-10 h-10 object-contain" />
                Blockchain Verde
            </a>
            @if (Route::has('login'))
                <div class="hidden sm:flex gap-4 text-xs">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white/80 hover:text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white/70 hover:text-white">Entrar</a>
                        <a href="{{ route('register') }}" class="text-white/70 hover:text-white">Registrar</a>
                    @endauth
                </div>
            @endif
        </div>

        <!-- Content card -->
        <div class="flex-1 w-full flex items-center justify-center px-6 pb-12">
            <div class="w-full max-w-md relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-indigo-500 rounded-2xl opacity-40 blur-lg"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-2xl border border-white/15 shadow-xl p-8">
                    <div class="flex flex-col items-center mb-8 text-center">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-emerald-600/30 mb-4">
                            <img src="{{ asset('Imagens/minha_logo.png') }}" alt="Logo" class="w-12 h-12 object-contain mx-auto" />
                        </div>
                        <h1 class="text-xl font-semibold tracking-tight">{{ $title ?? 'Acesse sua conta' }}</h1>
                        @isset($subtitle)
                            <p class="mt-1 text-sm text-slate-300">{{ $subtitle }}</p>
                        @endisset
                    </div>
                    {{ $slot }}
                </div>
                <p class="text-[11px] text-center mt-6 text-slate-400">&copy; {{ date('Y') }} Blockchain Verde</p>
            </div>
        </div>
    </body>
</html>
</html>
