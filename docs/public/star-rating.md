# StarRating

A clickable star-rating input for Filament v4.

The state is an integer from 1 to `max` (default 5), or `null` when unset. Hovering previews a rating; clicking a star commits it; clicking the star that is already selected clears it back to `null`, so "no rating" is always reachable (disable that with `->clearable(false)`).

Unlike the other fields in this package it is **not** a composition of existing components — Filament ships no star primitive — so it extends the public `Field` base with a tiny, self-contained Blade + Alpine view. It still stays strictly on the public field API: the standard field wrapper (`$getFieldWrapperView()`) and native state binding (`$wire.$entangle(...)`). It copies **no** internal Filament view and keeps **no** ad-hoc state container, so it upgrades with the framework like the rest.

Like every field here it assumes nothing about your model: bind it to any nullable integer column and keep chaining the native API (`->label()`, `->helperText()`, `->required()`, `->disabled()`, `->default()`, `->columnSpan()`, …).

## Basic Usage

```php
use HackeMate\FilamentExtraFields\Forms\Components\StarRating;

// Default: 1..5 stars, clearable
StarRating::make('rating')
    ->label('Rating')
    ->helperText('How would you rate it?');

// Custom: 1..10 stars, not clearable
StarRating::make('score')
    ->max(10)
    ->clearable(false);
```

## API Reference

### `make(string $name): static`

Creates the star rating field.

### `max(int $max): static`

Number of stars (the maximum selectable rating). Defaults to 5.

### `clearable(bool $clearable = true): static`

Whether clicking the currently selected star clears the value back to `null`. Defaults to `true`.

### Getters

- `getMax(): int` — Returns the configured maximum.
- `isClearable(): bool` — Returns whether clearing is enabled.

## Full Example

```php
use HackeMate\FilamentExtraFields\Forms\Components\StarRating;

public function form(Schema $schema): Schema
{
    return $schema->components([
        StarRating::make('product_rating')
            ->label('Product Rating')
            ->helperText('Rate this product from 1 to 5 stars')
            ->max(5)
            ->clearable(true)
            ->required()
            ->columnSpanFull(),

        StarRating::make('service_score')
            ->label('Service Score')
            ->max(10)
            ->clearable(false) // must pick a value
            ->default(7)
            ->helperText('Rate our service (1-10, cannot be cleared)'),
    ]);
}
```

## Binding to Database

Bind to any **nullable integer** column:

```php
// Migration
$table->unsignedTinyInteger('rating')->nullable(); // 1-5
$table->unsignedTinyInteger('score')->nullable();  // 1-10
```

```php
// Model
protected $casts = [
    'rating' => 'integer',
    'score'  => 'integer',
];
```

The field handles `null` (no rating) gracefully — the stars appear empty until the user clicks.

## How It Works

The field renders a Blade view (`filament-extra-fields::star-rating`) with a small Alpine.js component:

- **State** — bound via `$wire.$entangle()` to the Filament form state (native, no custom state container).
- **Hover preview** — moving the mouse over stars lights them up temporarily.
- **Click to set** — clicking a star commits the value.
- **Click to clear** — clicking the *already selected* star resets to `null` (if `clearable` is true).
- **Disabled state** — respects `->disabled()`; stars become non-interactive.
- **Accessibility** — uses `role="radiogroup"`, `role="radio"`, `aria-checked`, `aria-label`.

## CSS Customization

The stars use inline styles for colors (amber `#f59e0b` for lit, gray `#d1d5db` for unlit). To customize, publish the view and modify it:

```bash
php artisan vendor:publish --tag=filament-extra-fields-views
```

Then edit `resources/views/vendor/filament-extra-fields/star-rating.blade.php`.

Or override via CSS (the wrapper has class `fi-star-rating`):

```css
.fi-star-rating button svg {
    width: 2rem !important;
    height: 2rem !important;
}

.fi-star-rating button[x-bind:style*="color:#f59e0b"] svg {
    color: #eab308 !important; /* custom amber */
}

.fi-star-rating button[x-bind:style*="color:#d1d5db"] svg {
    color: #9ca3af !important; /* custom gray */
}
```

## Common Patterns

| Use Case | Config |
|----------|--------|
| Product reviews (1-5) | `->max(5)->clearable(true)` |
| NPS-style (1-10) | `->max(10)->clearable(false)` |
| Mandatory rating | `->required()->clearable(false)` |
| Optional feedback | `->clearable(true)->helperText('Optional')` |

## Limitations

- State is **integer only** (1..max, or null). No half-stars, no decimal ratings.
- No built-in "read-only display" mode — use `->disabled()` on a field with a default value.
- The view is minimal by design; for complex needs (tooltips per star, custom icons), publish and customize the view.

## See Also

- [Installation](installation.md)
- [InputWithSelect](input-with-select.md)
- [CSS Customization](css-customization.md)