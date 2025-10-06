@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
  <h1 class="text-3xl font-bold mb-4">Painel Empresa: {{ $user->name }}</h1>
  <p class="mb-6 text-gray-600 dark:text-gray-300">Você está autenticado como <strong>Empresa Fornecedora</strong>.</p>

  @if(!$user->website)
  <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-lg">
    <p class="text-yellow-800 dark:text-yellow-200">
      <strong>Importante:</strong> Para que os clientes possam fazer contratações, você precisa cadastrar o site da sua empresa no seu perfil.
    </p>
  </div>
  @endif

  <!-- Formulário para cadastrar/editar site -->
  <div class="mb-8">
    <form method="POST" action="{{ route('empresa.site.update') }}" class="flex gap-2 items-end">
      @csrf
      <label class="block text-sm text-gray-700 dark:text-gray-200">
        Site da Empresa:
        <input type="url" name="website" value="{{ old('website', $user->website) }}" class="mt-1 px-3 py-2 rounded-md bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 w-72" placeholder="https://www.suaempresa.com.br" required>
      </label>
      <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white font-semibold">Salvar</button>
    </form>
    @if(session('success'))
      <div class="mt-2 text-green-600">{{ session('success') }}</div>
    @endif
  </div>

  <!-- Formulário para cadastrar/editar endereço -->
  <div class="mb-8">
    <form method="POST" action="{{ route('empresa.endereco.update') }}" class="flex flex-col gap-2 items-start" id="form-endereco">
      @csrf
      <label class="block text-sm text-gray-700 dark:text-gray-200 w-full">
        Endereço da Empresa:
        <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $user->endereco) }}" class="mt-1 px-3 py-2 rounded-md bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 w-72" placeholder="Rua, número, cidade, estado ou CEP" required autocomplete="off">
      </label>
      <!-- Campos ocultos para lat/lon -->
      <input type="hidden" id="lat" name="lat" value="{{ old('lat', $user->lat) }}">
      <input type="hidden" id="lon" name="lon" value="{{ old('lon', $user->lon) }}">
      <button type="submit" class="px-4 py-2 mt-2 rounded bg-emerald-600 text-white font-semibold">Salvar</button>
    </form>
    @if(session('success'))
      <div class="mt-2 text-green-600">{{ session('success') }}</div>
    @endif

    <!-- Mapa -->
    <div id="map" style="height: 300px;" class="mt-6 mb-4 rounded shadow"></div>
  </div>

  <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="p-5 bg-white dark:bg-gray-800 rounded shadow border-l-4 border-emerald-500">
      <h2 class="font-semibold mb-2">Publicar Oferta</h2>
      <p class="text-sm">Cadastro de novas ofertas (futuro).</p>
    </div>
    <div class="p-5 bg-white dark:bg-gray-800 rounded shadow border-l-4 border-indigo-500">
      <h2 class="font-semibold mb-2">Pedidos</h2>
      <p class="text-sm">Gerencie pedidos recebidos.</p>
    </div>
    <div class="p-5 bg-white dark:bg-gray-800 rounded shadow border-l-4 border-yellow-500">
      <h2 class="font-semibold mb-2">Comentários</h2>
      <p class="text-sm">Interaja com compradores.</p>
    </div>
    <div class="p-5 bg-white dark:bg-gray-800 rounded shadow border-l-4 border-pink-500">
      <h2 class="font-semibold mb-2">Relatórios</h2>
      <p class="text-sm">Acompanhe desempenho (em breve).</p>
    </div>
  </div>
</div>

<!-- Leaflet.js CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicia com a posição salva, ou padrão Araras-SP
    var lat = parseFloat(document.getElementById('lat').value) || -22.3572;
    var lon = parseFloat(document.getElementById('lon').value) || -47.3842;
    var map = L.map('map').setView([lat, lon], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = null;
    if (!isNaN(lat) && !isNaN(lon) && (lat !== -22.3572 && lon !== -47.3842)) {
        marker = L.marker([lat, lon]).addTo(map)
            .bindPopup('Localização atual da empresa').openPopup();
    }

    async function buscarCoordenadasPorEndereco(endereco) {
        const query = encodeURIComponent(endereco);
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;
        const resposta = await fetch(url);
        const dados = await resposta.json();
        if (dados.length === 0) return null;
        return {
            lat: parseFloat(dados[0].lat),
            lon: parseFloat(dados[0].lon)
        };
    }

    document.getElementById('endereco').addEventListener('blur', async function() {
        let endereco = this.value;
        if (!endereco || endereco.length < 8) {
            alert('Endereço inválido');
            return;
        }
        let coordenadas = await buscarCoordenadasPorEndereco(endereco);
        if (!coordenadas) {
            alert('Não foi possível localizar no mapa');
            return;
        }
        document.getElementById('lat').value = coordenadas.lat;
        document.getElementById('lon').value = coordenadas.lon;

        map.setView([coordenadas.lat, coordenadas.lon], 16);
        if (marker) map.removeLayer(marker);
        marker = L.marker([coordenadas.lat, coordenadas.lon]).addTo(map)
            .bindPopup('Confirme a localização da empresa!').openPopup();
    });

    // Garante que lat/lon são enviados ao enviar o formulário
    document.getElementById('form-endereco').addEventListener('submit', function(e) {
        if (!document.getElementById('lat').value || !document.getElementById('lon').value) {
            e.preventDefault();
            alert('Por favor, preencha um endereço válido e aguarde o mapa carregar a localização!');
        }
    });
});
</script>
@endsection