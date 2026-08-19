# CSS Customization

The package ships a minimal stylesheet (`filament-extra-fields.css`) that is automatically loaded on all Filament pages via `FilamentAsset`. You can override or extend these styles in your own stylesheet.

## Loaded Assets

The service provider registers:

```php
FilamentAsset::register([
    Css::make('filament-extra-fields', __DIR__.'/../resources/dist/filament-extra-fields.css'),
], package: 'hackemate28-ux/filament-extra-fields');
```

This means the CSS loads on every Filament page (admin panel, forms, resources, etc.).

## InputWithSelect Styles

### Default CSS (bundled)

```css
/* InputWithSelect: the select takes only the width of its content while the input absorbs the rest,
   instead of the FusedGroup's default equal-columns split. `.fi-sc` is the inner grid rendered by the
   FusedGroup; the wrapper class is set by InputWithSelect::CSS_CLASS. */
.fi-input-with-select > .fi-sc {
    grid-template-columns: minmax(0, 1fr) max-content !important;
}

/* Keep the two halves flush: square the inner corners (the input's trailing edge and the select's
   leading edge) so they sit against each other as one control — even under themes that round every
   input wrapper, and regardless of the responsive grid class the corner rules would otherwise need.
   Logical properties, so this follows the writing direction (LTR/RTL). */
.fi-input-with-select > .fi-sc > :first-child .fi-input-wrp {
    border-start-end-radius: 0 !important;
    border-end-end-radius: 0 !important;
}

.fi-input-with-select > .fi-sc > :last-child .fi-input-wrp {
    border-start-start-radius: 0 !important;
    border-end-start-radius: 0 !important;
}
```

### Override Examples

**Make the select a fixed width:**

```css
.fi-input-with-select > .fi-sc {
    grid-template-columns: minmax(0, 1fr) 140px !important;
}
```

**Custom border radius (rounded outer corners, squared inner):**

```css
.fi-input-with-select > .fi-sc > :first-child .fi-input-wrp {
    border-start-end-radius: 0 !important;
    border-end-end-radius: 0 !important;
    border-start-start-radius: 0.5rem !important;
    border-end-start-radius: 0.5rem !important;
}

.fi-input-with-select > .fi-sc > :last-child .fi-input-wrp {
    border-start-start-radius: 0 !important;
    border-end-start-radius: 0 !important;
    border-start-end-radius: 0.5rem !important;
    border-end-end-radius: 0.5rem !important;
}
```

**Add a subtle border between input and select:**

```css
.fi-input-with-select > .fi-sc > :first-child .fi-input-wrp {
    border-end-end-radius: 0;
    border-end-start-radius: 0;
    border-inline-end: 1px solid var(--border-color, #e5e7eb);
}

.fi-input-with-select > .fi-sc > :last-child .fi-input-wrp {
    border-start-start-radius: 0;
    border-end-start-radius: 0;
}
```

**Dark mode support:**

```css
.dark .fi-input-with-select > .fi-sc > :first-child .fi-input-wrp {
    border-inline-end-color: #374151;
}
```

## StarRating Styles

The StarRating view uses inline styles for the star colors. The wrapper has class `fi-star-rating`.

### Default Colors (inline in Blade)

- **Lit (filled) star:** `#f59e0b` (amber-500)
- **Unlit (empty) star:** `#d1d5db` (gray-300)

### Override via CSS

```css
/* Change star size */
.fi-star-rating button svg {
    width: 2rem !important;
    height: 2rem !important;
}

/* Change lit star color */
.fi-star-rating button:has(svg[style*="color: #f59e0b"]) svg {
    color: #eab308 !important; /* yellow-500 */
}

/* Change unlit star color */
.fi-star-rating button:has(svg[style*="color: #d1d5db"]) svg {
    color: #9ca3af !important; /* gray-400 */
}

/* Hover effect enhancement */
.fi-star-rating button:hover svg {
    transform: scale(1.1);
    transition: transform 0.1s ease;
}

/* Disabled state */
.fi-star-rating:has([disabled]) button {
    opacity: 0.5;
    cursor: not-allowed;
}
```

### Override via View Publishing (Recommended for Major Changes)

Publish the view to fully customize the markup and Alpine logic:

```bash
php artisan vendor:publish --tag=filament-extra-fields-views
```

This creates:
```
resources/views/vendor/filament-extra-fields/star-rating.blade.php
```

Edit this file to:
- Change star SVG to a different icon (e.g., hearts, thumbs up)
- Add tooltips per star
- Add half-star support
- Change the Alpine logic entirely

## Adding Your Own Stylesheet

In your Filament panel provider or `app.css`:

```php
// In your PanelProvider::boot()
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;

FilamentAsset::register([
    Css::make('my-custom-fields', resource_path('css/filament-custom.css')),
]);
```

Or via Vite (recommended):

```css
/* resources/css/app.css */
@import 'filament-extra-fields'; /* if you copy the CSS locally */

/* Your overrides */
.fi-input-with-select > .fi-sc {
    grid-template-columns: minmax(0, 1fr) 120px !important;
}

.fi-star-rating button svg {
    width: 1.75rem;
    height: 1.75rem;
}
```

```js
// vite.config.js
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

## RTL Support

The bundled CSS uses **logical properties** (`border-start-end-radius`, `border-inline-end`, etc.) so it automatically adapts to RTL layouts. No extra work needed.

If you write custom overrides, prefer logical properties:

```css
/* Instead of border-right-radius */
border-end-end-radius: 0.5rem;

/* Instead of border-left */
border-inline-start: 1px solid #e5e7eb;
```

## CSS Variables (Future)

Currently no CSS variables are exposed. If you'd like them (e.g., `--fi-star-color-filled`, `--fi-star-color-empty`), open an issue or PR.

## See Also

- [InputWithSelect](input-with-select.md)
- [StarRating](star-rating.md)
- [Installation](installation.md)