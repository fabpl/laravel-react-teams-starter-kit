<?php

declare(strict_types=1);

use App\Models\User;

test('guest pages render without javascript errors', function (): void {
    $pages = visit(['/', '/login', '/register']);

    $pages->assertNoSmoke();
});

test('authenticated pages render without javascript errors', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $team = $user->personalTeamOrFail();

    visit([
        "/{$team->slug}/dashboard",
        '/settings/profile',
        '/settings/teams',
        '/settings/appearance',
    ])->assertNoSmoke();
});
