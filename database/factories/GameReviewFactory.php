<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameReview>
 */
class GameReviewFactory extends Factory
{
    protected $model = GameReview::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_id' => Game::factory(),
            'rating' => $this->faker->numberBetween(1, 10),
            'review' => $this->faker->paragraph(3),
        ];
    }
}
