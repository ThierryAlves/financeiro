<?php

namespace Database\Seeders;

use App\Models\TokenAcesso;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TokenAcessoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insert = [
            [
                'cliente_id' => 1,
                'token' => 'abcdefghijkl0123456789',
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'expires_at' => Carbon::now()->addHours(8),
            ],
        ];

        TokenAcesso::insert($insert);
    }
}
