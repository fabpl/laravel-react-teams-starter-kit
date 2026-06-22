# Frontend Conventions

The frontend is React 19 + Inertia v3 + TypeScript, linted by ESLint and
formatted by Prettier. TypeScript runs in strict mode.

## TypeScript

`tsconfig.json` enables `strict: true` plus the additive strict flags:
`noUnusedLocals`, `noUnusedParameters`, `noImplicitReturns`,
`noFallthroughCasesInSwitch`, `noUncheckedIndexedAccess`, and
`noImplicitOverride`. Write code that satisfies them:

- **Indexed access yields `T | undefined`** (`noUncheckedIndexedAccess`). Guard or
  default it: `(names[0] ?? '').charAt(0)`, `slots[index] ?? {}`. Do not assume an
  array/record lookup is defined.
- No unused locals or parameters. Prefix an intentionally-unused arg with `_`.
- Avoid `any` (it is an ESLint warning); type props and API payloads explicitly.
- Functions with a declared return type must return on every path.

## ESLint / Prettier

- Use **type-only imports** (`import type { Foo }`) — enforced by
  `@typescript-eslint/consistent-type-imports`. Imports are ordered/grouped.
- Do not hand-format; Prettier owns whitespace, quotes (single), semicolons, and
  Tailwind class ordering. Generated directories (`resources/js/actions`,
  `routes`, `wayfinder`, `components/ui`) are ignored by ESLint — do not edit
  generated files by hand.

## Inertia & routing

- Pages live in `resources/js/pages`; use `Inertia::render()` server-side.
- Call backend endpoints through **Wayfinder**-generated helpers
  (`@/actions/*`, `@/routes/*`) — never hardcode URLs. Run
  `npm run build` (or the dev server) to regenerate them; `tsc` depends on them.
- Add a `data-test="..."` attribute to interactive elements (buttons, inputs) so
  browser tests can target them stably. This convention is already used across
  the auth and team UIs (`@login-button`, `@create-team-submit`, etc.).

## Running the tools

```bash
npm run lint:check     # ESLint   (npm run lint to fix)
npm run format:check   # Prettier (npm run format to fix)
npm run types:check    # tsc --noEmit
```

See also [testing-conventions] for browser tests, and [quality-standards].
