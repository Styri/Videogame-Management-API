<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use App\Models\GameReview;
use Illuminate\Database\Seeder;

class GameReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('role', function($query) {
            $query->where('name', 'Regular User');
        })->get();

        $gameReviews = [
            'Cyberpunk 2077' => [
                [
                    'rating' => 9,
                    'review' => 'After all the patches, this game is a masterpiece. Night City is breathtaking and the story is unforgettable.'
                ],
                [
                    'rating' => 7,
                    'review' => 'Great storyline and side quests. The RPG elements are deep and meaningful.'
                ],
                [
                    'rating' => 8,
                    'review' => 'Incredible attention to detail in the world design. Combat feels smooth now.'
                ]
            ],
            'Elden Ring' => [
                [
                    'rating' => 10,
                    'review' => 'A masterpiece that sets new standards for open-world games. Every area is filled with discovery and wonder.'
                ],
                [
                    'rating' => 9,
                    'review' => 'Challenging but fair. The combat system is incredibly satisfying once mastered.'
                ],
                [
                    'rating' => 8,
                    'review' => 'The world design and lore are incredibly deep. Boss fights are memorable.'
                ]
            ],
            'God of War Ragnarök' => [
                [
                    'rating' => 10,
                    'review' => 'An emotional journey with stunning visuals and intense combat. The relationship between Kratos and Atreus is beautifully written.'
                ],
                [
                    'rating' => 9,
                    'review' => 'Epic scale battles and touching story moments. The Norse mythology is well integrated.'
                ]
            ],
            'Baldur\'s Gate 3' => [
                [
                    'rating' => 10,
                    'review' => 'The most faithful D&D experience in a video game. Incredible depth of choice and consequences.'
                ],
                [
                    'rating' => 9,
                    'review' => 'Amazing character development and party interactions. Each playthrough feels unique.'
                ],
                [
                    'rating' => 10,
                    'review' => 'Sets a new standard for CRPGs. The attention to detail is unprecedented.'
                ]
            ],
            'Marvel\'s Spider-Man 2' => [
                [
                    'rating' => 9,
                    'review' => 'Swinging through New York has never felt better. The dual protagonist system works perfectly.'
                ],
                [
                    'rating' => 8,
                    'review' => 'Great story and improved combat system. Side missions could be more varied.'
                ]
            ],
            'Final Fantasy XVI' => [
                [
                    'rating' => 8,
                    'review' => 'Action-packed combat and amazing summon battles. The story takes some interesting turns.'
                ],
                [
                    'rating' => 7,
                    'review' => 'Solid combat mechanics but somewhat linear compared to previous titles.'
                ]
            ],
            'Resident Evil 4 Remake' => [
                [
                    'rating' => 9,
                    'review' => 'A perfect remake that respects the original while modernizing the gameplay.'
                ],
                [
                    'rating' => 10,
                    'review' => 'The tension and atmosphere are perfectly balanced. Every area is meticulously crafted.'
                ]
            ],
            'Hogwarts Legacy' => [
                [
                    'rating' => 8,
                    'review' => 'The magical world feels alive and authentic. Spell combinations are fun to experiment with.'
                ],
                [
                    'rating' => 7,
                    'review' => 'Great exploration and school life simulation. Combat becomes repetitive in late game.'
                ]
            ],
            'Diablo IV' => [
                [
                    'rating' => 8,
                    'review' => 'Dark atmosphere and satisfying loot system. Endgame needs more variety.'
                ],
                [
                    'rating' => 7,
                    'review' => 'Solid ARPG mechanics and beautiful world design. Story could be stronger.'
                ]
            ],
            'Horizon Forbidden West' => [
                [
                    'rating' => 9,
                    'review' => 'Gorgeous visuals and improved mechanics from the first game. Machine combat is thrilling.'
                ],
                [
                    'rating' => 8,
                    'review' => 'Aloy\'s journey continues with better character development and stunning environments.'
                ]
            ]
        ];

        foreach ($gameReviews as $gameTitle => $reviews) {
            $game = Game::where('title', $gameTitle)->first();

            if ($game) {
                foreach ($reviews as $review) {
                    GameReview::firstOrCreate(
                        [
                            'user_id' => $users->random()->id,
                            'game_id' => $game->id,
                            'review' => $review['review']
                        ],
                        [
                            'rating' => $review['rating']
                        ]
                    );
                }
            }
        }
    }
}
