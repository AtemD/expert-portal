<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => $this->faker->firstName.' '.$this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'client_id' => function () {
                return Client::factory()->create()->id;
            },
            'phone_number' => $this->faker->phoneNumber,
        ];

    }
}
