<?php

namespace App\Http\Service;

use App\Http\Interfaces\NotificadorPagamento;
use App\Models\Cliente;
use DomainException;
use Illuminate\Support\Facades\Http;

class NotificadorPagamentoEmailService implements NotificadorPagamento{
    public function notificar(Cliente $cliente, string $assunto, string $corpo) {
        $postBody = [
            'remetente' => 'exemplo.remetente@mail.com',
            'destinatario' => $cliente->email,
            'assunto' => $assunto,
            'corpo' => $corpo
        ];

        $response = Http::post(
            'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc',
            $postBody
        );

        $response = json_decode($response->body());

        if ($response->message !== 'Autorizado') {
            throw new DomainException("Transação não autorizada.");
        }
    }
}
