@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-6 text-green-700">Cadastrar Nova Empresa</h1>
    <form method="POST" action="{{ route('empresas.store') }}">
        @csrf
        <!-- Nome -->
        <div class="mb-4">
            <label for="name" class="block font-semibold text-green-700 mb-2">Nome da Empresa</label>
            <input type="text" id="name" name="name" required class="border rounded px-4 py-2 w-full">
        </div>
        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block font-semibold text-green-700 mb-2">Email</label>
            <input type="email" id="email" name="email" required class="border rounded px-4 py-2 w-full">
        </div>
        <!-- CNPJ -->
        <div class="mb-4">
            <label for="cnpj" class="block font-semibold text-green-700 mb-2">CNPJ</label>
            <input type="text" id="cnpj" name="cnpj" required class="border rounded px-4 py-2 w-full">
        </div>
        <!-- Telefone -->
        <div class="mb-4">
            <label for="telefone" class="block font-semibold text-green-700 mb-2">Telefone</label>
            <input type="text" id="telefone" name="telefone" required class="border rounded px-4 py-2 w-full">
        </div>
        <!-- Cargo -->
        <div class="mb-4">
            <label for="cargo" class="block font-semibold text-green-700 mb-2">Cargo</label>
            <input type="text" id="cargo" name="cargo" required class="border rounded px-4 py-2 w-full">
        </div>
        <!-- CEP -->
        <div class="mb-4">
            <label for="cep" class="block font-semibold text-green-700 mb-2">CEP</label>
            <input type="text" id="cep" name="cep" maxlength="9" placeholder="Digite o CEP"
                   class="border rounded px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-green-400" required>
        </div>
        <!-- Mapa -->
        <div id="map" style="height: 300px;" class="mb-4 rounded shadow"></div>
        <!-- Campos ocultos para latitude e longitude -->
        <input type="hidden" id="lat" name="lat">
        <input type="hidden" id="lon" name="lon">

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800">Cadastrar</button>
    </form>
</div>

<!-- Leaflet.js CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([-22.3572, -47.3842], 10); // Posição inicial: Araras-SP
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = null;

    async function buscarEnderecoPorCep(cep) {
        const resposta = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        if (!resposta.ok) return null;
        const dados = await resposta.json();
        if (dados.erro) return null;
        return dados;
    }

    async function buscarCoordenadasPorEndereco(endereco) {
        const query = encodeURIComponent(endereco);
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;
        const resposta = await fetch(url);
        const dados = await resposta.json();
        if (dados.length === 0) return null;
        return {
            lat: dados[0].lat,
            lon: dados[0].lon
        };
    }

    document.getElementById('cep').addEventListener('blur', async function() {
        let cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) {
            alert('CEP inválido');
            return;
        }
        let endereco = await buscarEnderecoPorCep(cep);
        if (!endereco) {
            alert('CEP não encontrado');
            return;
        }
        let enderecoCompleto = `${endereco.logradouro}, ${endereco.bairro}, ${endereco.localidade}, ${endereco.uf}`;
        let coordenadas = await buscarCoordenadasPorEndereco(enderecoCompleto);
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
});
</script>
@endsection