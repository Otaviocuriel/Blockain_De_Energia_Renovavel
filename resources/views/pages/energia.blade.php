@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-green-600">Mapa de Energia Sustentável - Região de Araras (SP)</h1>
    
    <!-- Campo de CEP -->
    <div class="mb-6">
        <label for="cep" class="block font-semibold text-green-700 mb-2">CEP da nova empresa:</label>
        <input type="text" id="cep" name="cep" maxlength="9" placeholder="Digite o CEP"
            class="border rounded px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-green-400">
    </div>
    
    <div id="map" class="w-full h-96 rounded-lg shadow mb-8"></div>
    <h2 class="text-2xl font-semibold mb-4 text-green-500">Empresas de Energia</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-4 text-gray-900">
            <h3 class="font-bold text-lg">Araras Solar</h3>
            <p>Cidade: Araras</p>
            <p>Tipo: Solar</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-gray-900">
            <h3 class="font-bold text-lg">Energia Limpa Leme</h3>
            <p>Cidade: Leme</p>
            <p>Tipo: Solar/Eólica</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-gray-900">
            <h3 class="font-bold text-lg">Rio Claro Sustentável</h3>
            <p>Cidade: Rio Claro</p>
            <p>Tipo: Solar</p>
        </div>
    </div>
</div>

<!-- Leaflet.js CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o mapa
        var map = L.map('map').setView([-22.3572, -47.3842], 12); // Araras centro
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Markers estáticos (cidades)
        var cidadesLayer = L.layerGroup().addTo(map);
        var cidades = [
            {nome: 'Araras', coords: [-22.3572, -47.3842], empresa: 'Araras Solar'},
            {nome: 'Leme', coords: [-22.1807, -47.3841], empresa: 'Energia Limpa Leme'},
            {nome: 'Rio Claro', coords: [-22.4149, -47.5606], empresa: 'Rio Claro Sustentável'}
        ];
        cidades.forEach(function(cidade) {
            L.marker(cidade.coords).addTo(cidadesLayer)
                .bindPopup('<b>' + cidade.nome + '</b><br>' + cidade.empresa);
        });

        // Layer dinâmico para empresas vindas do banco
        var empresasLayer = L.layerGroup().addTo(map);

        async function loadEmpresas() {
            try {
                const res = await fetch('{{ route("api.empresas") }}');
                if (!res.ok) throw new Error('Erro ao buscar empresas: ' + res.status);
                const empresas = await res.json();
                empresasLayer.clearLayers();

                if (!empresas || empresas.length === 0) {
                    return;
                }

                empresas.forEach(function(emp) {
                    var lat = parseFloat(emp.latitude);
                    var lon = parseFloat(emp.longitude);
                    if (!isNaN(lat) && !isNaN(lon)) {
                        L.marker([lat, lon])
                            .addTo(empresasLayer)
                            .bindPopup('<b>' + (emp.name || 'Empresa') + '</b><br>CEP: ' + (emp.cep || 'N/A'));
                    }
                });
            } catch (err) {
                console.error('Erro ao carregar empresas:', err);
            }
        }

        // Carrega inicialmente e a cada 20s para captar novas empresas salvas sem recarregar a página
        loadEmpresas();
        setInterval(loadEmpresas, 20000);

        // Marcação temporária ao pesquisar CEP (não salva no banco aqui)
        var novoMarker = null;

        // Função para buscar endereço pelo CEP
        async function buscarEnderecoPorCep(cep) {
            const resposta = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            if (!resposta.ok) return null;
            const dados = await resposta.json();
            if (dados.erro) return null;
            return dados;
        }

        // Função para buscar coordenadas no Nominatim (OpenStreetMap)
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

        // Evento ao sair do campo CEP
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
            let enderecoCompleto = `${endereco.logradouro}, ${endereco.localidade}, ${endereco.uf}`;
            let coordenadas = await buscarCoordenadasPorEndereco(enderecoCompleto);
            if (!coordenadas) {
                alert('Não foi possível localizar no mapa');
                return;
            }
            // Centraliza o mapa e adiciona (ou move) marcador temporário
            map.setView([coordenadas.lat, coordenadas.lon], 15);
            if (novoMarker) empresasLayer.removeLayer(novoMarker);
            novoMarker = L.marker([coordenadas.lat, coordenadas.lon]).addTo(empresasLayer)
                .bindPopup('<b>Nova Empresa</b><br>' + enderecoCompleto)
                .openPopup();
        });
    });
</script>
@endsection