<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;
use Override;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    #[Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'currentTeam' => fn () => $user?->currentTeam ? $user->toUserTeam($user->currentTeam) : null,
            'teams' => fn () => $user?->toUserTeams(includeCurrent: true) ?? [],
            'locale' => app()->getLocale(),
            'supportedLocales' => config('app.supported_locales', ['en']),
            'translations' => Inertia::once(fn (): array => $this->loadTranslations()),
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function loadTranslations(): array
    {
        $locale = app()->getLocale();
        $path = lang_path($locale);

        if (! is_dir($path)) {
            return [];
        }

        $translations = [];

        foreach (glob($path.'/*.php') ?: [] as $file) {
            $namespace = basename($file, '.php');
            $groups = require $file;

            if (! is_array($groups)) {
                continue;
            }

            $translations[$namespace] = $groups;
        }

        return $translations;
    }
}
