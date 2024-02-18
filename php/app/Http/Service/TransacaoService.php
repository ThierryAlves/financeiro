<?php

namespace App\Http\Service;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Repositories\TransacaoRepository;
use Illuminate\Support\Facades\DB;

class TransacaoService
{
    private ClienteRepository $clienteRepository;
    private TransacaoRepository $transacaoRepository;

    public function __construct(ClienteRepository $clienteRepository, TransacaoRepository $transacaoRepository)
    {
        $this->clienteRepository = $clienteRepository;
        $this->transacaoRepository = $transacaoRepository;
    }

    public function transferir(int $recebedor_id, int $valor, string $token)
    {
        $requisitor = $this->clienteRepository->byToken($token);
        $recebedor =  $this->clienteRepository->byId($recebedor_id);

        if (! $recebedor) {
            throw new \InvalidArgumentException("Cliente recebedor não foi encontrado");
        }

        $this->validarPermissoesTransacao($requisitor, $recebedor);
        $this->atualizarSaldos($requisitor, $recebedor, $valor);
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

    private function atualizarSaldos(Cliente $requisitor, Cliente $recebedor, float $valor)
    {
        $novoSaldoRequisitor = $requisitor->saldo - $valor;
        $novoSaldoRecebedor = $recebedor->saldo + $valor;

        if ($novoSaldoRequisitor < 0) {
            throw new \DomainException("Usuário não possui saldo suficiente para essa operação");
        }

        DB::beginTransaction();
        $this->transacaoRepository->adicionarTransacao($requisitor->id, $recebedor->id, $valor);
        $this->clienteRepository->atualizarSaldo($requisitor->id, $novoSaldoRequisitor);
        $this->clienteRepository->atualizarSaldo($recebedor->id, $novoSaldoRecebedor);
        DB::commit();
    }
}
