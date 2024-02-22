<?php

namespace Tests\Feature;

use Database\Seeders\ClienteSeeder;
use Database\Seeders\TokenAcessoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_recuperar_cliente_sucesso(): void
    {
        $this->seed(ClienteSeeder::class);
        $this->seed(TokenAcessoSeeder::class);
        $response = $this->get('/api/cliente/1',  ['Authorization' => 'abcdefghijkl0123456789']);
        $response
            ->assertJsonStructure([
                'id',
                'nome',
                'documento',
                'email',
                'email',
                'telefone',
                'updated_at',
                'created_at'
            ])->assertJson(fn (AssertableJson $json) =>
            $json->where('id', 1)
                ->etc()
            );;

        $response->assertStatus(200);
    }
}
