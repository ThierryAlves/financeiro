<?php

namespace App\Http\Service;

use DomainException;
use Illuminate\Support\Facades\Http;

class AutorizadorTransacaoService
{
    public function checarAutorizacao() : void
    {
        $response = Http::post(
            'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc'
        );

        $response = json_decode($response->body());

        if ($response->message !== 'Autorizado') {
            throw new DomainException("Transação não autorizada.");
        }
    }
}
