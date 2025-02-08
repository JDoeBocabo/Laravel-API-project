<?php

namespace Database\Factories;

use App\Models\Post;
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
            "comment" => $this->faker->sentence,
            "user_id" => $this->faker->numberBetween(5, 20),
            "post_id" => $this->faker->numberBetween(5, 20),
        ];
    }
}
