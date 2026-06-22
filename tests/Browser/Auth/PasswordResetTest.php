<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('the login screen links to the password reset request page', function (): void {
    visit('/login')
        ->assertSee('Forgot password?')
        ->click('Forgot password?')
        ->assertUrlIs(url('/forgot-password'))
        ->assertSee('Email password reset link')
        ->assertNoJavascriptErrors();
});

test('a user can request a password reset link', function (): void {
    Notification::fake();

    $user = User::factory()->create(['email' => 'jane@example.com']);

    visit('/forgot-password')
        ->fill('email', 'jane@example.com')
        ->click('@email-password-reset-link-button')
        ->assertSee('We have emailed your password reset link.')
        ->assertNoJavascriptErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});
