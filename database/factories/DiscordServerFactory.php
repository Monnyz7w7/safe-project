<?php

namespace Database\Factories;

use App\Models\User;
use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscordServer>
 */
class DiscordServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'server_id' => $this->faker->numberBetween(100000000000000000, 999999999999999999),
            'name' => str($this->faker->words(3, asText: true))->title(),
            'invitation_link' => $this->faker->url(),
            'user_id' => User::factory()
        ];
    }
}
