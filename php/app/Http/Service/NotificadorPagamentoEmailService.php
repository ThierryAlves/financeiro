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
            'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6',
            $postBody
        );

        $response = json_decode($response->body());

        if ($response->message !== true) {
            throw new DomainException("Email de notificação não enviado.");
        }
    }
}
