<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(),
            'release_date' => fake()->dateTimeBetween('-2 years', '+1 year'),
            'genre' => fake()->randomElement([
                'Action',
                'Adventure',
                'RPG',
                'Strategy',
                'Simulation',
                'Sports',
                'Racing',
                'Puzzle',
                'Horror',
                'MMO'
            ]),
            'publisher' => fake()->company(),
            'developer' => fake()->company(),
            'is_multi_player' => fake()->boolean(),
            'is_single_player' => fake()->boolean(),
            'user_id' => User::factory()
        ];
    }
}
