<?php

namespace Tests\Feature;

use Database\Seeders\ClienteSeeder;
use Database\Seeders\TokenAcessoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TransferenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_get_success(): void
    {
        $this->seed(ClienteSeeder::class);
        $this->seed(TokenAcessoSeeder::class);
        $response = $this->get('/api/cliente/1',  ['Authorization' => 'abcdefghijkl0123456789']);
        $response
            ->assertJsonStructure([
                'nome',
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
