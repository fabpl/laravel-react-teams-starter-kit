<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to login when visiting products', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this->get(route('products.index', ['current_team' => $team->slug]));

    $response->assertRedirect(route('login'));
});

test('team members can view products page', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->get(route('products.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->component('products/index')
        ->has('products'),
    );
});

test('products page returns configured products', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.products' => [
        [
            'id' => 'extra_storage',
            'name' => 'Extra Storage Pack',
            'description' => 'Add 50 GB of storage.',
            'price' => 499,
            'stripe_price_id' => 'price_storage',
        ],
    ]]);

    $response = $this
        ->actingAs($user)
        ->get(route('products.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->component('products/index')
        ->has('products', 1)
        ->where('products.0.id', 'extra_storage')
        ->where('products.0.name', 'Extra Storage Pack')
        ->where('products.0.price', 499),
    );
});

test('non-members cannot view products page', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($otherUser)
        ->get(route('products.index', ['current_team' => $team->slug]));

    $response->assertForbidden();
});

test('guests are redirected to login when accessing product checkout', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this->get(
        route('products.checkout', ['current_team' => $team->slug, 'priceId' => 'price_storage'])
    );

    $response->assertRedirect(route('login'));
});

test('non-members cannot access product checkout', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.products' => [
        ['id' => 'extra_storage', 'name' => 'Extra Storage Pack', 'stripe_price_id' => 'price_storage', 'price' => 499],
    ]]);

    $response = $this
        ->actingAs($otherUser)
        ->get(route('products.checkout', ['current_team' => $team->slug, 'priceId' => 'price_storage']));

    $response->assertForbidden();
});

test('product checkout returns 404 for unknown price', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.products' => []]);

    $response = $this
        ->actingAs($user)
        ->get(route('products.checkout', ['current_team' => $team->slug, 'priceId' => 'price_unknown']));

    $response->assertNotFound();
});
