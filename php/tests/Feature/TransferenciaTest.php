<?php

namespace Tests\Feature;

use Database\Seeders\ClienteSeeder;
use Database\Seeders\TokenAcessoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TransferenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_trasnferir_sucesso(): void
    {
        $this->seed(ClienteSeeder::class);
        $this->seed(TokenAcessoSeeder::class);

        $response = $this->post(
            '/api/transferir/',
            [
                'recebedor_id' => 2,
                'valor' => 100,
                'notificacao' => 'email'
            ],
            ['Authorization' => 'abcdefghijkl0123456789']
        );

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('mensagem', "Operação realizada com sucesso")
                ->etc()
            );;

        $response->assertStatus(200);
    }


}
