<?php

namespace App\Http\Interfaces;

use App\Models\Cliente;

interface NotificadorPagamento
{
    public function notificar(Cliente $remetente, string $cabecalho, string $corpo);
}

