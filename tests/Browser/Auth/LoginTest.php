<?php

declare(strict_types=1);

use App\Models\User;

test('a user can log in through the login form', function (): void {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
    ]);

    $team = $user->personalTeamOrFail();

    $page = visit('/login');

    $page->assertSee('Log in')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'password')
        ->click('@login-button')
        ->assertUrlIs(url("/{$team->slug}/dashboard"))
        ->assertNoJavascriptErrors();

    $this->assertAuthenticatedAs($user);
});

test('the login form shows a validation error for invalid credentials', function (): void {
    User::factory()->create([
        'email' => 'jane@example.com',
    ]);

    $page = visit('/login');

    $page->fill('email', 'jane@example.com')
        ->fill('password', 'wrong-password')
        ->click('@login-button')
        ->assertSee('These credentials do not match our records.')
        ->assertNoJavascriptErrors();

    $this->assertGuest();
});
