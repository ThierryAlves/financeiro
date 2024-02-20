<?php

namespace App\Http\Service;

use App\Http\Interfaces\NotificadorPagamento;
use App\Models\Cliente;
use DomainException;
use Illuminate\Support\Facades\Http;

class NotificadorPagamentoSmsService implements NotificadorPagamento{

    public function notificar(Cliente $cliente, string $assunto, string $mensagem) {
        $postBody = [
            'telefone' => $cliente->telefone,
            'assunto' => $assunto,
            'mensagem' => $mensagem
        ];

        $response = Http::post(
            'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6',
            $postBody
        );

        $response = json_decode($response->body());

        if ($response->message !== true) {
            throw new DomainException("SMS de notificação não enviado.");
        }
    }
}
