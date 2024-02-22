<?php

namespace App\Http\Service;

use App\Http\Factories\NotificadorPagamentoFactory;
use App\Models\Cliente;
use App\Models\TipoCliente;
use App\Repositories\ClienteRepository;
use App\Repositories\TransacaoRepository;
use DomainException;
use Illuminate\Support\Facades\DB;


class TransferenciaService
{
    private ClienteRepository $clienteRepository;
    private TransacaoRepository $transacaoRepository;
    private AutorizadorTransacaoService $autorizadorTransacaoService;
    private NotificadorPagamentoFactory $notificadorPagamentoFactory;

    private Cliente $pagante;
    private Cliente $recebedor;

    public function __construct(
        ClienteRepository           $clienteRepository,
        TransacaoRepository         $transacaoRepository,
        AutorizadorTransacaoService $autorizadorTransacaoService,
        NotificadorPagamentoFactory $notificadorPagamentoFactory
    ){
        $this->clienteRepository = $clienteRepository;
        $this->transacaoRepository = $transacaoRepository;
        $this->autorizadorTransacaoService = $autorizadorTransacaoService;
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

    private function enviarNotificacao(?string $notificador, float $valor) : void
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

        if ($this->pagante->tipo === TipoCliente::TIPO_PESSOAJURIDICA) {
            throw new DomainException("Usuário não possui permissão para realizar tranferencias");
        }

        $this->autorizadorTransacaoService->checarAutorizacao();
    }

    private function atualizarSaldos(float $valor) : void
    {
        $novoSaldoPagante = $this->pagante->saldo - $valor;
        $novoSaldoRecebedor = $this->recebedor->saldo + $valor;

        if ($novoSaldoPagante < 0) {
            throw new DomainException("Usuário não possui saldo suficiente para essa operação");
        }

        DB::transaction(function () use ($valor, $novoSaldoPagante, $novoSaldoRecebedor) {
            $this->transacaoRepository->adicionarTransacao($this->pagante->id, $this->recebedor->id, $valor);
            $this->clienteRepository->atualizarSaldo($this->pagante->id, $novoSaldoPagante);
            $this->clienteRepository->atualizarSaldo($this->recebedor->id, $novoSaldoRecebedor);
        });
    }
}
