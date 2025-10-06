<!-- Campo CEP -->
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

<!-- Imports do Leaflet (coloque só uma vez no arquivo) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Script para centralizar o mapa ao digitar o CEP -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([-22.3572, -47.3842], 10); // posição inicial Araras-SP
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