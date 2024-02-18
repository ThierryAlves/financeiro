<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'documento' => fake('pt_BR')->cpf(false),
            'email' => fake()->unique()->safeEmail(),
            'senha' => Hash::make('senhaTeste@123'),
            'token' => Hash::make(strtotime('now') . rand(1, 255)),
        ];
    }
}
