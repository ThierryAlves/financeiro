<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferenciaRequest;
use App\Http\Service\TransferenciaService;

class TransacaoController extends Controller
{
    private TransferenciaService $transferenciaService;

    public function __construct(TransferenciaService $transferenciaService)
    {
        $this->transferenciaService = $transferenciaService;
    }

    /**
     * Realiza transferência entre 2 clientes
     *
     * @OA\Post(
     *   path="/api/transferir",
     *   tags={"transferencia"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(
     *                   property="recebedor_id",
     *                   type="int"
     *              ),
     *              @OA\Property(
     *                   property="valor",
     *                   type="float"
     *               ),
     *              @OA\Property(
     *                   property="notificacao",
     *                   type="string"
     *              ),
     *              example={"recebedor_id":2,"valor":10.5,"notificacao":"sms"}
     *           )
     *       )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Sucesso",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *        @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="Operação realizada com sucesso"
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="erro",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="Cliente recebedor não foi encontrado"
     *         ),
     *       )
     *     )
     *   ),
     * )
     *
     */
    public function transferir(TransferenciaRequest $request)
    {
            $this->transferenciaService->transferir(
                $request->input('recebedor_id'),
                $request->input('valor'),
                $request->header('authorization'),
                $request->input('notificacao')
            );

            return response(['mensagem' => "Operação realizada com sucesso"]);
    }
}
