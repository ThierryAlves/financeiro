<?php

namespace App\Http\Service;

use App\Http\Factories\NotificadorPagamentoFactory;
use App\Http\Interfaces\NotificadorPagamento;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Repositories\TransacaoRepository;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class TransacaoService
{
    private ClienteRepository $clienteRepository;
    private TransacaoRepository $transacaoRepository;
    private NotificadorPagamentoFactory $notificadorPagamentoFactory;

    private Cliente $pagante;
    private Cliente $recebedor;

    public function __construct(ClienteRepository $clienteRepository, TransacaoRepository $transacaoRepository,NotificadorPagamentoFactory $notificadorPagamentoFactory)
    {
        $this->clienteRepository = $clienteRepository;
        $this->transacaoRepository = $transacaoRepository;
        $this->notificadorPagamentoFactory = $notificadorPagamentoFactory;
    }

    public function transferir(int $recebedorId, float $valor, string $token, ?string $notificador = null) : void
    {
        $this->pagante = $this->clienteRepository->byToken($token);
        $this->recebedor =  $this->clienteRepository->byId($recebedorId);

        $this->validarPermissoesTransacao();
        $this->atualizarSaldos($valor);
        $this->enviarNotificacao($notificador, $valor);
    }

    private function enviarNotificacao(?string $notificador, float $valor)
    {
        $service = $this->notificadorPagamentoFactory->definirNotificadorPagamento($notificador);
        $service->notificar(
            $this->recebedor,
            'Transferência Recebida',
            "Você recebeu uma transferência no valor de R$ {$valor} de {$this->pagante->nome}"
        );
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

    private function atualizarSaldos(float $valor) : void
    {
        $novoSaldoPagante = $this->pagante->saldo - $valor;
        $novoSaldoRecebedor = $this->recebedor->saldo + $valor;

        if ($novoSaldoPagante < 0) {
            throw new DomainException("Usuário não possui saldo suficiente para essa operação");
        }

        try {
            Db::beginTransaction();
            $this->transacaoRepository->adicionarTransacao($this->pagante->id, $this->recebedor->id, $valor);
            $this->clienteRepository->atualizarSaldo($this->pagante->id, $novoSaldoPagante);
            $this->clienteRepository->atualizarSaldo($this->recebedor->id, $novoSaldoRecebedor);
            DB::commit();
        }catch (\Throwable $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
