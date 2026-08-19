# Contributing

Thank you for considering a contribution to Filament Extra Fields! This document outlines the process and guidelines.

## Before You Start

**Open an issue first** to discuss the field you want to add or the change you want to make. This avoids wasted effort on PRs that don't align with the project's direction.

## Design Principle

> **"Compose native primitives"**

Every field in this package must:
- Be built on **public, native Filament APIs** (no copied internal views, no ad-hoc state containers).
- Upgrade cleanly with Filament — if a Filament release breaks it, the field is fixed here, not by asking users to patch their code.
- Assume nothing about the consumer's model or use case — configure via the standard field API (`->label()`, `->required()`, `->live()`, etc.).

Fields that cannot be built cleanly on top of the framework **do not ship here**.

## What Makes a Good Field Candidate

- Fills a genuine gap in Filament v4 core.
- Can be built by composing existing Filament components (`FusedGroup`, `Field`, `Section`, etc.) or by extending `Field` with a minimal self-contained view.
- Has a clear, single responsibility.
- Is useful across many projects (not hyper-specific to one domain).

## Development Setup

```bash
# Clone the repo
git clone https://github.com/hackemate28-ux/filament-extra-fields.git
cd filament-extra-fields

# Install dependencies
composer install

# Run tests (if any)
composer test

# Run static analysis
composer analyse

# Run code style checks
composer lint
```

## Adding a New Field

1. **Create the component** in `src/Forms/Components/YourField.php`
   - Extend `Filament\Forms\Components\Field` (or compose `FusedGroup`/`Section`).
   - Use only public Filament APIs.
   - If it needs a view, place it in `resources/views/your-field.blade.php` and reference it via `$view = 'filament-extra-fields::your-field'`.
   - Add a CSS class constant if it needs styling (e.g., `public const CSS_CLASS = 'fi-your-field';`).

2. **Add styles** (if needed) in `resources/dist/filament-extra-fields.css`
   - Use logical properties for RTL support.
   - Prefix with `.fi-your-field`.

3. **Update the service provider** (if adding new views/assets):
   ```php
   $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-extra-fields');
   FilamentAsset::register([
       Css::make('filament-extra-fields', __DIR__.'/../resources/dist/filament-extra-fields.css'),
   ], package: 'hackemate28-ux/filament-extra-fields');
   ```

4. **Document it** in `docs/public/your-field.md`
   - Follow the structure of `input-with-select.md` and `star-rating.md`.
   - Include: basic usage, API reference, full example, binding, how it works, CSS customization, limitations.

5. **Update the main index** (`docs/public/index.md`) to link the new field.

6. **Add tests** (if test infrastructure exists).

## Code Style

- Follow PSR-12.
- Use `declare(strict_types=1);` at the top of every PHP file.
- Type-hint everything (params, returns, properties).
- Prefer `final` classes unless extension is intentional.
- Use descriptive variable names (`$statePath`, not `$sp`).
- Document public methods with PHPDoc.

## Commit Messages

Use conventional commits:

```
feat: add ColorPicker field
fix: StarRating clears on double-click when clearable=false
docs: update InputWithSelect reactivity example
style: fix indentation in StarRating view
refactor: extract CSS class constant from InputWithSelect
test: add StarRating max() validation test
```

## Pull Request Checklist

- [ ] Issue discussed and approved before starting.
- [ ] Field follows "compose native primitives" principle.
- [ ] Component uses only public Filament APIs.
- [ ] No copied internal Filament views.
- [ ] No ad-hoc state containers (use `$wire.$entangle()`).
- [ ] RTL-friendly CSS (logical properties).
- [ ] Documentation added in `docs/public/`.
- [ ] Tests pass (if applicable).
- [ ] Static analysis passes (`composer analyse`).
- [ ] Code style passes (`composer lint`).

## Reporting Bugs

Include:
- Filament version (`composer show filament/forms`).
- PHP version.
- Minimal reproduction (form schema, model, steps).
- Screenshots if visual.
- Error messages / stack traces.

## Feature Requests

Open an issue with:
- Use case description.
- Why it can't be done with existing Filament fields.
- Sketch/mockup of the expected UI (if applicable).
- Willingness to implement (PRs welcome!).

## License

By contributing, you agree that your contributions will be licensed under the MIT License (same as the project).

## Questions?

Open a [GitHub Discussion](https://github.com/hackemate28-ux/filament-extra-fields/discussions) or an issue.