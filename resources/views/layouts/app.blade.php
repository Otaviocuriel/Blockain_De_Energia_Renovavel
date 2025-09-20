<!DOCTYPE html>
<html lang="pt-BR" class="h-full" x-data="{mobile:false}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name','Blockchain Verde') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-full bg-gray-950 text-gray-100 flex flex-col" x-data x-init="document.querySelectorAll('[data-mask]')?.forEach(el=>{el.addEventListener('input',e=>{let m=e.target.getAttribute('data-mask');let v=e.target.value.replace(/\D/g,'');if(m==='cpf'){v=v.slice(0,11).replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');}if(m==='cnpj'){v=v.slice(0,14).replace(/(\d{2})(\d)/,'$1.$2').replace(/(\d{2}).(\d{3})(\d)/,'$1.$2.$3').replace(/(\d{3}).(\d{3})(\d)/,'$1.$2/$3').replace(/(\d{4})(\d{1,2})$/,'$1-$2');}if(m==='cep'){v=v.slice(0,8).replace(/(\d{5})(\d)/,'$1-$2');}if(m==='telefone'){v=v.slice(0,11).replace(/(\d{2})(\d)/,'($1) $2').replace(/(\d{5})(\d{4})$/,'$1-$2');}e.target.value=v;});});">
    <!-- Navbar -->
    <nav class="bg-green-900 text-white shadow-lg relative z-20 border-b border-green-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Blockchain Verde
                    </a>
                    <div class="hidden md:flex items-center gap-4 text-sm">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Início</x-nav-link>
                        <x-nav-link :href="route('comentarios')" :active="request()->routeIs('comentarios')">Comentários</x-nav-link>
                        <x-nav-link :href="route('servicos')" :active="request()->routeIs('servicos')">Serviços</x-nav-link>
                        <x-nav-link :href="route('contato')" :active="request()->routeIs('contato')">Contato</x-nav-link>
                        @auth
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                        @endauth
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-green-700 text-white text-sm font-medium hover:bg-green-600 transition">Registrar</a>
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition">Login</a>
                    @else
                        <span class="text-sm opacity-80">Olá, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button class="px-3 py-2 bg-green-700 hover:bg-green-600 rounded-md text-sm font-medium">Sair</button>
                        </form>
                    @endguest
                </div>
                <button @click="mobile=!mobile" class="md:hidden inline-flex items-center justify-center p-2 rounded hover:bg-white/10 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    <div x-show="mobile" x-transition class="md:hidden border-t border-green-800 bg-green-900/95 backdrop-blur">
            <div class="px-4 py-3 space-y-2 text-sm">
                <a href="{{ route('home') }}" class="block">Início</a>
                <a href="{{ route('comentarios') }}" class="block">Comentários</a>
                <a href="{{ route('servicos') }}" class="block">Serviços</a>
                <a href="{{ route('contato') }}" class="block">Contato</a>
                @auth <a href="{{ route('dashboard') }}" class="block">Dashboard</a> @endauth
                @guest
                    <a href="{{ route('login') }}" class="block">Login</a>
                    <a href="{{ route('register') }}" class="block">Registrar</a>
                @else
                    <form method="POST" action="{{ route('logout') }}">@csrf <button class="mt-2 text-left w-full">Sair</button></form>
                @endguest
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @isset($header)
            <div class="bg-white dark:bg-gray-800 shadow py-8 mb-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">{{ $header }}</div>
            </div>
        @endisset
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="mt-12 bg-gradient-to-r from-slate-700 to-slate-600 text-white py-10 text-sm">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold mb-2">Sobre</h3>
                <p class="opacity-80 leading-relaxed">Plataforma dedicada à compra e venda de energia renovável com transparência e rastreabilidade garantida.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Links</h3>
                <ul class="space-y-1">
                    <li><a href="{{ route('home') }}" class="hover:underline">Início</a></li>
                    <li><a href="{{ route('comentarios') }}" class="hover:underline">Comentários</a></li>
                    <li><a href="{{ route('servicos') }}" class="hover:underline">Serviços</a></li>
                    <li><a href="{{ route('contato') }}" class="hover:underline">Contato</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Contato</h3>
                <p class="opacity-80">📧 ruan.otavio@blockchainverde.com<br>📞 (11) 99999-9999<br>📍 São Paulo - SP</p>
            </div>
        </div>
        <div class="mt-8 border-t border-white/10 pt-4 text-center opacity-70">
            &copy; {{ date('Y') }} Blockchain Verde. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
