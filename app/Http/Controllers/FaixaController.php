<?php

namespace App\Http\Controllers;

use App\Models\Faixa;
use App\Models\FaixaCriterio;
use Illuminate\Http\Request;

class FaixaController extends Controller {
    public function index() {
        $aFaixas = Faixa::with('criterios')->orderBy('ordem')->get();
        return view('faixas.index', compact('aFaixas'));
    }

    public function create() { 
      return view('faixas.cadastro');
    }

    public function store(Request $oRequest) {
        $aDados = $oRequest->validate([
              'nome'                 => 'required|string|max:100'
            , 'ordem'                => 'required|integer|min:1'
            , 'cor_hex'              => 'nullable|string|max:7'
            , 'ativa'                => 'boolean'
            , 'criterios'            => 'array'
            , 'criterios.*.chave'    => 'required|string'
            , 'criterios.*.operador' => 'nullable|string'
            , 'criterios.*.valor'    => 'nullable|numeric'
            , 'criterios.*.peso'     => 'nullable|numeric'
        ]);

        $faixa = Faixa::create([
              'nome'    => $aDados['nome']
            , 'ordem'   => $aDados['ordem']
            , 'cor_hex' => $aDados['cor_hex'] ?? null
            , 'ativa'   => $aDados['ativa'] ?? true
        ]);

        foreach ($aDados['criterios'] ?? [] as $aDado) {
            FaixaCriterio::create([
                  'faixa_id' => $faixa->id
                , 'chave'    => $aDado['chave']
                , 'operador' => $aDado['operador'] ?? '>='
                , 'valor'    => $aDado['valor'] ?? 0,
                , 'peso'     => $aDado['peso'] ?? null
            ]);
        }

        return redirect()->route('faixas.index')->with('success', 'Faixa criada!');
    }
}