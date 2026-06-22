# Testing Conventions

Testing is mandatory and uses **Pest 4**. Coverage must stay **≥ 95%**
(`php artisan test --coverage --min=95`, wired into `composer test`).

## What to write for a change

- **Feature tests** for HTTP behaviour: routes, controllers, Form Request
  validation, policies, redirects, notifications. Most tests are feature tests.
- **Unit tests** for pure logic (enums, value objects, rules) that needs no
  database or HTTP context.
- **Browser tests** (Playwright) for anything involving navigation: clicking,
  filling and submitting forms, client-side validation feedback, and smoke
  checks. Every interactive feature gets a browser test.

## Suites & configuration

- Tests live in `tests/Unit`, `tests/Feature`, and `tests/Browser`. The suites
  are registered in `phpunit.xml`; `tests/Pest.php` binds `RefreshDatabase` to
  `Feature` and `Browser`.
- **Database-backed tests must be Feature or Browser tests** (Unit tests run on
  the bare PHPUnit `TestCase` without the app container or `RefreshDatabase` —
  framework helpers like `abort()` are unavailable there).
- Use model **factories** and their states; do not hand-build models. Note: a
  `User` factory provisions a personal team, so scope membership queries to the
  specific team you created.

## Browser tests (Pest + Playwright)

Setup is already in place (`pestphp/pest-plugin-browser`, `playwright`). Run
`npx playwright install chromium` once locally, and `npm run build` so the
real pages have assets.

- Use `visit('/path')` then chain interactions: `->fill('email', '…')`,
  `->click('@login-button')`, `->assertSee('…')`.
- Target elements by their `data-test` attribute via the `@name` selector
  (e.g. `@create-team-submit`). Add `data-test` to UI you need to drive.
- Always assert `->assertNoJavascriptErrors()` (or `assertNoSmoke()` for pure
  smoke checks across multiple pages).
- Drive **server-side** validation by submitting input that passes the browser's
  native `required`/`type` checks but fails server rules (e.g. a duplicate email,
  a reserved team name) — an empty submit is blocked by the browser first.
- Use Laravel helpers freely: `$this->actingAs($user)`,
  `Notification::fake()` + `Notification::assertSentTo(...)`, `assertDatabaseHas`.

## Coverage & production-only code

- Keep coverage ≥ 95%; add tests rather than lowering the threshold.
- Only genuinely unreachable-in-tests production code (e.g. behaviour gated on
  `app()->isProduction()`) may use a documented `@codeCoverageIgnore`. Never use
  it to paper over untested logic.

## Commands

```bash
composer test                                   # full gate incl. coverage
php artisan test --compact --filter=Name        # focused run
php artisan test tests/Browser                  # browser suite only
```

See also [quality-standards], [php-conventions], [frontend-conventions].
