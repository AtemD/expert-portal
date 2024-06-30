<?php

namespace Database\Factories;

use App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->word;

        return [
            'name' => 'client_' . $name,
            'contract_status' => $this->faker->randomElement(array_keys(ContractStatus::toList())),
        ];
    }
}
