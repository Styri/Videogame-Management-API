<?php

use App\Models\Game;
use App\Models\User;
use App\Models\Role;

beforeEach(function () {
    $this->adminRole = Role::factory()->create(['name' => 'Admin']);
    $this->userRole = Role::factory()->create(['name' => 'Regular User']);

    $this->admin = User::factory()->create(['role_id' => $this->adminRole->id]);
    $this->user = User::factory()->create(['role_id' => $this->userRole->id]);

    $this->adminToken = $this->admin->createToken('auth_token')->plainTextToken;
    $this->userToken = $this->user->createToken('auth_token')->plainTextToken;
});

test('unauthenticated user cannot list games', function () {
    $response = $this->getJson('/api/games');

    $response->assertUnauthorized();
});

test('authenticated user can list games with pagination', function () {
    Game::factory()->count(15)->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games');

    $response->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
});

test('authenticated user can view specific game', function () {
    $game = Game::factory()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson("/api/games/{$game->id}");

    $response->assertOk()
        ->assertJson($game->toArray());
});

test('unauthenticated user cannot view specific game', function () {
    $game = Game::factory()->create();

    $response = $this->getJson("/api/games/{$game->id}");

    $response->assertUnauthorized();
});

test('returns 404 for non-existent game', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson("/api/games/999999");

    $response->assertNotFound();
});

test('can filter games by genre', function () {
    Game::factory()->create(['genre' => 'RPG']);
    Game::factory()->create(['genre' => 'FPS']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?genre=RPG');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.genre', 'RPG');
});

