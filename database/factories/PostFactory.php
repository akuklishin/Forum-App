<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'userId' => $this->faker->numberBetween(1, 13),
            'subforumId' => $this->faker->numberBetween(1, 10),
            'title' => $this->faker->realText($maxNbChars = 50),
            'content' => $this->faker->text($maxNbChars = 3000),
            'scoreCount' => $this->faker->numberBetween(0, 100)
        ];
    }
}
