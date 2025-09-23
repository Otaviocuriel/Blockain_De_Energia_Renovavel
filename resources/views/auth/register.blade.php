<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Criar Conta — Blockchain Verde</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    [x-cloak]{display:none!important}
    /* Custom page background: dark blue gradient with soft radial light on right */
    .page-bg {
      background: radial-gradient(800px 400px at 85% 35%, rgba(200,245,235,0.06), transparent 10%),
                  linear-gradient(135deg, #0b1220 0%, #1f2937 30%, #14505b 60%, #071029 100%);
      background-repeat: no-repeat;
      background-size: cover;
    }
    /* subtle card backdrop to show the background through */
    .card-glass { background-color: rgba(255,255,255,0.04); }
  </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('[data-mask]').forEach(function(el) {
        el.addEventListener('input', function(e) {
          let m = el.getAttribute('data-mask');
          let v = el.value.replace(/\D/g, '');
          if (m === 'cpf') {
            v = v.slice(0, 11)
              .replace(/(\d{3})(\d)/, '$1.$2')
              .replace(/(\d{3})(\d)/, '$1.$2')
              .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
          }
          if (m === 'cnpj') {
            v = v.slice(0, 14)
              .replace(/(\d{2})(\d)/, '$1.$2')
              .replace(/(\d{3})(\d)/, '$1.$2')
              .replace(/(\d{3})(\d)/, '$1/$2')
              .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
          }
          if (m === 'telefone') {
            v = v.slice(0, 11)
              .replace(/(\d{2})(\d)/, '($1) $2')
              .replace(/(\d{5})(\d{4})$/, '$1-$2');
          }
          el.value = v;
        });
      });
    });
    </script>
</head>
<body class="min-h-screen page-bg flex items-center justify-center">
  <div class="w-full max-w-3xl mx-auto p-6">
    <div class="card-glass backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
      <div class="grid grid-cols-12">
  <!-- ...existing code... -->
  <div class="col-span-12 md:col-span-5 bg-gradient-to-b from-slate-900/60 to-teal-900/40 p-8 flex flex-col items-center justify-center">
          <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center text-white mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <h2 class="text-2xl font-bold text-white mb-1">Criar Conta</h2>
          <p class="text-sm text-white/60">Escolha Pessoa ou Empresa e preencha os dados</p>
        </div>
        <div class="col-span-12 md:col-span-7 p-8">
          <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('role','user') }}' }" class="space-y-4">
            @csrf
            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm text-white/80">Nome</label>
                <input name="name" type="text" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5" required>
              </div>
              <div>
                <label class="block text-sm text-white/80">Tipo de Conta</label>
                <div class="mt-1 flex gap-2">
                  <label :class="role==='user'? 'px-3 py-2 rounded-md bg-teal-600 text-white cursor-pointer':'px-3 py-2 rounded-md bg-white text-gray-800 cursor-pointer'">
                    <input class="hidden" type="radio" name="role" value="user" x-model="role"> Pessoa
                  </label>
                  <label :class="role==='company'? 'px-3 py-2 rounded-md bg-teal-600 text-white cursor-pointer':'px-3 py-2 rounded-md bg-white text-gray-800 cursor-pointer'">
                    <input class="hidden" type="radio" name="role" value="company" x-model="role"> Empresa
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-sm text-white/80">Email</label>
                <input name="email" type="email" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5" required>
              </div>
              <div>
                <label class="block text-sm text-white/80">Telefone</label>
                <input name="telefone" type="text" maxlength="15" data-mask="telefone" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5">
              </div>

              <div>
                <label class="block text-sm text-white/80">CEP</label>
                <input name="cep" type="text" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5">
              </div>
              <div>
                <label class="block text-sm text-white/80">Data de Nascimento</label>
                <input name="data_nascimento" type="date" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5">
              </div>

              <div x-cloak x-show="role==='user'" x-transition class="col-span-2">
                <label class="block text-sm text-white/80">CPF</label>
                <input name="cpf" type="text" maxlength="14" data-mask="cpf" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5">
              </div>

              <div x-cloak x-show="role==='company'" x-transition class="col-span-2">
                <label class="block text-sm text-white/80">CNPJ</label>
                <input name="cnpj" type="text" maxlength="18" data-mask="cnpj" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5">
              </div>

              <div class="col-span-2 grid grid-cols-2 gap-3 items-end">
                <div>
                  <label class="block text-sm text-white/80">Senha</label>
                  <input name="password" type="password" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5" required>
                </div>
                <div>
                  <label class="block text-sm text-white/80">Confirmar Senha</label>
                  <input name="password_confirmation" type="password" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5" required>
                </div>
              </div>

              <!-- Actions: submit + link to login -->
              <div class="col-span-2 flex flex-col md:flex-row gap-3">
                <button type="submit" class="flex-1 py-3 rounded-md bg-teal-600 hover:bg-cyan-600 text-white font-semibold shadow-md border border-teal-700">Criar Conta</button>
                <a href="{{ route('login') }}" class="flex-1 py-3 rounded-md bg-transparent text-white/80 font-semibold text-center border border-white/10 hover:bg-white/5">Já tenho conta</a>
              </div>
             </div>
           </form>
         </div>
       </div>
     </div>
   </div>
 </body>
 </html>
