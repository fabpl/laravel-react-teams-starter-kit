<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

test('a user can create a team from the teams settings page', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $page = visit('/settings/teams');

    $page->assertSee('Teams')
        ->click('@teams-new-team-button')
        ->fill('@create-team-name', 'Engineering')
        ->click('@create-team-submit')
        ->assertSee('Engineering')
        ->assertNoJavascriptErrors();

    expect(Team::where('name', 'Engineering')->exists())->toBeTrue();
});

test('the create team modal rejects a reserved team name', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $page = visit('/settings/teams');

    $page->click('@teams-new-team-button')
        ->fill('@create-team-name', 'settings')
        ->click('@create-team-submit')
        ->assertSee('This team name is reserved and cannot be used.')
        ->assertNoJavascriptErrors();

    expect(Team::where('name', 'settings')->exists())->toBeFalse();
});
