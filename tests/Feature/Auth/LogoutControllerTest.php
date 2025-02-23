<?php

use App\Models\User;
use App\Models\Role;
use Laravel\Sanctum\PersonalAccessToken;

beforeEach(function () {
    $role = Role::factory()->create(['name' => 'Regular User']);

    $this->user = User::factory()->create([
        'role_id' => $role->id
    ]);

    $this->token = $this->user->createToken('auth_token')->plainTextToken;
});

test('authenticated user can logout', function () {
    expect(PersonalAccessToken::count())->toBe(1);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/logout');

    $response->assertOk()
        ->assertJson([
            'message' => 'Logged out successfully'
        ]);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('unauthenticated user cannot access logout endpoint', function () {
    $response = $this->postJson('/api/logout');

    $response->assertUnauthorized();
});

test('user with invalid token cannot logout', function () {
    $response = $this->withHeader('Authorization', 'Bearer invalid_token')
        ->postJson('/api/logout');

    $response->assertUnauthorized();
});

test('logout only deletes current token', function () {
    $secondToken = $this->user->createToken('second_token')->plainTextToken;
    expect(PersonalAccessToken::count())->toBe(2);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/logout');

    $response->assertOk();

    expect(PersonalAccessToken::count())->toBe(1)
        ->and(PersonalAccessToken::first()->name)->toBe('second_token');
});

afterEach(function () {
    PersonalAccessToken::query()->delete();
});