test('can search games by title', function () {
    Game::factory()->create(['title' => 'The Last of Us']);
    Game::factory()->create(['title' => 'God of War']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?title=Last');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'The Last of Us');
});

test('can sort games', function () {
    $oldGame = Game::factory()->create(['release_date' => '2020-01-01']);
    $newGame = Game::factory()->create(['release_date' => '2023-01-01']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?sort_by=release_date&sort=desc');

    $response->assertOk()
        ->assertJsonPath('data.0.id', $newGame->id);
});

test('authenticated user can create game', function () {
    $gameData = [
        'title' => 'New Game',
        'description' => 'Game description',
        'release_date' => '2023-01-01',
        'genre' => 'RPG',
        'publisher' => 'Publisher Co',
        'developer' => 'Developer Inc',
        'is_multi_player' => true,
        'is_single_player' => true
    ];

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->postJson('/api/games', $gameData);

    $response->assertCreated()
        ->assertJsonPath('title', $gameData['title'])
        ->assertJsonPath('user_id', $this->user->id);
});

test('cannot create game with duplicate title', function () {
    Game::factory()->create(['title' => 'Existing Game']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->postJson('/api/games', [
            'title' => 'Existing Game',
            'description' => 'Game description',
            'release_date' => '2023-01-01',
            'genre' => 'RPG',
            'publisher' => 'Publisher Co',
            'developer' => 'Developer Inc'
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

test('user can update their own game', function () {
    $game = Game::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->putJson("/api/games/{$game->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'release_date' => '2023-01-01',
            'genre' => 'RPG',
            'publisher' => 'Publisher Co',
            'developer' => 'Developer Inc'
        ]);

    $response->assertOk()
        ->assertJsonPath('title', 'Updated Title');
});

test('user cannot update others games', function () {
    $otherUser = User::factory()->create(['role_id' => $this->userRole->id]);
    $game = Game::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->putJson("/api/games/{$game->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'release_date' => '2023-01-01',
            'genre' => 'RPG',
            'publisher' => 'Publisher Co',
            'developer' => 'Developer Inc'
        ]);

    $response->assertForbidden();
});

test('admin can update any game', function () {
    $game = Game::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
        ->putJson("/api/games/{$game->id}", [
            'title' => 'Admin Updated',
            'description' => 'Updated description',
            'release_date' => '2023-01-01',
            'genre' => 'RPG',
            'publisher' => 'Publisher Co',
            'developer' => 'Developer Inc'
        ]);

    $response->assertOk()
        ->assertJsonPath('title', 'Admin Updated');
});

test('user can delete their own game', function () {
    $game = Game::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->deleteJson("/api/games/{$game->id}");

    $response->assertNoContent();
    $this->assertModelMissing($game);
});

test('user cannot delete others games', function () {
    $otherUser = User::factory()->create(['role_id' => $this->userRole->id]);
    $game = Game::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->deleteJson("/api/games/{$game->id}");

    $response->assertForbidden();
    $this->assertModelExists($game);
});

test('admin can delete any game', function () {
    $game = Game::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
        ->deleteJson("/api/games/{$game->id}");

    $response->assertNoContent();
    $this->assertModelMissing($game);
});

test('pagination links are correct and functional', function () {
    Game::factory()
        ->count(30)
        ->create(['title' => fn() => 'Game ' . fake()->unique()->numberBetween(1, 30)]);

    $firstPageResponse = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games');

    $firstPageResponse->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'last_page_url',
            'next_page_url',
            'prev_page_url'
        ])
        ->assertJson([
            'current_page' => 1,
            'last_page' => 3,
            'per_page' => 10,
            'total' => 30,
            'prev_page_url' => null
        ]);

    $nextPageUrl = $firstPageResponse->json('next_page_url');
    $secondPageResponse = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson(parse_url($nextPageUrl, PHP_URL_PATH) . '?' . parse_url($nextPageUrl, PHP_URL_QUERY));

    $secondPageResponse->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJson([
            'current_page' => 2,
            'last_page' => 3,
            'per_page' => 10,
            'total' => 30
        ]);

    $prevPageUrl = $secondPageResponse->json('prev_page_url');
    $backToFirstResponse = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson(parse_url($prevPageUrl, PHP_URL_PATH) . '?' . parse_url($prevPageUrl, PHP_URL_QUERY));

    $backToFirstResponse->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJson([
            'current_page' => 1
        ]);

    $lastPageUrl = $firstPageResponse->json('last_page_url');
    $lastPageResponse = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson(parse_url($lastPageUrl, PHP_URL_PATH) . '?' . parse_url($lastPageUrl, PHP_URL_QUERY));

    $lastPageResponse->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJson([
            'current_page' => 3,
            'next_page_url' => null
        ]);
});

test('pagination maintains query parameters across pages', function () {
    Game::factory()
        ->count(25)
        ->create(['genre' => 'RPG', 'title' => fn() => 'Game ' . fake()->unique()->numberBetween(1, 25)]);

    Game::factory()
        ->count(5)
        ->create(['genre' => 'FPS']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?genre=RPG&sort_by=title&sort=asc');

    $response->assertOk();

    $firstPageGames = collect($response->json('data'));
    expect($firstPageGames->every(fn($game) => $game['genre'] === 'RPG'))->toBeTrue();

    $secondPageResponse = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?genre=RPG&sort_by=title&sort=asc&page=2');

    $secondPageResponse->assertOk();

    $secondPageGames = collect($secondPageResponse->json('data'));
    expect($secondPageGames->every(fn($game) => $game['genre'] === 'RPG'))->toBeTrue();
});

test('pagination handles empty pages correctly', function () {
    Game::factory()->count(5)->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?page=2');

    $response->assertOk()
        ->assertJsonCount(0, 'data')
        ->assertJsonStructure([
            'current_page',
            'data',
            'total'
        ]);

    expect($response->json('data'))->toBeEmpty()
        ->and($response->json('total'))->toBe(5);
});

test('pagination handles invalid page numbers', function () {
    Game::factory()->count(15)->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?page=-1');

    $response->assertOk()
        ->assertJson([
            'current_page' => 1
        ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->getJson('/api/games?page=abc');

    $response->assertOk()
        ->assertJson([
            'current_page' => 1
        ]);
});

afterEach(function () {
    Game::query()->delete();
    User::query()->delete();
    Role::query()->delete();
});
