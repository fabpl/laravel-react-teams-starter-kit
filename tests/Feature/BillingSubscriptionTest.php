<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to login when visiting subscriptions', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertRedirect(route('login'));
});

test('team members can view subscriptions page', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->component('subscriptions/index')
        ->has('plans')
        ->where('currentSubscription', null),
    );
});

test('subscriptions page returns configured plans', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.plans' => [
        [
            'id' => 'starter',
            'name' => 'Starter',
            'description' => 'Starter plan',
            'price' => 990,
            'interval' => 'month',
            'stripe_price_id' => 'price_starter',
            'features' => ['Feature 1'],
        ],
    ]]);

    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->component('subscriptions/index')
        ->has('plans', 1)
        ->where('plans.0.id', 'starter')
        ->where('plans.0.name', 'Starter')
        ->where('plans.0.isSubscribed', false),
    );
});

test('subscriptions page marks plan as subscribed when team has active subscription', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.plans' => [
        [
            'id' => 'starter',
            'name' => 'Starter',
            'description' => 'Starter plan',
            'price' => 990,
            'interval' => 'month',
            'stripe_price_id' => 'price_starter',
            'features' => ['Feature 1'],
        ],
    ]]);

    $subscription = $team->subscriptions()->create([
        'type' => 'default',
        'stripe_id' => 'sub_test_starter',
        'stripe_status' => 'active',
        'stripe_price' => 'price_starter',
        'quantity' => 1,
    ]);
    $subscription->items()->create([
        'stripe_id' => 'si_test_starter',
        'stripe_product' => 'prod_test',
        'stripe_price' => 'price_starter',
        'quantity' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->component('subscriptions/index')
        ->where('plans.0.isSubscribed', true),
    );
});

test('subscriptions page returns current subscription data when team is subscribed', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $team->subscriptions()->create([
        'type' => 'default',
        'stripe_id' => 'sub_test_active',
        'stripe_status' => 'active',
        'stripe_price' => 'price_pro',
        'quantity' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->component('subscriptions/index')
        ->whereType('currentSubscription', 'array')
        ->where('currentSubscription.type', 'default')
        ->where('currentSubscription.stripeStatus', 'active')
        ->where('currentSubscription.stripePrice', 'price_pro')
        ->where('currentSubscription.onGracePeriod', false)
        ->where('currentSubscription.cancelled', false)
        ->where('currentSubscription.trialEndsAt', null)
        ->where('currentSubscription.endsAt', null),
    );
});

test('subscriptions page returns cancelled subscription on grace period', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $endsAt = now()->addDays(7);

    $team->subscriptions()->create([
        'type' => 'default',
        'stripe_id' => 'sub_test_cancelled',
        'stripe_status' => 'active',
        'stripe_price' => 'price_pro',
        'quantity' => 1,
        'ends_at' => $endsAt,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page): Assert => $page
        ->where('currentSubscription.onGracePeriod', true)
        ->where('currentSubscription.cancelled', true)
        ->where('currentSubscription.endsAt', $endsAt->toIso8601String()),
    );
});

test('non-members cannot view subscriptions page', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($otherUser)
        ->get(route('subscriptions.index', ['current_team' => $team->slug]));

    $response->assertForbidden();
});

test('guests are redirected to login when accessing subscription checkout', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this->get(
        route('subscriptions.checkout', ['current_team' => $team->slug, 'priceId' => 'price_starter'])
    );

    $response->assertRedirect(route('login'));
});

test('non-members cannot access subscription checkout', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.plans' => [
        ['id' => 'starter', 'name' => 'Starter', 'stripe_price_id' => 'price_starter', 'price' => 990, 'interval' => 'month', 'features' => []],
    ]]);

    $response = $this
        ->actingAs($otherUser)
        ->get(route('subscriptions.checkout', ['current_team' => $team->slug, 'priceId' => 'price_starter']));

    $response->assertForbidden();
});

test('subscription checkout returns 404 for unknown price', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    config(['billing.plans' => []]);

    $response = $this
        ->actingAs($user)
        ->get(route('subscriptions.checkout', ['current_team' => $team->slug, 'priceId' => 'price_unknown']));

    $response->assertNotFound();
});

test('guests are redirected to login when accessing subscription portal', function (): void {
    $user = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this->get(route('subscriptions.portal', ['current_team' => $team->slug]));

    $response->assertRedirect(route('login'));
});

test('non-members cannot access subscription portal', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($otherUser)
        ->get(route('subscriptions.portal', ['current_team' => $team->slug]));

    $response->assertForbidden();
});
