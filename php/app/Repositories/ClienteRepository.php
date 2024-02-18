<?php

namespace App\Repositories;

use App\Models\Cliente;
use App\Models\TokenAcesso;

class ClienteRepository
{
    public function byEmail(string $email) : Cliente | null
    {
        return Cliente::where('email', $email)->first();
    }

    public function gerarTokenAcesso(int $clienteId)
    {
        return TokenAcesso::create([
            'cliente_id' => $clienteId,
            'token' => md5($clienteId . uniqid()),
            'expires_at' => date("Y-m-d H:i:s", strtotime('+8 hours'))
        ]);
    }
}
