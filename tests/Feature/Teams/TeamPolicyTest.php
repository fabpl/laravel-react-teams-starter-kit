<?php

declare(strict_types=1);

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;

beforeEach(function (): void {
    $this->policy = new TeamPolicy;
});

function teamWithMember(TeamRole $role, bool $isPersonal = false): array
{
    $user = User::factory()->create();
    $team = Team::factory()->create(['is_personal' => $isPersonal]);
    $team->members()->attach($user, ['role' => $role->value]);

    return [$user, $team];
}

test('any user can view the team listing and create teams', function (): void {
    $user = User::factory()->create();

    expect($this->policy->viewAny($user))->toBeTrue()
        ->and($this->policy->create($user))->toBeTrue();
});

test('only members can view a team', function (): void {
    [$member, $team] = teamWithMember(TeamRole::Member);
    $stranger = User::factory()->create();

    expect($this->policy->view($member, $team))->toBeTrue()
        ->and($this->policy->view($stranger, $team))->toBeFalse();
});

test('owners can manage members and the team while members cannot', function (): void {
    [$owner, $team] = teamWithMember(TeamRole::Owner);
    $member = User::factory()->create();
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    expect($this->policy->update($owner, $team))->toBeTrue()
        ->and($this->policy->addMember($owner, $team))->toBeTrue()
        ->and($this->policy->updateMember($owner, $team))->toBeTrue()
        ->and($this->policy->removeMember($owner, $team))->toBeTrue()
        ->and($this->policy->inviteMember($owner, $team))->toBeTrue()
        ->and($this->policy->cancelInvitation($owner, $team))->toBeTrue()
        ->and($this->policy->delete($owner, $team))->toBeTrue();

    expect($this->policy->update($member, $team))->toBeFalse()
        ->and($this->policy->addMember($member, $team))->toBeFalse()
        ->and($this->policy->removeMember($member, $team))->toBeFalse();
});

test('a member can leave a non-personal team but the owner cannot', function (): void {
    [$owner, $team] = teamWithMember(TeamRole::Owner);
    $member = User::factory()->create();
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    expect($this->policy->leave($member, $team))->toBeTrue()
        ->and($this->policy->leave($owner, $team))->toBeFalse();
});

test('nobody can leave or delete a personal team', function (): void {
    [$owner, $team] = teamWithMember(TeamRole::Owner, isPersonal: true);

    expect($this->policy->leave($owner, $team))->toBeFalse()
        ->and($this->policy->delete($owner, $team))->toBeFalse();
});
