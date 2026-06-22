<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('a user can request a password reset link from the login screen', function (): void {
    Notification::fake();

    $user = User::factory()->create(['email' => 'jane@example.com']);

    $page = visit('/login');

    $page->click('Forgot password?')
        ->fill('email', 'jane@example.com')
        ->click('@email-password-reset-link-button')
        ->assertSee('We have emailed your password reset link.')
        ->assertNoJavascriptErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});
