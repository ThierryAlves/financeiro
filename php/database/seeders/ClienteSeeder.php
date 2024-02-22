<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            [
                'nome' => 'Cliente PF 1',
                'documento' => fake('pt_BR')->cpf(false),
                'email' => 'clientepf1@mail.com',
                'senha' => Hash::make('senhaClientePf1'),
                'telefone' => fake('pt_BR')->phoneNumberCleared(),
                'tipo' => 1,
                'saldo' => '1000',
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->addDays(rand(1, 30)),
            ],
            [
                'nome' => 'Cliente PJ 1',
                'documento' => fake('pt_BR')->cnpj(false),
                'email' => 'clientepj1@mail.com',
                'senha' => Hash::make('senhaClientePj1'),
                'telefone' => fake('pt_BR')->phoneNumberCleared(),
                'tipo' => 2,
                'saldo' => '1000',
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->addDays(rand(1, 30)),
            ],

        ];

        Cliente::insert($insert);
    }
}
