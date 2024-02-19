<?php

namespace App\Http\Service;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Repositories\TransacaoRepository;
use DomainException;
use Illuminate\Support\Facades\Http;


class TransacaoService
{
    private ClienteRepository $clienteRepository;
    private TransacaoRepository $transacaoRepository;

    private Cliente $pagante;
    private Cliente $recebedor;

    public function __construct(ClienteRepository $clienteRepository, TransacaoRepository $transacaoRepository)
    {
        $this->clienteRepository = $clienteRepository;
        $this->transacaoRepository = $transacaoRepository;
    }

    public function transferir(int $recebedorId, float $valor, string $token)
    {
        $this->pagante = $this->clienteRepository->byToken($token);
        $this->recebedor =  $this->clienteRepository->byId($recebedorId);

        $this->validarPermissoesTransacao();
        $this->atualizarSaldos($valor);
        $this->enviarEmailNotificacao();
    }

    private function validarPermissoesTransacao() : void
    {
        if ($this->pagante->id === $this->recebedor->id) {
            throw new DomainException("Não é permitido realizar transferencias para si mesmo");
        }

        if ($this->pagante->tipo === Cliente::CLIENTE_TIPO_PESSOA_JURIDICA) {
            throw new DomainException("Usuário não possui permissão para realizar tranferencias");
        }

        $this->checarAutorizacao();
    }

    private function checarAutorizacao() : void
    {
        $response = Http::post(
            'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc'
        );

        $response = json_decode($response->body());

        if ($response->message !== 'Autorizado') {
            throw new DomainException("Transação não autorizada.");
        }
    }

    private function enviarEmailNotificacao() : void
    {
        $response = Http::post(
            'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6',
            ['email' => $this->recebedor->email]
        );

        $response = json_decode($response->body());

        if ($response->message !== true) {
            throw new DomainException("Ocorreu um erro ao enviar o e-mail de notificação");
        }
    }

    private function atualizarSaldos(float $valor) : void
    {
        $novoSaldoPagante = $this->pagante->saldo - $valor;
        $novoSaldoRecebedor = $this->recebedor->saldo + $valor;

        if ($novoSaldoPagante < 0) {
            throw new DomainException("Usuário não possui saldo suficiente para essa operação");
        }

        $this->transacaoRepository->adicionarTransacao($this->pagante->id, $this->recebedor->id, $valor);
        $this->clienteRepository->atualizarSaldo($this->pagante->id, $novoSaldoPagante);
        $this->clienteRepository->atualizarSaldo($this->recebedor->id, $novoSaldoRecebedor);
    }
}
