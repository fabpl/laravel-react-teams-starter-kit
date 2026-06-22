<?php

declare(strict_types=1);

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use App\Notifications\Teams\TeamInvitation as TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

test('a team owner can invite a member through the team edit page', function (): void {
    Notification::fake();

    $owner = User::factory()->create();
    $team = Team::factory()->create(['name' => 'Engineering']);
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner);

    $page = visit(route('teams.edit', $team, absolute: false));

    $page->assertSee('Engineering')
        ->click('@invite-member-button')
        ->fill('@invite-email', 'colleague@example.com')
        ->click('@invite-submit')
        ->assertSee('colleague@example.com')
        ->assertNoJavascriptErrors();

    $this->assertDatabaseHas('team_invitations', [
        'team_id' => $team->id,
        'email' => 'colleague@example.com',
    ]);

    Notification::assertSentOnDemand(TeamInvitationNotification::class);
});
