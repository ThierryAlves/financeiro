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
     * Handle an authentication attempt.
     */
    public function login(AutenticarRequest $request): Response
    {
        $cliente = $this->loginService->autenticarUsuario($request->all());
        return response($cliente);
    }
}
