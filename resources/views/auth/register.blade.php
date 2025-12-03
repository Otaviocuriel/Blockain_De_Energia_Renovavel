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
                <input name="cep" id="cep" type="text" maxlength="9" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5" placeholder="Digite o CEP" required>
              </div>
              <div x-cloak x-show="role==='company'" x-transition class="col-span-2">
                <label class="block text-sm text-white/80">Endereço</label>
                <input name="endereco" id="endereco" type="text" class="mt-1 w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5" readonly>
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
                  <div class="relative mt-1">
                    <input id="password" name="password" type="password" class="w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5 pr-10" required>
                    <button type="button" class="toggle-password absolute inset-y-0 right-2 flex items-center text-slate-300" data-target="password" aria-label="Mostrar senha" aria-pressed="false">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                </div>
                <div>
                  <label class="block text-sm text-white/80">Confirmar Senha</label>
                  <div class="relative mt-1">
                    <input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-3 py-2 rounded-md bg-slate-800 text-white border border-white/5 pr-10" required>
                    <button type="button" class="toggle-password absolute inset-y-0 right-2 flex items-center text-slate-300" data-target="password_confirmation" aria-label="Mostrar confirmação de senha" aria-pressed="false">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
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
   <script>
document.addEventListener('DOMContentLoaded', function() {
  const cepInput = document.getElementById('cep');
  const enderecoInput = document.getElementById('endereco');
  if (cepInput) {
    cepInput.addEventListener('blur', function() {
      const cep = cepInput.value.replace(/\D/g, '');
      if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
          .then(response => response.json())
          .then(data => {
            if (!data.erro) {
              enderecoInput.value = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
            } else {
              enderecoInput.value = '';
            }
          });
      } else {
        enderecoInput.value = '';
      }
    });
  }
});
</script>
<script>
(function(){
  function setIcon(btn, visible){
    // eye-off when visible=true, eye when visible=false
    btn.innerHTML = visible
      ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a11.97 11.97 0 012.223-3.99M6.6 6.6A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a11.976 11.976 0 01-1.318 3.058M3 3l18 18"/></svg>'
      : '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
  }

  document.addEventListener('click', function(e){
    var btn = e.target.closest && e.target.closest('.toggle-password');
    if (!btn) return;
    var targetId = btn.dataset.target;
    var input = document.getElementById(targetId);
    if (!input) return;
    var nowVisible = input.type === 'text';
    if (nowVisible) {
      input.type = 'password';
      btn.setAttribute('aria-pressed','false');
      setIcon(btn, false);
    } else {
      input.type = 'text';
      btn.setAttribute('aria-pressed','true');
      setIcon(btn, true);
    }
  });

  // Optional: initialize icons for any toggle buttons present at load
  document.querySelectorAll('.toggle-password').forEach(function(b){
    setIcon(b, false);
  });
})();
</script>
 </body>
 </html>
