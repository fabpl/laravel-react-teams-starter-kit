<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;

test('default locale is english when no cookie is set', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('appearance.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('locale', 'en')
            ->has('supportedLocales')
            ->where('supportedLocales', ['en', 'fr'])
            ->has('translations'),
        );
});

test('locale is set to french when cookie is present', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', 'fr')
        ->get(route('appearance.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('locale', 'fr'),
        );
});

test('invalid locale cookie falls back to default', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', 'de')
        ->get(route('appearance.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('locale', 'en'),
        );
});

test('translations contain keys for english locale', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('appearance.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('translations.nav.dashboard', 'Dashboard')
            ->where('translations.settings.title', 'Settings')
            ->where('translations.common.language', 'Language'),
        );
});

test('translations contain french keys when locale is french', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', 'fr')
        ->get(route('appearance.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('translations.nav.dashboard', 'Tableau de bord')
            ->where('translations.settings.title', 'Paramètres')
            ->where('translations.common.language', 'Langue'),
        );
});

test('english and french translation files have matching keys', function (): void {
    $enKeys = collect(glob(lang_path('en/*.php')))
        ->flatMap(function (string $file): array {
            $namespace = basename($file, '.php');
            $groups = require $file;

            return collect(Arr::dot($groups))
                ->mapWithKeys(fn ($v, $k): array => ["{$namespace}.{$k}" => true])
                ->all();
        })
        ->keys()
        ->sort()
        ->values();

    $frKeys = collect(glob(lang_path('fr/*.php')))
        ->flatMap(function (string $file): array {
            $namespace = basename($file, '.php');
            $groups = require $file;

            return collect(Arr::dot($groups))
                ->mapWithKeys(fn ($v, $k): array => ["{$namespace}.{$k}" => true])
                ->all();
        })
        ->keys()
        ->sort()
        ->values();

    expect($enKeys->all())->toBe($frKeys->all());
});
