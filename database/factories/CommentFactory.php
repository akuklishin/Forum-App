<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'postId' => $this->faker->numberBetween(61, 110),
            'content' => $this->faker->text($maxNbChars = 100),
            'scoreCount' => $this->faker->numberBetween(0, 100)
        ];
    }
}
