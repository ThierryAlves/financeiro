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

    public function criar(CriarClienteRequest $request)
    {
        $cliente = $this->clienteService->criar($request->validated());
        return response($cliente);
    }

    public function recuperar(Cliente $cliente) : Response
    {
        return response($cliente);
    }

    public function atualizar(AtualizarClienteRequest $request, Cliente $cliente) : Response
    {
        $this->clienteService->atualizar($cliente, $request->validated());
        return response(['mensagem' => 'Dados atualizados.']);
    }

    public function excluir(Cliente $cliente) : Response
    {
        $this->clienteService->excluir($cliente);
        return response(['mensagem' => 'Cliente excluido.']);
    }
}
