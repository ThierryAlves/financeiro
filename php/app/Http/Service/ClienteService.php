<?php

namespace App\Http\Service;

use App\Models\Cliente;
use App\Models\TipoCliente;
use App\Repositories\ClienteRepository;
use Illuminate\Support\Facades\Hash;

class ClienteService
{
    private ClienteRepository $clienteRepository;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function criar(array $dadosCliente) : Cliente
    {
        $tipo = strlen($dadosCliente['documento']) === 11 ? TipoCliente::TIPO_PESSOAFISICA : TipoCliente::TIPO_PESSOAJURIDICA;

        return $this->clienteRepository->criar(
            $dadosCliente['nome'],
            $dadosCliente['documento'],
            $dadosCliente['email'],
            Hash::make($dadosCliente['senha']),
            $dadosCliente['telefone'],
            $tipo
        );
    }

    public function atualizar(Cliente $cliente, array $novosDados) : void
    {
        $novosDados = array_merge($cliente->toArray(), $novosDados);
        $this->clienteRepository->atualizar($cliente, $novosDados);
    }

    public function excluir(Cliente $cliente) : void
    {
        $this->clienteRepository->excluir($cliente);
    }
}
