<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutenticarRequest;
use App\Http\Service\LoginService;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    private LoginService $loginService;
    public function __construct(LoginService $loginService){
        $this->loginService = $loginService;
    }

    /**
     * Realiza login no sistema
     *
     * @OA\Post(
     *   path="/api/login/",
     *   tags={"login"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(
     *                   property="email",
     *                   type="string"
     *              ),
     *              @OA\Property(
     *                   property="senha",
     *                   type="string"
     *              ),
     *              example={"email":"clientepf1@mail.com","senha":"senhaClientePf1"}
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
     *           property="saldo",
     *           type="integer"
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
     *         @OA\Property(
     *           property="token",
     *           type="string"
     *         ),
     *         example={"id":1,"nome":"Cliente PF 1","documento":"93311421108","email":"clientepf1@mail.com","saldo":1000,"telefone":"6137112480","tipo":1,"deleted_at":null,"created_at":"2024-01-28T15:55:38.000000Z","updated_at":"2024-03-11T15:55:38.000000Z","token":"3c0719d9ebcaf0d2a245549474b70d11"}
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
     *           example="Senha é obrigatório."
     *         ),
     *       )
     *     )
     *   ),
     * )
     *
     */
    public function login(AutenticarRequest $request): Response
    {
        $cliente = $this->loginService->autenticarUsuario($request->all());
        return response($cliente);
    }
}
