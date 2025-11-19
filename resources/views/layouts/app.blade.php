<!DOCTYPE html>
<html lang="pt-BR" class="h-full" x-data="{mobile:false}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name','Blockchain Verde') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    
    <script>
        if (typeof window.ethers === 'undefined') {
            window.ethers = {}; 
            console.warn('[metamask-shim] window.ethers placeholder criado no <head>');
        }
        if (typeof ethers === 'undefined') {
            var ethers = window.ethers;
        }
    </script>

    <script>
        (function(){
            try {
                var t = localStorage.getItem('site-theme-choice');
                if (t) {
                    document.documentElement.classList.add(t === 'dark' ? 'theme-dark' : 'theme-light');
                } else {
                    document.documentElement.classList.add('theme-light');
                }
            } catch (e) {  }
        })();
    </script>
</head>
<body class="min-h-full bg-black text-white flex flex-col" x-data x-init="document.querySelectorAll('[data-mask]')?.forEach(el=>{el.addEventListener('input',e=>{let m=e.target.getAttribute('data-mask');let v=e.target.value.replace(/\D/g,'');if(m==='cpf'){v=v.slice(0,11).replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');}if(m==='cnpj'){v=v.slice(0,14).replace(/(\d{2})(\d)/,'$1.$2').replace(/(\d{2}).(\d{3})(\d)/,'$1.$2.$3').replace(/(\d{3}).(\d{3})(\d)/,'$1.$2/$3').replace(/(\d{4})(\d{1,2})$/,'$1-$2');}if(m==='cep'){v=v.slice(0,8).replace(/(\d{5})(\d)/,'$1-$2');}if(m==='telefone'){v=v.slice(0,11).replace(/(\d{2})(\d)/,'($1) $2').replace(/(\d{5})(\d{4})$/,'$1-$2');}e.target.value=v;});});">
    @include('partials.theme-toggle') 
    <nav class="bg-black text-white shadow-lg relative z-20 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Blockchain de Energia renovável
                    </a>
                    <div class="hidden md:flex items-center gap-4 text-sm">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-white">Início</x-nav-link>
                        <x-nav-link :href="route('energia')" :active="request()->routeIs('energia')" class="text-white">Mapa de Energia</x-nav-link>
                        <x-nav-link :href="route('comentarios')" :active="request()->routeIs('comentarios')" class="text-white">Comentários</x-nav-link>
                        <x-nav-link :href="route('servicos')" :active="request()->routeIs('servicos')" class="text-white">Serviços</x-nav-link>
                        <x-nav-link :href="route('contato')" :active="request()->routeIs('contato')" class="text-white">Contato</x-nav-link>
                        <x-nav-link :href="route('blockchain.page')" :active="request()->routeIs('blockchain.page')" class="text-white">Blockchain</x-nav-link>
                        @auth
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">Dashboard</x-nav-link>
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
                <a href="{{ route('energia') }}" class="block">Mapa de Energia</a>
                <a href="{{ route('comentarios') }}" class="block">Comentários</a>
                <a href="{{ route('servicos') }}" class="block">Serviços</a>
                <a href="{{ route('contato') }}" class="block">Contato</a>
                <a href="{{ route('blockchain.page') }}" class="block">Blockchain</a>
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

    <main class="flex-1 bg-black text-white">
        @isset($header)
            <div class="bg-black shadow py-8 mb-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">{{ $header }}</div>
            </div>
        @endisset
        {{ $slot ?? '' }}
        @yield('content')
    </main>

<footer class="mt-12 bg-black text-white py-10 text-sm border-t border-white/10">
  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-semibold mb-2">Sobre</h3>
      <p class="opacity-80 leading-relaxed">Plataforma dedicada à compra e venda de energia renovável com transparência e rastreabilidade garantida.</p>
    </div>
    <div>
      <h3 class="font-semibold mb-2">Links</h3>
      <ul class="space-y-1">
        <li><a href="{{ route('home') }}" class="hover:underline">Início</a></li>
        <li><a href="{{ route('energia') }}" class="hover:underline">Mapa de Energia</a></li>
        <li><a href="{{ route('comentarios') }}" class="hover:underline">Comentários</a></li>
        <li><a href="{{ route('servicos') }}" class="hover:underline">Serviços</a></li>
        <li><a href="{{ route('contato') }}" class="hover:underline">Contato</a></li>
        <li><a href="{{ route('blockchain.page') }}" class="hover:underline">Blockchain</a></li>
      </ul>
    </div>

    <div>
      <h3 class="font-semibold mb-2">Contato</h3>
      <p class="opacity-80">
        📧 otavio.curiel@etec.sp.gov.br<br>
        📞 <a href="tel:+55(19)993878979" class="hover:underline">+55 (19)99387-8979</a><br>
        📍 São Paulo - SP
      </p>

    
      <div class="mt-3 flex items-center gap-4">
        
        <a href="https://github.com/Otaviocuriel" target="_blank" rel="noopener noreferrer" aria-label="GitHub de Otaviocuriel" class="inline-flex items-center gap-2 text-sm opacity-90 hover:opacity-100">
          <img
            src="https://github.com/Otaviocuriel.png?s=128"
            alt="Otaviocuriel — GitHub"
            class="w-8 h-8 rounded-full object-cover shadow-sm"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ asset('images/otaviocuriel.jpg') }}';"
          >
          <span class="hidden sm:inline">Otaviocuriel</span>
        </a>
        <a href="https://github.com/Ruan236" target="_blank" rel="noopener noreferrer" aria-label="GitHub de Ruan236" class="inline-flex items-center gap-2 text-sm opacity-90 hover:opacity-100">
          <img
            src="https://github.com/Ruan236.png?s=128"
            alt="Ruan236 — GitHub"
            class="w-8 h-8 rounded-full object-cover shadow-sm"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ asset('images/ruan236.jpg') }}';">
          <span class="hidden sm:inline">Ruan236</span>
        </a>
      </div>
    </div>
  </div>

  <div class="mt-8 border-t border-white/10 pt-4 text-center opacity-70">
    &copy; {{ date('Y') }} Blockchain Verde. Todos os direitos reservados.
  </div>
</footer>

    
    <script>
        window.Laravel = {
            isAuthenticated: @json(auth()->check())
        };
    </script>

    <script>window.ethersLoaded = false;</script>
    <script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.min.js"></script>
    <script>window.ethersLoaded = typeof ethers !== 'undefined';</script>
    <script src="{{ asset('js/blockchain.js') }}"></script>
</body>
</html>