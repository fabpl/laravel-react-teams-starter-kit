<?php

declare(strict_types=1);

use App\Enums\TeamPermission;
use App\Enums\TeamRole;

test('label returns an ucfirst version of the value', function (): void {
    expect(TeamRole::Owner->label())->toBe('Owner')
        ->and(TeamRole::Admin->label())->toBe('Admin')
        ->and(TeamRole::Member->label())->toBe('Member');
});

test('owner has every permission', function (): void {
    expect(TeamRole::Owner->permissions())->toBe(TeamPermission::cases());

    foreach (TeamPermission::cases() as $permission) {
        expect(TeamRole::Owner->hasPermission($permission))->toBeTrue();
    }
});

test('member has no permissions', function (): void {
    expect(TeamRole::Member->permissions())->toBe([])
        ->and(TeamRole::Member->hasPermission(TeamPermission::UpdateTeam))->toBeFalse();
});

test('admin has a limited set of permissions', function (): void {
    expect(TeamRole::Admin->hasPermission(TeamPermission::UpdateTeam))->toBeTrue()
        ->and(TeamRole::Admin->hasPermission(TeamPermission::DeleteTeam))->toBeFalse();
});

test('role levels express the privilege hierarchy', function (): void {
    expect(TeamRole::Owner->level())->toBeGreaterThan(TeamRole::Admin->level())
        ->and(TeamRole::Admin->level())->toBeGreaterThan(TeamRole::Member->level());
});

test('isAtLeast compares role privilege levels', function (): void {
    expect(TeamRole::Owner->isAtLeast(TeamRole::Admin))->toBeTrue()
        ->and(TeamRole::Admin->isAtLeast(TeamRole::Admin))->toBeTrue()
        ->and(TeamRole::Member->isAtLeast(TeamRole::Admin))->toBeFalse();
});

test('assignable roles exclude the owner role', function (): void {
    $assignable = TeamRole::assignable();

    expect($assignable)->toBe([
        ['value' => 'admin', 'label' => 'Admin'],
        ['value' => 'member', 'label' => 'Member'],
    ]);
});
