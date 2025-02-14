<?php

namespace Database\Factories;

use App\Models\User;
use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partnership>
 */
class PartnershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => str($this->faker->words(3, asText: true))->title(),
            'description' => $this->faker->realText(500),
            'link' => $this->faker->url(),
            'image_url' => $this->faker->imageUrl(),
            'user_id' => User::factory(),
            'status' => Status::UNDER_REVIEW,
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::APPROVED,
                'action_at' => now(),
            ];
        });
    }

    public function declined()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::DECLINED,
                'action_at' => now(),
            ];
        });
    }
}
