<?php

namespace App\Http\Factories;

use App\Http\Interfaces\NotificadorPagamento;
use App\Http\Service\NotificadorPagamentoEmailService;
use App\Http\Service\NotificadorPagamentoSmsService;

class NotificadorPagamentoFactory
{
    private NotificadorPagamentoEmailService $notificadorPagamentoEmail;
    private NotificadorPagamentoSmsService $notificadorPagamentoSms;

    public function __construct(NotificadorPagamentoEmailService $notificadorPagamentoEmail, NotificadorPagamentoSmsService $notificadorPagamentoSms)
    {
        $this->notificadorPagamentoEmail = $notificadorPagamentoEmail;
        $this->notificadorPagamentoSms = $notificadorPagamentoSms;
    }

    public function definirNotificadorPagamento(?string $notificador) : NotificadorPagamento
    {
        return match ($notificador) {
            'email' => $this->notificadorPagamentoEmail,
            'sms' => $this->notificadorPagamentoSms,
            default => $this->notificadorPagamentoEmail,
        };
    }
}
