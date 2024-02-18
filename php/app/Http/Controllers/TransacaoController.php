<?php

namespace App\Http\Controllers;

use App\Http\Service\TransacaoService;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    private TransacaoService $transacaoService;
    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }

    public function transferir(Request $request)
    {
        $this->transacaoService->transferir(
            $request->input('recebedor_id'),
            $request->input('valor'),
            $request->header('authorization')
        );

        return response(['mensagem' => "Operação realizada com sucesso"]);
    }
}
