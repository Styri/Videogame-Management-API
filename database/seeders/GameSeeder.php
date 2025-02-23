<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('role', function($query) {
            $query->where('name', 'Regular User');
        })->get();

        $games = [
            [
                'title' => 'Cyberpunk 2077',
                'description' => 'An open-world, action-adventure story set in Night City, a megalopolis obsessed with power, glamour and body modification.',
                'release_date' => '2020-12-10',
                'genre' => 'Action RPG',
                'publisher' => 'CD Projekt',
                'developer' => 'CD Projekt Red',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Elden Ring',
                'description' => 'An action RPG set in a vast fantasy realm. Players explore the Lands Between and face challenging combat encounters.',
                'release_date' => '2022-02-25',
                'genre' => 'Action RPG',
                'publisher' => 'Bandai Namco',
                'developer' => 'FromSoftware',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'God of War Ragnarök',
                'description' => 'Continue the journey of Kratos and Atreus through the nine realms as they face the approaching Ragnarök.',
                'release_date' => '2022-11-09',
                'genre' => 'Action-Adventure',
                'publisher' => 'Sony Interactive Entertainment',
                'developer' => 'Santa Monica Studio',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Baldur\'s Gate 3',
                'description' => 'A party-based RPG set in the Dungeons & Dragons universe, featuring turn-based combat and rich storytelling.',
                'release_date' => '2023-08-03',
                'genre' => 'RPG',
                'publisher' => 'Larian Studios',
                'developer' => 'Larian Studios',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Starfield',
                'description' => 'A space-faring RPG where players explore the galaxy as a member of Constellation, seeking answers to humanity\'s greatest mystery.',
                'release_date' => '2023-09-06',
                'genre' => 'Action RPG',
                'publisher' => 'Bethesda Softworks',
                'developer' => 'Bethesda Game Studios',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Final Fantasy XVI',
                'description' => 'An action RPG set in the fantasy realm of Valisthea, where players control Clive Rosfield in his quest for revenge.',
                'release_date' => '2023-06-22',
                'genre' => 'Action RPG',
                'publisher' => 'Square Enix',
                'developer' => 'Square Enix',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Diablo IV',
                'description' => 'Return to the dark fantasy world of Sanctuary in this action RPG, facing off against Lilith and her demonic forces.',
                'release_date' => '2023-06-06',
                'genre' => 'Action RPG',
                'publisher' => 'Blizzard Entertainment',
                'developer' => 'Blizzard Entertainment',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Marvel\'s Spider-Man 2',
                'description' => 'Team up with Peter Parker and Miles Morales to face new threats in New York City, including the symbiote and Kraven the Hunter.',
                'release_date' => '2023-10-20',
                'genre' => 'Action-Adventure',
                'publisher' => 'Sony Interactive Entertainment',
                'developer' => 'Insomniac Games',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'The Legend of Zelda: Tears of the Kingdom',
                'description' => 'The sequel to Breath of the Wild takes Link\'s adventure to the skies above Hyrule with new abilities and mysteries.',
                'release_date' => '2023-05-12',
                'genre' => 'Action-Adventure',
                'publisher' => 'Nintendo',
                'developer' => 'Nintendo EPD',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Hogwarts Legacy',
                'description' => 'An open-world action RPG set in the 1800s wizarding world, where players attend Hogwarts and shape their own magical legacy.',
                'release_date' => '2023-02-10',
                'genre' => 'Action RPG',
                'publisher' => 'Warner Bros. Games',
                'developer' => 'Avalanche Software',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Street Fighter 6',
                'description' => 'The latest entry in the legendary fighting game series, featuring new mechanics and modes.',
                'release_date' => '2023-06-02',
                'genre' => 'Fighting',
                'publisher' => 'Capcom',
                'developer' => 'Capcom',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Resident Evil 4 Remake',
                'description' => 'A complete remake of the classic survival horror game, following Leon Kennedy\'s mission to rescue the president\'s daughter.',
                'release_date' => '2023-03-24',
                'genre' => 'Survival Horror',
                'publisher' => 'Capcom',
                'developer' => 'Capcom',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Call of Duty: Modern Warfare III',
                'description' => 'The latest installment in the Modern Warfare series, featuring both campaign and multiplayer modes.',
                'release_date' => '2023-11-10',
                'genre' => 'First-Person Shooter',
                'publisher' => 'Activision',
                'developer' => 'Sledgehammer Games',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Mortal Kombat 1',
                'description' => 'A reboot of the iconic fighting game series, featuring a new universe created by Fire God Liu Kang.',
                'release_date' => '2023-09-19',
                'genre' => 'Fighting',
                'publisher' => 'Warner Bros. Games',
                'developer' => 'NetherRealm Studios',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Alan Wake 2',
                'description' => 'The long-awaited sequel to the psychological thriller, following both Alan Wake and FBI agent Saga Anderson.',
                'release_date' => '2023-10-27',
                'genre' => 'Survival Horror',
                'publisher' => 'Epic Games Publishing',
                'developer' => 'Remedy Entertainment',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Monster Hunter: Rise',
                'description' => 'Hunt solo or in a party with friends to earn rewards that you can use to craft a huge variety of weapons and armor.',
                'release_date' => '2021-03-26',
                'genre' => 'Action RPG',
                'publisher' => 'Capcom',
                'developer' => 'Capcom',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Persona 5 Royal',
                'description' => 'Don the mask of Joker and join the Phantom Thieves of Hearts as they stage grand heists.',
                'release_date' => '2022-10-21',
                'genre' => 'JRPG',
                'publisher' => 'Atlus',
                'developer' => 'P-Studio',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Hades',
                'description' => 'Defy the god of the dead as you hack and slash out of the Underworld in this rogue-like dungeon crawler.',
                'release_date' => '2020-09-17',
                'genre' => 'Roguelike',
                'publisher' => 'Supergiant Games',
                'developer' => 'Supergiant Games',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Horizon Forbidden West',
                'description' => 'Join Aloy as she braves the Forbidden West, a deadly frontier that conceals mysterious new threats.',
                'release_date' => '2022-02-18',
                'genre' => 'Action RPG',
                'publisher' => 'Sony Interactive Entertainment',
                'developer' => 'Guerrilla Games',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Demon\'s Souls',
                'description' => 'From PlayStation Studios and Bluepoint Games comes a remake of the PlayStation classic.',
                'release_date' => '2020-11-12',
                'genre' => 'Action RPG',
                'publisher' => 'Sony Interactive Entertainment',
                'developer' => 'Bluepoint Games',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'It Takes Two',
                'description' => 'Embark on the craziest journey of your life in a genre-bending platform adventure created purely for co-op.',
                'release_date' => '2021-03-26',
                'genre' => 'Platform Adventure',
                'publisher' => 'Electronic Arts',
                'developer' => 'Hazelight Studios',
                'is_single_player' => false,
                'is_multi_player' => true
            ],
            [
                'title' => 'Deathloop',
                'description' => 'Fight to break a timeloop trapped on the island of Blackreef.',
                'release_date' => '2021-09-14',
                'genre' => 'First-Person Shooter',
                'publisher' => 'Bethesda Softworks',
                'developer' => 'Arkane Studios',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Ghostwire: Tokyo',
                'description' => 'Explore a unique vision of Tokyo twisted by supernatural elements as you fight to solve the mystery of the mass disappearance.',
                'release_date' => '2022-03-25',
                'genre' => 'Action-Adventure',
                'publisher' => 'Bethesda Softworks',
                'developer' => 'Tango Gameworks',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Returnal',
                'description' => 'Break the cycle of chaos on an always-changing alien planet. Fight to survive in this third-person roguelike shooter.',
                'release_date' => '2021-04-30',
                'genre' => 'Third-Person Shooter',
                'publisher' => 'Sony Interactive Entertainment',
                'developer' => 'Housemarque',
                'is_single_player' => true,
                'is_multi_player' => true
            ],
            [
                'title' => 'Tales of Arise',
                'description' => 'Challenge the fate that binds you in a new Tales of adventure. Fight to free a world where oppression is the norm.',
                'release_date' => '2021-09-10',
                'genre' => 'JRPG',
                'publisher' => 'Bandai Namco',
                'developer' => 'Bandai Namco',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Dead Space Remake',
                'description' => 'The sci-fi survival horror classic returns, completely rebuilt to offer a deeper and more immersive experience.',
                'release_date' => '2023-01-27',
                'genre' => 'Survival Horror',
                'publisher' => 'Electronic Arts',
                'developer' => 'Motive Studio',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Final Fantasy VII Remake',
                'description' => 'A reimagining of the iconic original game that redefined the RPG genre.',
                'release_date' => '2020-04-10',
                'genre' => 'Action RPG',
                'publisher' => 'Square Enix',
                'developer' => 'Square Enix',
                'is_single_player' => true,
                'is_multi_player' => false
            ],
            [
                'title' => 'Assassin\'s Creed Valhalla',
                'description' => 'Become Eivor, a Viking raider, and lead your clan to glory in medieval England.',
                'release_date' => '2020-11-10',
                'genre' => 'Action RPG',
                'publisher' => 'Ubisoft',
                'developer' => 'Ubisoft Montreal',
                'is_single_player' => true,
                'is_multi_player' => false
            ]
        ];

        foreach ($games as $game) {
            Game::firstOrCreate(
                [
                    'title' => $game['title'],
                    'user_id' => $users->random()->id
                ],
                $game
            );
        }
    }
}
