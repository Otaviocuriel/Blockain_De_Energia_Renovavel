<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', [PageController::class,'home'])->name('home');

Route::get('/comentarios', [PageController::class,'comentarios'])->name('comentarios');
Route::post('/comentarios', [PageController::class,'comentariosPost'])->name('comentarios.post');
Route::post('/comentarios/{id}/like', [ComentarioController::class, 'like'])->name('comentarios.like');
Route::get('/servicos', [PageController::class,'servicos'])->name('servicos');
Route::get('/contratar/{empresa}', [PageController::class,'contratar'])->name('contratar');
Route::get('/contato', [PageController::class,'contato'])->name('contato');
Route::get('/mapa', [PageController::class,'mapa'])->name('mapa');
Route::get('/planos', [PageController::class,'planos'])->name('planos');
Route::get('/empresas', [PageController::class,'empresas'])->name('empresas');
Route::get('/usuario', [PageController::class,'usuario'])->name('usuario');

Route::post('/contratar/{empresa}', [PageController::class,'contratarPost'])->name('contratar.post');
Route::get('/energia', [PageController::class,'energia'])->name('energia');

// ==== ROTAS DE CADASTRO DE EMPRESA ====
// Formulário de cadastro de empresa
Route::get('/empresas/create', [PageController::class, 'empresaCreate'])->name('empresas.create');
// Salvando empresa cadastrada
Route::post('/empresas', [PageController::class, 'empresaStore'])->name('empresas.store');
// =======================================

Route::get('/dashboard', [DashboardController::class,'index'])
    ->middleware(['auth','verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::match(['get', 'post'], '/contratar/{empresa}', [PageController::class,'contratar'])->name('contratar');
Route::put('/comentarios/{id}', [ComentarioController::class, 'update'])->name('comentarios.update');
Route::get('/comentarios/{id}/edit', [ComentarioController::class, 'edit'])->name('comentarios.edit');
Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.delete');
Route::post('/contratar/{empresa}/confirmar', [App\Http\Controllers\ContratacaoController::class, 'confirmar'])->name('contratar.confirmar');
Route::get('/ofertas', [App\Http\Controllers\OfertaController::class, 'index'])->name('ofertas.index');
Route::post('/ofertas/{oferta}/contratar', [App\Http\Controllers\OfertaController::class, 'contratar'])
    ->name('ofertas.contratar')
    ->middleware('auth');
Route::get('/mapa-empresas', function() {
    $empresas = \App\Models\User::where('role', 'company')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();
    return view('mapa.empresas', compact('empresas'));
})->name('mapa.empresas');

// Rota pública JSON para o mapa: retorna empresas com coordenadas
Route::get('/api/empresas', function() {
    return \App\Models\User::where('role', 'company')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get(['id','name','email','cep','latitude','longitude']);
})->name('api.empresas');


// Excluir empresa (apenas admin)
Route::delete('/admin/empresa/{id}', function($id) {
    $user = Auth::user();
    if (!$user || $user->role !== 'admin') {
        abort(403);
    }
    \App\Models\User::where('id', $id)->where('role', 'company')->delete();
    return response()->noContent();
})->middleware('auth');

// Excluir comentário (apenas admin)
Route::delete('/admin/comentario/{id}', function($id) {
    $user = Auth::user();
    if (!$user || $user->role !== 'admin') {
        abort(403);
    }
    \App\Models\Comentario::where('id', $id)->delete();
    return response()->noContent();
})->middleware('auth');

Route::post('/empresa/site/update', function(Request $request) {
    $user = auth()->user();
    if ($user && $user->role === 'company') {
        $request->validate([
            'website' => 'required|url|max:255'
        ]);
        $user->website = $request->website;
        $user->save();
        return back()->with('success', 'Site atualizado com sucesso!');
    }
    abort(403);
})->name('empresa.site.update')->middleware('auth');

/*
 * ROTA ATUALIZADA: aceita latitude/longitude enviados pelo formulário (preferred),
 * ou faz geocoding caso não venham. Também tenta melhorar geocode com ViaCEP quando o
 * usuário inseriu somente o CEP.
 */
Route::post('/empresa/endereco/update', function(Request $request) {
    $user = auth()->user();
    if ($user && $user->role === 'company') {
        $request->validate([
            'endereco' => 'required|string|max:255',
            // aceita lat/lon como nomes curtos ou completos
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user->endereco = $request->endereco;

        // Verifica se o frontend já enviou coordenadas (prioridade)
        $latFromRequest = $request->input('lat') ?? $request->input('latitude');
        $lonFromRequest = $request->input('lon') ?? $request->input('longitude');

        if ($latFromRequest && $lonFromRequest) {
            $user->latitude = $latFromRequest;
            $user->longitude = $lonFromRequest;
        } else {
            // Se o endereco for provavelmente um CEP, tenta expandir com ViaCEP
            $enderecoParaGeocode = $user->endereco;
            $cepSomenteNumeros = preg_replace('/\D/', '', $user->endereco);
            if (strlen($cepSomenteNumeros) === 8) {
                $viacep = Http::get("https://viacep.com.br/ws/{$cepSomenteNumeros}/json/");
                if ($viacep->ok() && !isset($viacep->json()['erro'])) {
                    $v = $viacep->json();
                    $logradouro = $v['logradouro'] ?? '';
                    $bairro = $v['bairro'] ?? '';
                    $localidade = $v['localidade'] ?? '';
                    $uf = $v['uf'] ?? '';
                    $enderecoParaGeocode = trim("{$logradouro}, {$bairro}, {$localidade}, {$uf}, Brasil");
                }
            }

            // Chamada ao Nominatim com User-Agent (recomendado)
            $response = Http::withHeaders([
                'User-Agent' => 'CommitTCC/1.0 (contato@seuemail.com)',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $enderecoParaGeocode,
                'format' => 'json',
                'limit' => 1,
            ]);

            if ($response->ok() && count($response->json()) > 0) {
                $geo = $response->json()[0];
                $user->latitude = $geo['lat'];
                $user->longitude = $geo['lon'];
            } else {
                $user->latitude = null;
                $user->longitude = null;
            }
        }

        $user->save();
        return back()->with('success', 'Endereço atualizado e empresa posicionada no mapa!');
    }
    abort(403);
})->name('empresa.endereco.update')->middleware('auth');

require __DIR__.'/auth.php';