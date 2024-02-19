<?php

namespace App\Http\Service;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class LoginService
{
    private ClienteRepository $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function autenticarUsuario(array $dadosAutenticacao) : Cliente
    {
        $cliente = $this->validarDadosAcesso($dadosAutenticacao);
        $tokenAcesso = $this->clienteRepository->gerarTokenAcesso($cliente->id);
        $cliente->token = $tokenAcesso->token;

        return $cliente;
    }

    private function validarDadosAcesso(array $dadosAutenticacao) : Cliente
    {
        $cliente = $this->clienteRepository->byEmail($dadosAutenticacao['email']);

        if (! $cliente) {
            throw new InvalidArgumentException("Usuário não encontrado.", 404);
        }

        if(! Hash::check($dadosAutenticacao['senha'], $cliente->senha)) {
            throw new InvalidArgumentException("Senha incorreta.", 404);
        }

        return $cliente;
    }
}
