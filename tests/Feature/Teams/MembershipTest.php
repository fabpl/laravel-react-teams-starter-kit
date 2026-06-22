<?php

declare(strict_types=1);

use App\Enums\TeamRole;
use App\Models\Membership;
use App\Models\Team;
use App\Models\User;

test('a membership belongs to a team and a user', function (): void {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $membership = $team->memberships()->where('user_id', $user->id)->firstOrFail();

    expect($membership)->toBeInstanceOf(Membership::class)
        ->and($membership->team)->toBeInstanceOf(Team::class)
        ->and($membership->team->is($team))->toBeTrue()
        ->and($membership->user)->toBeInstanceOf(User::class)
        ->and($membership->user->is($user))->toBeTrue()
        ->and($membership->role)->toBe(TeamRole::Owner);
});
