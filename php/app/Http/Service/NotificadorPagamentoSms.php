<?php

namespace App\Http\Service;

use App\Http\Interfaces\NotificadorPagamento;

class NotificadorPagamentoSms implements NotificadorPagamento{
    public function notificar(string $telefone, string $assunto, string $mensagem) {
        $response = Http::post(
            'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc'
        );

        $response = json_decode($response->body());

        if ($response->message !== 'Autorizado') {
            throw new DomainException("Transação não autorizada.");
        }
    }
}
