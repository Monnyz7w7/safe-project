<?php

namespace Database\Factories;

use App\Models\User;
use App\Enum\Status;
use App\Enum\SponsorshipType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsorship>
 */
class SponsorshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => str($this->faker->sentence())->title(),
            'description' => $this->faker->realText(500),
            'link' => $this->faker->url(),
            'image_url' => $this->faker->imageUrl(),
            'user_id' => User::factory(),
            'status' => Status::UNDER_REVIEW,
            'type' => SponsorshipType::cases()[array_rand(SponsorshipType::cases())]->value
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::APPROVED,
                'approved_at' => now(),
                'user_id' => User::factory()->approved()
            ];
        });
    }

    public function declined()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::DECLINED,
            ];
        });
    }
}
