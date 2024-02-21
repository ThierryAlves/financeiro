<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtualizarClienteRequest;
use App\Http\Requests\CriarClienteRequest;
use App\Http\Service\ClienteService;
use App\Models\Cliente;
use Illuminate\Http\Response;


class ClienteController extends Controller
{
    private ClienteService $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    /**
     * Cria um novo cliente
     *
     * @OA\Post(
     *   path="/api/cliente/criar/",
     *   tags={"cliente"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(
     *                   property="nome",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="documento",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="email",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="senha",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="telefone",
     *                   type="string"
     *              ),
     *              example={"nome":"Exemplo Nome","documento":"111.111.111-11","email":"emailexemplo@gmail.com","senha":"senhaExemplo123","telefone":"(11) 91111-1111"}
     *           )
     *       )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Sucesso",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="nome",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="documento",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="email",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="telefone",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="tipo",
     *           type="int"
     *         ),
     *         @OA\Property(
     *           property="updated_at",
     *           type="date"
     *         ),
     *         @OA\Property(
     *           property="created_at",
     *           type="date"
     *         ),
     *         @OA\Property(
     *           property="id",
     *           type="int"
     *         ),
     *         example={"nome":"Exemplo Nome","documento":"11111111111","email":"emailexemplo@gmail.com","telefone":"11951380478","tipo":2,"updated_at":"2024-02-20T23:20:52.000000Z","created_at":"2024-02-20T23:20:52.000000Z","id":7}
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
     *           example="Nome é obrigatório."
     *         ),
     *       )
     *     )
     *   ),
     * )
     *
     */
    public function criar(CriarClienteRequest $request) : Response
    {
        $cliente = $this->clienteService->criar($request->validated());
        return response($cliente);
    }


    /**
     * Recupera um cliente
     *
     * @OA\Get(
     *   path="/api/cliente/{id}",
     *   tags={"cliente"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id do cliente",
     *     example=1,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Sucesso",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="nome",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="documento",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="email",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="telefone",
     *           type="string"
     *         ),
     *         @OA\Property(
     *           property="tipo",
     *           type="int"
     *         ),
     *         @OA\Property(
     *           property="updated_at",
     *           type="date"
     *         ),
     *         @OA\Property(
     *           property="created_at",
     *           type="date"
     *         ),
     *         @OA\Property(
     *           property="id",
     *           type="int"
     *         ),
     *         example={"nome":"Exemplo Nome","documento":"11111111111","email":"emailexemplo@gmail.com","telefone":"11951380478","tipo":2,"updated_at":"2024-02-20T23:20:52.000000Z","created_at":"2024-02-20T23:20:52.000000Z","id":7}
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="erro",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="cliente não encontrado."
     *         ),
     *       )
     *     )
     *   ),
     * )
     *
     */
    public function recuperar(Cliente $cliente) : Response
    {
        return response($cliente);
    }

    /**
     * Atualiza dados de um cliente
     *
     * @OA\Patch(
     *   path="/api/cliente/atualizar/{id}",
     *   tags={"cliente"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id do cliente",
     *     example=1,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(
     *                   property="nome",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="documento",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="email",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="senha",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="telefone",
     *                   type="string"
     *              ),
     *              example={"nome":"Exemplo Nome","documento":"111.111.111-11","email":"emailexemplo@gmail.com","senha":"senhaExemplo123","telefone":"(11) 91111-1111"}
     *           )
     *       )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Sucesso",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="Cliente atualizado."
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="erro",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="Cliente não encontrado."
     *         ),
     *       )
     *     )
     *   ),
     * )
     *
     */
    public function atualizar(AtualizarClienteRequest $request, Cliente $cliente) : Response
    {
        $this->clienteService->atualizar($cliente, $request->validated());
        return response(['mensagem' => 'Dados atualizados.']);
    }


    /**
     * Exclui um usuário
     *
     * @OA\Delete(
     *   path="/api/cliente/excluir/{id}",
     *   tags={"cliente"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id do cliente",
     *     example=1,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Sucesso",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="Cliente excluido."
     *         ),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="erro",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="mensagem",
     *           type="string",
     *           example="Cliente não encontrado."
     *         ),
     *       )
     *     )
     *   ),
     * )
     *
     */
    public function excluir(Cliente $cliente) : Response
    {
        $this->clienteService->excluir($cliente);
        return response(['mensagem' => 'Cliente excluido.']);
    }
}
