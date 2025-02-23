<?php

use App\Models\Game;
use App\Models\User;
use App\Models\Role;

beforeEach(function () {

    $this->userRole = Role::factory()->create(['name' => 'Regular User']);

    $this->user = User::factory()->create(['role_id' => $this->userRole->id]);
    $this->token = $this->user->createToken('auth_token')->plainTextToken;

    $this->otherUser = User::factory()->create(['role_id' => $this->userRole->id]);
});

test('unauthenticated user cannot access my-games', function () {
    $response = $this->getJson('/api/my-games');

    $response->assertUnauthorized();
});

test('user can see their own games with pagination', function () {
    Game::factory()
        ->count(15)
        ->for($this->user)
        ->create();

    Game::factory()
        ->count(5)
        ->for($this->otherUser)
        ->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games');

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

    expect($response->json('total'))->toBe(15);
});

test('can filter own games by genre', function () {
    Game::factory()
        ->for($this->user)
        ->create(['genre' => 'RPG']);

    Game::factory()
        ->for($this->user)
        ->create(['genre' => 'FPS']);

    Game::factory()
        ->for($this->otherUser)
        ->create(['genre' => 'RPG']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games?genre=RPG');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.genre', 'RPG');
});

test('can search own games by title', function () {
    Game::factory()
        ->for($this->user)
        ->create(['title' => 'The Last of Us']);

    Game::factory()
        ->for($this->user)
        ->create(['title' => 'God of War']);

    Game::factory()
        ->for($this->otherUser)
        ->create(['title' => 'The Last of Us 2']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games?title=Last');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'The Last of Us');
});

test('can filter own games by developer', function () {
    Game::factory()
        ->for($this->user)
        ->create(['developer' => 'Naughty Dog']);

    Game::factory()
        ->for($this->user)
        ->create(['developer' => 'Santa Monica']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games?developer=Naughty');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.developer', 'Naughty Dog');
});

test('can filter own games by player mode', function () {
    Game::factory()
        ->for($this->user)
        ->create([
            'is_multi_player' => true,
            'is_single_player' => false
        ]);

    Game::factory()
        ->for($this->user)
        ->create([
            'is_multi_player' => false,
            'is_single_player' => true
        ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games?is_multi_player=1');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.is_multi_player', true);
});

test('can sort own games', function () {
    $oldGame = Game::factory()
        ->for($this->user)
        ->create(['release_date' => '2020-01-01']);

    $newGame = Game::factory()
        ->for($this->user)
        ->create(['release_date' => '2023-01-01']);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games?sort_by=release_date&sort=desc');

    $response->assertOk()
        ->assertJsonPath('data.0.id', $newGame->id)
        ->assertJsonPath('data.1.id', $oldGame->id);
});

test('invalid sort parameters are rejected', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/my-games?sort_by=invalid&sort=invalid');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['sort_by', 'sort']);
});

afterEach(function () {
    Game::query()->delete();
    User::query()->delete();
    Role::query()->delete();
});
