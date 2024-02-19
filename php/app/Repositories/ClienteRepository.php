<?php

namespace App\Repositories;

use App\Models\Cliente;
use App\Models\TokenAcesso;

class ClienteRepository
{
    public function byId(int $id) : ?Cliente
    {
        return Cliente::find($id);
    }

    public function byEmail(string $email) : ?Cliente
    {
        return Cliente::where('email', $email)->first();
    }

    public function byToken(string $token) : ?Cliente
    {
        return Cliente::select('clientes.*')
            ->join('tokens_acesso', 'cliente_id', 'clientes.id')
            ->where('token', $token)
            ->first();
    }

    public function gerarTokenAcesso(int $clienteId): ?string
    {
        return TokenAcesso::updateOrCreate(
            ['cliente_id' => $clienteId],
            [
                'token' => md5($clienteId . uniqid()),
                'expires_at' => date("Y-m-d H:i:s", strtotime('+8 hours'))
            ]
        )->token;
    }

    public function atualizarSaldo(int $id, float $novoSaldo) : void
    {
        Cliente::where('id', $id)
        ->update([
            'saldo' => $novoSaldo
        ]);
    }
}
