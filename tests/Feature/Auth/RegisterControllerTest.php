<?php

use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

beforeEach(function () {

    $this->regularRole = Role::factory()->create(['name' => 'Regular User']);
});

test('user can register with valid data', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'access_token',
            'token_type'
        ])
        ->assertJson([
            'token_type' => 'Bearer'
        ]);

    expect(User::count())->toBe(1);
    $user = User::first();
    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and($user->role_id)->toBe($this->regularRole->id)
        ->and(PersonalAccessToken::count())->toBe(1)
        ->and(PersonalAccessToken::first()->name)->toBe('auth_token');

});

test('user cannot register with existing email', function () {

    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('password must be confirmed', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different-password'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

test('password must be at least 8 characters', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'short',
        'password_confirmation' => 'short'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

test('password cannot exceed 40 characters', function () {
    $longPassword = str_repeat('a', 41);

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => $longPassword,
        'password_confirmation' => $longPassword
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

test('name cannot exceed 255 characters', function () {
    $response = $this->postJson('/api/register', [
        'name' => str_repeat('a', 256),
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('email must be valid format', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'not-an-email',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('email cannot exceed 254 characters', function () {
    $longEmail = str_repeat('a', 246) . '@test.com';

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => $longEmail,
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('required fields cannot be empty', function () {
    $response = $this->postJson('/api/register', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'password'
        ]);
});