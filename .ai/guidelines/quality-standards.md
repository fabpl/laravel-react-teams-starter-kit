# Quality Standards

This project enforces a high quality bar. Treat the following as mandatory when
adding or changing code. These guidelines are composed into the agent guideline
files by `php artisan boost:install` / `boost:update`.

## The non-negotiables

- **Every change is tested.** New behaviour ships with feature, unit, and (for
  anything touching navigation/forms) browser tests. See `testing-conventions`.
- **Static analysis stays green.** Larastan runs at **level 8** with zero errors.
- **Code is formatted and refactored automatically.** Pint and Rector own the
  style; never hand-format around them.
- **The frontend is strictly typed.** TypeScript runs with the strict flag set
  plus `noUncheckedIndexedAccess`, `noUnusedLocals/Parameters`, etc.
- **Coverage must stay at or above 95%.** `composer test` fails below that.

## Before you finalise any change

Run the full gate locally. `composer test` chains all of it:

```bash
composer test
```

It runs, in order:

1. `pint --parallel --test` — PHP code style check
2. `rector process --dry-run` — automated refactor check
3. `phpstan analyse` (level 8) — static analysis
4. `php artisan test --coverage --min=95` — the full suite (Unit, Feature,
   Browser) with the coverage gate

For the frontend, also run:

```bash
npm run lint:check    # ESLint
npm run format:check  # Prettier
npm run types:check   # tsc --noEmit
```

To auto-fix instead of check: `composer lint`, `composer refactor`,
`npm run lint`, `npm run format`.

## CI

Two GitHub Actions workflows enforce the same gate on every push/PR:
`.github/workflows/lint.yml` (Pint, Rector, ESLint, Prettier) and
`.github/workflows/tests.yml` (Larastan, tsc, Playwright browser tests, and the
coverage-gated test suite).

See also: [php-conventions], [frontend-conventions], [testing-conventions].
