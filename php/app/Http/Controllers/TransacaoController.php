<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferenciaRequest;
use App\Http\Service\TransacaoService;
use Illuminate\Support\Facades\DB;

class TransacaoController extends Controller
{
    private TransacaoService $transacaoService;
    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }

    public function transferir(TransferenciaRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->transacaoService->transferir(
                $request->input('recebedor_id'),
                $request->input('valor'),
                $request->header('authorization')
            );
            DB::commit();

            return response(['mensagem' => "Operação realizada com sucesso"]);
        }catch (\Throwable $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
