<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

beforeEach(function () {
    $role = Role::factory()->create(['name' => 'Regular User']);

    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'role_id' => $role->id
    ]);
});

afterEach(function () {
    PersonalAccessToken::query()->delete();
});

test('user can login with valid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123'
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'access_token',
            'token_type'
        ])
        ->assertJson([
            'token_type' => 'Bearer'
        ]);

    expect(PersonalAccessToken::count())->toBe(1);
    expect(PersonalAccessToken::first()->name)->toBe('auth_token');
});

test('returned token can access protected routes', function () {
    $loginResponse = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123'
    ]);

    $token = $loginResponse->json('access_token');

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/my-games');

    $response->assertOk();
});

test('user cannot login with invalid email', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'wrong@example.com',
        'password' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email' => 'The provided credentials are incorrect.'
        ]);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('user cannot login with invalid password', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email' => 'The provided credentials are incorrect.'
        ]);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('email field is required', function () {
    $response = $this->postJson('/api/login', [
        'password' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('password field is required', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('email must be valid format', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'not-an-email',
        'password' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('email cannot exceed 254 characters', function () {
    $longEmail = str_repeat('a', 245) . '@test.com';

    $response = $this->postJson('/api/login', [
        'email' => $longEmail,
        'password' => 'password123'
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    expect(PersonalAccessToken::count())->toBe(0);
});

test('api rejects malformed tokens', function () {
    $response = $this->withHeader('Authorization', 'Bearer malformed-token')
        ->getJson('/api/games');

    $response->assertUnauthorized();
});

test('shows all validation errors when no fields provided', function () {
    $response = $this->postJson('/api/login', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
            'password'
        ]);

    expect(PersonalAccessToken::count())->toBe(0);
});