<?php

namespace App\Http\Interfaces;

interface NotificadorPagamento
{
    public function notificar(string $remetente, string $cabecalho, string $corpo);
}

