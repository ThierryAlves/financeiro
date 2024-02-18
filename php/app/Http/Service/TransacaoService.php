<?php

namespace App\Http\Service;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Repositories\TransacaoRepository;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransacaoService
{
    private ClienteRepository $clienteRepository;
    private TransacaoRepository $transacaoRepository;

    public function __construct(ClienteRepository $clienteRepository, TransacaoRepository $transacaoRepository)
    {
        $this->clienteRepository = $clienteRepository;
        $this->transacaoRepository = $transacaoRepository;
    }

    public function transferir(int $recebedor_id, float $valor, string $token)
    {
        $requisitor = $this->clienteRepository->byToken($token);
        $recebedor =  $this->clienteRepository->byId($recebedor_id);

        if (! $recebedor) {
            throw new \InvalidArgumentException("Cliente recebedor não foi encontrado");
        }

        $this->validarPermissoesTransacao($requisitor, $recebedor);
        $this->atualizarSaldos($requisitor, $recebedor, $valor);
        $this->enviarEmailNotificacao($recebedor->email);
    }

    private function validarPermissoesTransacao(Cliente $requisitor, Cliente $recebedor)
    {
        if ($requisitor->id === $recebedor->id) {
            throw new \DomainException("Não é permitido realizar transferencias para si mesmo");
        }

        if ($requisitor->tipo === Cliente::CLIENTE_TIPO_PESSOA_JURIDICA) {
            throw new \DomainException("Usuário não possui permissão para realizar tranferencias");
        }
    }

    private function enviarEmailNotificacao(string $email)
    {
        $response = Http::post(
            'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6',
            ['email' => $email]
        );

        $response = json_decode($response->body());

        if ($response->message !== true) {
            throw new \DomainException("Ocorreu um erro ao enviar o e-mail de notificação");
        }
    }

    private function atualizarSaldos(Cliente $requisitor, Cliente $recebedor, float $valor)
    {
        $novoSaldoRequisitor = $requisitor->saldo - $valor;
        $novoSaldoRecebedor = $recebedor->saldo + $valor;

        if ($novoSaldoRequisitor < 0) {
            throw new \DomainException("Usuário não possui saldo suficiente para essa operação");
        }

        $this->transacaoRepository->adicionarTransacao($requisitor->id, $recebedor->id, $valor);
        $this->clienteRepository->atualizarSaldo($requisitor->id, $novoSaldoRequisitor);
        $this->clienteRepository->atualizarSaldo($recebedor->id, $novoSaldoRecebedor);
    }
}
