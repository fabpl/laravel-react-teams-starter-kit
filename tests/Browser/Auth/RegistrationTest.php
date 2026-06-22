<?php

declare(strict_types=1);

use App\Models\User;

test('a new user can register through the registration form', function (): void {
    $page = visit('/register');

    $page->assertSee('Create account')
        ->fill('name', 'Jane Doe')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'password')
        ->fill('password_confirmation', 'password')
        ->click('@register-user-button')
        ->assertNoJavascriptErrors();

    $this->assertAuthenticated();

    expect(User::where('email', 'jane@example.com')->exists())->toBeTrue();
});

test('the registration form rejects an already registered email', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    $page = visit('/register');

    $page->fill('name', 'Jane Doe')
        ->fill('email', 'taken@example.com')
        ->fill('password', 'password')
        ->fill('password_confirmation', 'password')
        ->click('@register-user-button')
        ->assertSee('The email has already been taken.')
        ->assertNoJavascriptErrors();

    $this->assertGuest();
});
