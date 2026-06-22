# PHP Conventions

PHP code targets **8.4** and is governed by Pint, Rector, and Larastan (level 8).
Write code that already satisfies them so the tooling has nothing to fix.

## Language & style

- Every PHP file starts with `declare(strict_types=1);` (Pint adds it; write it
  yourself). The codebase uses strict comparisons (`===`) and strict params.
- Always declare parameter, return, and property types. No untyped signatures.
- Use constructor property promotion: `public function __construct(private CreateTeam $createTeam) {}`.
- Prefer PHPDoc array shapes (`array<int, string>`, `array{value: string, label: string}`)
  over bare `array`. Generic relations/collections are annotated
  (`@return BelongsToMany<Team, $this>`, `/** @use HasFactory<\Database\Factories\UserFactory> */`).
- Imports are ordered alphabetically and globally imported (no leading `\` on
  classes/functions in bodies). Let Pint enforce this.
- Use curly braces for all control structures, even single-line bodies.

## Static analysis (Larastan level 8)

Level 8 is strict about `null`. Two patterns are established in this codebase —
reuse them instead of reinventing:

- **Authenticated user in controllers:** never call `$request->user()->method()`
  directly (it is typed `?User`). Use the base controller helper
  `App\Http\Controllers\Controller::authenticatedUser($request)`, which returns a
  guaranteed non-null `User` (and fails closed with 403 otherwise).
- **Guaranteed relations:** when a related model is an invariant (e.g. a user's
  personal team), expose an explicit `*OrFail()` accessor using `firstOrFail()`
  (see `HasTeams::personalTeamOrFail()`) rather than silencing the nullable type.

**Do not** suppress errors with `@phpstan-ignore`, baseline entries, `assert()`,
inline `@var`, or type casts. Fix the underlying type. Tests are *not* analysed by
PHPStan (Pest's fluent DSL is not statically analysable), so keep production logic
out of test files.

## Doing things the Laravel way

- Create files with `php artisan make:*` and follow the structure of sibling files.
- Use Form Requests for validation, Policies for authorization (`Gate::authorize`),
  Eloquent relationships, and named routes via `route()` / `to_route()`.
- Reusable validation rule sets live in concerns (e.g. `ProfileValidationRules`).

## Running the tools

```bash
composer lint            # Pint (fix)        / composer lint:check
composer refactor        # Rector (apply)    / composer refactor:check
composer types:check     # Larastan level 8 (--memory-limit=512M)
```

After editing PHP, run `composer lint` then `composer refactor`, then re-run
`composer types:check`, and finally the tests. See [testing-conventions].
