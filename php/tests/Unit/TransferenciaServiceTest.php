<?php

namespace Tests\Unit;

use App\Http\Factories\NotificadorPagamentoFactory;
use App\Http\Service\AutorizadorTransacaoService;
use App\Http\Service\NotificadorPagamentoEmailService;
use App\Http\Service\NotificadorPagamentoSmsService;
use App\Http\Service\TransferenciaService;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Repositories\TransacaoRepository;
use Mockery;
use Tests\TestCase;

class TransferenciaServiceTest extends TestCase
{

    private $clienteRepository;
    private $transacaoRepository;
    private $autorizadorTransacaoService;
    private $notificadorFactory;
    private $notificadorEmail;
    private $notificadorSms;


    protected function setUp(): void
    {
        parent::setUp();
        $this->clienteRepository = Mockery::mock(ClienteRepository::class);
        $this->transacaoRepository = Mockery::mock(TransacaoRepository::class);
        $this->autorizadorTransacaoService = Mockery::mock(AutorizadorTransacaoService::class);
        $this->notificadorFactory = Mockery::mock(NotificadorPagamentoFactory::class);
        $this->notificadorEmail = Mockery::mock(NotificadorPagamentoEmailService::class);
        $this->notificadorSms = Mockery::mock(NotificadorPagamentoSmsService::class);
    }

    private function getMockCliente(array $dados) : Cliente
    {
        return (new Cliente)->setRawAttributes($dados);
    }

    /**
     * Testa transferencia sem definir um notificador manualmente
     */
    public function test_transferir_notificador_padrao_sucesso(): void
    {
        $tokenTeste = 'UmTokenGenericoParaTeste';
        $dadosClientePagante = [
            'id' => 1,
            'email' => 'testePagante@mail.com',
            'tipo' => 1,
            'saldo' => 100
        ];
        $dadosClienteRecebedor = [
            'id' => 2,
            'email' => 'testeRecebedor@mail.com',
            'tipo' => 1,
            'saldo' => 150
        ];
        $valor = 50;

        $this->clienteRepository->shouldReceive('byToken')
            ->with($tokenTeste)
            ->once()
            ->andReturn($this->getMockCliente($dadosClientePagante));
        $this->clienteRepository->shouldReceive('byId')
            ->with(2)
            ->once()
            ->andReturn($this->getMockCliente($dadosClienteRecebedor));

        $this->autorizadorTransacaoService->shouldReceive('checarAutorizacao')
            ->times(1)
            ->andReturn();

        $this->transacaoRepository->shouldReceive('adicionarTransacao')
            ->once();

        $this->clienteRepository->shouldReceive('atualizarSaldo')
            ->with(1, 50)
            ->once();
        $this->clienteRepository->shouldReceive('atualizarSaldo')
            ->with(2, 200)
            ->once();

        $this->notificadorEmail->shouldReceive('notificar')
            ->once();
        $this->notificadorFactory->shouldReceive('definirNotificadorPagamento')
            ->with(null)
            ->once()
            ->andReturn($this->notificadorEmail);


        $transferencia = new TransferenciaService(
            $this->clienteRepository,
            $this->transacaoRepository,
            $this->autorizadorTransacaoService,
            $this->notificadorFactory
        );
        $transferencia->transferir($dadosClienteRecebedor['id'], $valor, $tokenTeste);
    }

    /**
     * Testa transferencia definindo notificador manualmente como sms
     */
    public function test_transferir_notificador_sms_sucesso(): void
    {
        $tokenTeste = 'UmTokenGenericoParaTeste';
        $dadosClientePagante = [
            'id' => 1,
            'email' => 'testePagante@mail.com',
            'tipo' => 1,
            'saldo' => 100
        ];
        $dadosClienteRecebedor = [
            'id' => 2,
            'email' => 'testeRecebedor@mail.com',
            'tipo' => 1,
            'saldo' => 150
        ];
        $valor = 50;

        $this->clienteRepository->shouldReceive('byToken')
            ->with($tokenTeste)
            ->once()
            ->andReturn($this->getMockCliente($dadosClientePagante));
        $this->clienteRepository->shouldReceive('byId')
            ->with(2)
            ->once()
            ->andReturn($this->getMockCliente($dadosClienteRecebedor));

        $this->autorizadorTransacaoService->shouldReceive('checarAutorizacao')
            ->times(1)
            ->andReturn();

        $this->transacaoRepository->shouldReceive('adicionarTransacao')
            ->once();

        $this->clienteRepository->shouldReceive('atualizarSaldo')
            ->with(1, 50)
            ->once();
        $this->clienteRepository->shouldReceive('atualizarSaldo')
            ->with(2, 200)
            ->once();

        $this->notificadorSms->shouldReceive('notificar')
            ->once();
        $this->notificadorFactory->shouldReceive('definirNotificadorPagamento')
            ->with('sms')
            ->once()
            ->andReturn($this->notificadorSms);


        $transferencia = new TransferenciaService(
            $this->clienteRepository,
            $this->transacaoRepository,
            $this->autorizadorTransacaoService,
            $this->notificadorFactory
        );
        $transferencia->transferir($dadosClienteRecebedor['id'], $valor, $tokenTeste, 'sms');
    }

    /**
     * Testa transferencia realizada por lojista
     */
    public function test_transferir_por_lojista_erro(): void
    {
        $tokenTeste = 'UmTokenGenericoParaTeste';
        $dadosClientePagante = [
            'id' => 1,
            'email' => 'testePagante@mail.com',
            'tipo' => 2,
            'saldo' => 100
        ];
        $dadosClienteRecebedor = [
            'id' => 2,
            'email' => 'testeRecebedor@mail.com',
            'tipo' => 1,
            'saldo' => 150
        ];
        $valor = 50;

        $this->clienteRepository->shouldReceive('byToken')
            ->with($tokenTeste)
            ->once()
            ->andReturn($this->getMockCliente($dadosClientePagante));
        $this->clienteRepository->shouldReceive('byId')
            ->with(2)
            ->once()
            ->andReturn($this->getMockCliente($dadosClienteRecebedor));

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não possui permissão para realizar tranferencias');

        $transferencia = new TransferenciaService(
            $this->clienteRepository,
            $this->transacaoRepository,
            $this->autorizadorTransacaoService,
            $this->notificadorFactory
        );
        $transferencia->transferir($dadosClienteRecebedor['id'], $valor, $tokenTeste, 'sms');
    }

    /**
     * Testa transferencia realizada para si mesmo
     */
    public function test_transferir_para_si_erro(): void
    {
        $tokenTeste = 'UmTokenGenericoParaTeste';
        $dadosClientePagante = [
            'id' => 1,
            'email' => 'testePagante@mail.com',
            'tipo' => 2,
            'saldo' => 100
        ];

        $valor = 50;

        $this->clienteRepository->shouldReceive('byToken')
            ->with($tokenTeste)
            ->once()
            ->andReturn($this->getMockCliente($dadosClientePagante));
        $this->clienteRepository->shouldReceive('byId')
            ->with(1)
            ->once()
            ->andReturn($this->getMockCliente($dadosClientePagante));

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é permitido realizar transferencias para si mesmo');

        $transferencia = new TransferenciaService(
            $this->clienteRepository,
            $this->transacaoRepository,
            $this->autorizadorTransacaoService,
            $this->notificadorFactory
        );
        $transferencia->transferir(1, $valor, $tokenTeste, 'sms');
    }
}
