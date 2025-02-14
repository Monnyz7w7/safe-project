<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DiscordServer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discord_server_id' => DiscordServer::factory(),
            'discord_username' => $this->faker->userName(),
            'discord_user_id' => $this->faker->numberBetween(100000000000000000, 999999999999999999),
            'discord_user_global_name' => $this->faker->name(),
            'discord_user_avatar_url' => $this->faker->imageUrl(width: 80, height: 80),
            'user_id' => User::factory()->approved(),
            'details' => $this->faker->realText(500)
        ];
    }
}
