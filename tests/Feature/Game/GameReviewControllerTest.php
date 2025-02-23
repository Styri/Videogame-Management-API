<?php

use App\Models\Game;
use App\Models\User;
use App\Models\Role;
use App\Models\GameReview;

beforeEach(function () {
    $this->userRole = Role::factory()->create(['name' => 'Regular User']);

    $this->user = User::factory()->create(['role_id' => $this->userRole->id]);
    $this->anotherUser = User::factory()->create(['role_id' => $this->userRole->id]);

    $this->game = Game::factory()->create(['user_id' => $this->user->id]);

    $this->token = $this->user->createToken('auth_token')->plainTextToken;
    $this->anotherToken = $this->anotherUser->createToken('auth_token')->plainTextToken;
});

test('unauthenticated user cannot list reviews', function () {
    $response = $this->getJson("/api/games/{$this->game->id}/reviews");

    $response->assertUnauthorized();
});

test('can list game reviews with pagination', function () {
    GameReview::factory()
        ->count(15)
        ->for($this->game)
        ->create([
            'user_id' => $this->user->id
        ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/games/{$this->game->id}/reviews");

    $response->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'rating',
                    'review',
                    'user' => [
                        'id',
                        'name'
                    ]
                ]
            ],
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

test('returns 404 for reviews of non-existent game', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/games/999999/reviews");

    $response->assertNotFound();
});

test('can get empty reviews for game without reviews', function () {
    $game = Game::factory()->create();

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/games/{$game->id}/reviews");

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

test('can filter reviews by user_id', function () {
    GameReview::factory()
        ->for($this->game)
        ->for($this->user)
        ->create([
            'review' => 'First review'
        ]);

    GameReview::factory()
        ->for($this->game)
        ->for($this->anotherUser)
        ->create([
            'review' => 'Second review'
        ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/games/{$this->game->id}/reviews?user_id={$this->user->id}");

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.review', 'First review');
});

test('can sort reviews by rating', function () {
    GameReview::factory()
        ->for($this->game)
        ->for($this->user)
        ->create([
            'rating' => 5
        ]);

    GameReview::factory()
        ->for($this->game)
        ->for($this->anotherUser)
        ->create([
            'rating' => 10
        ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/games/{$this->game->id}/reviews?sort_by=rating&sort=desc");

    $response->assertOk()
        ->assertJsonPath('data.0.rating', 10);
});

test('authenticated user can create review', function () {
    $reviewData = [
        'rating' => 8,
        'review' => 'This is a great game!'
    ];

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/games/{$this->game->id}/reviews", $reviewData);

    $response->assertCreated()
        ->assertJson([
            'rating' => 8,
            'review' => 'This is a great game!',
            'user_id' => $this->user->id,
            'game_id' => $this->game->id
        ]);
});

test('rating must be between 1 and 10', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/games/{$this->game->id}/reviews", [
            'rating' => 11,
            'review' => 'This is a great game!'
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['rating']);
});

test('review cannot exceed 1500 characters', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/games/{$this->game->id}/reviews", [
            'rating' => 8,
            'review' => str_repeat('a', 1501)
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['review']);
});

test('rating and review are required', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/games/{$this->game->id}/reviews", []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['rating', 'review']);
});

afterEach(function () {
    GameReview::query()->delete();
    Game::query()->delete();
    User::query()->delete();
    Role::query()->delete();
});