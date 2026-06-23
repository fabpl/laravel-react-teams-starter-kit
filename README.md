# Laravel + React Teams Starter Kit

A hardened starter kit built on the official Laravel React stack, with first-class
**team management** and a quality toolchain wired in from day one. Everything below
is optimised for a fast, predictable **developer experience**: one command to set
up, one command to run, one command to verify.

## Stack

| Layer        | Tech                                                                 |
| ------------ | -------------------------------------------------------------------- |
| Backend      | Laravel 13 · PHP 8.4 · Fortify (auth, 2FA, passkeys)                  |
| Frontend     | React 19 · Inertia v3 · TypeScript (strict) · Tailwind CSS v4        |
| Routing glue | Wayfinder (typed routes/actions for the frontend)                    |
| Tooling      | Pint · Rector · Larastan (lvl 8) · ESLint · Prettier · Pest 4        |
| Tests        | Pest — Unit · Feature · **Browser (Playwright)**, ≥ 95% coverage     |

## Requirements

- PHP **8.4+** with Xdebug or PCOV (for coverage)
- Node **22+**
- Composer 2

## Quick start

```bash
composer setup        # install deps, create .env, key, migrate, build assets
npx playwright install chromium   # once, for browser tests
composer dev          # serve + queue + logs + Vite, all in one
```

`composer dev` runs the app server, queue worker, log tailer (Pail), and the Vite
dev server concurrently. Open the URL printed by `artisan serve`.

## The daily loop

| I want to…                          | Run                          |
| ----------------------------------- | ---------------------------- |
| Start everything (server + Vite)    | `composer dev`               |
| Auto-fix PHP style                  | `composer lint`              |
| Apply automated refactors           | `composer refactor`          |
| Auto-fix frontend style             | `npm run format && npm run lint` |
| Run the tests                       | `php artisan test`           |
| Run one test file/filter            | `php artisan test --filter=TeamPolicy` |
| Run the **full quality gate**       | `composer test`              |

### `composer test` — the quality gate

This is the single command that proves a change is ready. It runs, and fails on
the first problem:

1. **Pint** — PHP code style (`--test`)
2. **Rector** — automated refactor check (`--dry-run`)
3. **Larastan** — static analysis at **level 8**
4. **Pest** — the full suite (Unit + Feature + Browser) with a **≥ 95% coverage gate**

For the frontend, the matching checks are:

```bash
npm run lint:check     # ESLint
npm run format:check   # Prettier
npm run types:check    # tsc --noEmit (strict)
```

CI runs the exact same checks on every push and pull request
(`.github/workflows/lint.yml` and `tests.yml`).

## Testing

Tests live in `tests/Unit`, `tests/Feature`, and `tests/Browser`.

- **Feature** tests cover HTTP behaviour (routes, validation, policies, redirects).
- **Unit** tests cover pure logic (enums, value objects, rules).
- **Browser** tests drive the real UI with Playwright — clicking, filling forms,
  submitting, and asserting client-side feedback.

```bash
php artisan test                       # everything
php artisan test tests/Browser         # browser suite only
php artisan test --coverage --min=90   # with the coverage gate
```

Browser tests need built assets (`npm run build`) and the Chromium binary
(`npx playwright install chromium`).

## Project structure

```
app/
  Actions/        Single-purpose actions (CreateTeam, Fortify actions)
  Concerns/       Reusable traits (HasTeams, validation rule sets)
  Enums/          TeamRole, TeamPermission
  Http/           Controllers, Form Requests, Middleware, Fortify responses
  Models/         User, Team, Membership, TeamInvitation
  Policies/       Authorization (TeamPolicy)
resources/js/
  pages/          Inertia page components
  components/     Shared React components (ui/ is generated — don't edit)
  actions/ routes/ wayfinder/   Generated typed route helpers (don't edit)
tests/
  Unit/ Feature/ Browser/
.ai/guidelines/   Conventions for AI coding agents (see below)
```

## Conventions

The quality bar is documented for both humans and AI agents in
[`.ai/guidelines/`](.ai/guidelines):

- [`quality-standards.md`](.ai/guidelines/quality-standards.md) — the gate and how to run it
- [`php-conventions.md`](.ai/guidelines/php-conventions.md) — PHP 8.4, Pint, Rector, Larastan lvl 8
- [`frontend-conventions.md`](.ai/guidelines/frontend-conventions.md) — strict TS, ESLint/Prettier, Inertia, Wayfinder
- [`testing-conventions.md`](.ai/guidelines/testing-conventions.md) — feature/unit/browser tests, coverage

These files are composed into the agent guideline files (`CLAUDE.md`, `AGENTS.md`,
…) when you run `php artisan boost:install` or `php artisan boost:update`.

## License

MIT.
