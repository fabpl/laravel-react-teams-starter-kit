<?php

declare(strict_types=1);

use App\Models\User;

test('subscriptions page displays available plans', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $team = $user->personalTeamOrFail();

    $page = visit("/{$team->slug}/subscriptions");

    $page->assertSee('Starter')
        ->assertSee('Pro')
        ->assertNoJavascriptErrors();
});

test('products page displays available products', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $team = $user->personalTeamOrFail();

    $page = visit("/{$team->slug}/products");

    $page->assertSee('Extra Storage Pack')
        ->assertSee('Priority Onboarding')
        ->assertNoJavascriptErrors();
});

test('subscriptions page subscribe buttons link to checkout', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $team = $user->personalTeamOrFail();

    $page = visit("/{$team->slug}/subscriptions");

    $page->assertSee('Subscribe')
        ->assertNoJavascriptErrors();
});

test('products page buy buttons link to checkout', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $team = $user->personalTeamOrFail();

    $page = visit("/{$team->slug}/products");

    $page->assertSee('Buy')
        ->assertNoJavascriptErrors();
});
