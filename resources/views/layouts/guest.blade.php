<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Energia Renovável') }} – Redefinir senha</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Fallback temporário: CDN do Tailwind e Alpine para quando o Vite/manifest não estiver disponível -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @php $manifestPath = public_path('build/manifest.json'); @endphp

    @if (file_exists($manifestPath))
        @vite(['resources/css/app.css','resources/js/app.js'])
    @else
        <style>
            /* Pequeno fallback visual se Vite não estiver rodando */
            html, body { height: 100%; }
        </style>
    @endif
</head>
<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg,#071428 0%, #06242e 60%, #053536 100%); color: #ffffff;">

    {{-- Top minimal navigation (igual à das outras páginas) --}}
    <header class="w-full absolute top-0 left-0 z-20">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-bold text-white/95 hover:text-white transition">
                <img src="{{ asset('Imagens/minha_logo.png') }}" alt="Logo" class="w-10 h-10 object-contain" />
                <span>Energia Renovável</span>
            </a>

            @if (Route::has('login'))
                <nav class="space-x-4 text-sm text-white/80 hidden sm:flex">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hover:text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white">Entrar</a>
                        <a href="{{ route('register') }}" class="hover:text-white">Registrar</a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- Card central idêntico ao login/register, com o formulário de esqueci a senha em português --}}
    <main class="w-full flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md relative">
            <div class="rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 shadow-lg p-8">
                <div class="flex flex-col items-center mb-6 text-center">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-indigo-600 flex items-center justify-center shadow-lg mb-4">
                        <img src="{{ asset('Imagens/minha_logo.png') }}" alt="Logo" class="w-12 h-12 object-contain mx-auto" />
                    </div>
                    <h1 class="text-xl font-semibold tracking-tight text-white">Redefinir senha</h1>
                    <p class="mt-1 text-sm text-white/75">
                        Esqueceu sua senha? Sem problema. Informe seu e-mail abaixo e enviaremos um link para redefinição.
                    </p>
                </div>

                {{-- Mensagem de sucesso --}}
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-400">
                        Enviamos um link para redefinição de senha para o seu e-mail. Verifique sua caixa de entrada.
                    </div>
                @endif

                {{-- Formulário --}}
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-white/90">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="mt-1 block w-full rounded-md border border-white/20 bg-white/5 px-3 py-2 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-400" />
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-md shadow-sm">
                            Enviar link para redefinir senha
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-white/80 hover:text-white">Voltar ao login</a>
                </div>
            </div>

            <p class="text-center text-[12px] text-white/60 mt-6">&copy; {{ date('Y') }} Blockchain Verde</p>
        </div>
    </main>

</body>
</html>