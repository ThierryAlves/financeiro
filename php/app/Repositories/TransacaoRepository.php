<?php

namespace App\Repositories;

use App\Models\TransacaoCliente;

class TransacaoRepository
{
    public function adicionarTransacao(int $requisitorId, int $recebedorId, float $valor)
    {
        TransacaoCliente::create([
            'cliente_pagante_id' => $requisitorId,
            'cliente_recebedor_id' => $recebedorId,
            'valor' => $valor
        ]);
    }
}
