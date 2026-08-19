# InputWithSelect

A text or number input fused with a select into a single control — the select sits *inside* the field as a suffix (the same shape as an "amount + currency" input). It is built on Filament's native [`FusedGroup`](https://filamentphp.com/docs/4.x/schemas/layouts#fusing), so both halves are real Filament fields: state, validation, hydration and reactivity are all native.

## Basic Usage

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use HackeMate\FilamentExtraFields\Forms\Components\InputWithSelect;

InputWithSelect::make(
    input: TextInput::make('duration_value')
        ->numeric()
        ->minValue(1),
    select: Select::make('duration_unit')
        ->options([
            'minutes' => 'Minutes',
            'hours'   => 'Hours',
            'days'    => 'Days',
        ])
        ->default('hours'),
)
    ->label('Duration')
    ->helperText('How long it lasts.');
```

## API Reference

### `make(TextInput $input, Select $select): FusedGroup`

Creates the fused group. Returns the underlying `FusedGroup`, so you can chain any layout method on it.

| Parameter | Type | Description |
|-----------|------|-------------|
| `input` | `TextInput` | The text or number input, configured by you (`->numeric()`, `->minValue()`, `->live()`, `->placeholder()`, etc.). |
| `select` | `Select` | The select, configured by you (`->options()`, `->default()`, `->live()`, `->searchable()`, etc.). |

### Chaining on the returned `FusedGroup`

Since `make()` returns a `FusedGroup`, you can chain any native layout method:

```php
InputWithSelect::make($input, $select)
    ->label('Duration')
    ->helperText('Pick a value and unit')
    ->columnSpanFull()
    ->visible(fn (Get $get) => $get('has_duration'))
    ->required()
    ->dehydrated(false); // exclude from model if needed
```

## How It Works

1. **Hides child labels** — the group carries the label.
2. **Lays both halves side by side** — 2 columns via `FusedGroup::make(...)->columns(2)`.
3. **Applies CSS class `fi-input-with-select`** — the bundled stylesheet makes the select size to its content (not 50/50 split) and squares the inner corners so the two halves look like one control.
## Important: Prefer Native Select

Use the default **native `<select>`** (Filament's default) for the second half.

| Select Type | Behavior |
|-------------|----------|
| **Native** (`Select::make(...)` — default) | Sizes to the *widest option*; control width stays stable. |
| **JS-powered** (`->native(false)`) | Sizes trigger to the *selected value*; control width shifts on change. |

The CSS rule `.fi-input-with-select > .fi-sc { grid-template-columns: minmax(0, 1fr) max-content !important; }` expects the select to be `max-content` wide — a native select guarantees this.

## Full Example: Price with Currency

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use HackeMate\FilamentExtraFields\Forms\Components\InputWithSelect;

InputWithSelect::make(
    input: TextInput::make('price')
        ->label('Price')
        ->numeric()
        ->minValue(0)
        ->step(0.01)
        ->placeholder('0.00')
        ->required(),
    select: Select::make('currency')
        ->label('Currency')
        ->options([
            'USD' => 'USD ($)',
            'EUR' => 'EUR (€)',
            'GBP' => 'GBP (£)',
            'JPY' => 'JPY (¥)',
        ])
        ->default('USD')
        ->native() // important: keep native select
        ->required(),
)
    ->label('Price')
    ->columnSpanFull();
```

## Reactivity Example

Both halves are ordinary Filament components — reactivity works natively:

```php
InputWithSelect::make(
    input: TextInput::make('quantity')
        ->numeric()
        ->live(onBlur: true)
        ->afterStateUpdated(function (Get $get, Set $set, $state) {
            $unit = $get('unit');
            if ($unit === 'kg' && $state > 100) {
                // auto-switch to tons for large quantities
                $set('unit', 'ton');
            }
        }),
    select: Select::make('unit')
        ->options(['g' => 'g', 'kg' => 'kg', 'ton' => 'ton'])
        ->default('kg')
        ->native()
        ->live()
        ->afterStateUpdated(function (Get $get, Set $set, $state) {
            $qty = $get('quantity');
            if ($state === 'g' && $qty && $qty >= 1000) {
                $set('quantity', $qty / 1000);
                $set('unit', 'kg');
            }
        }),
)
    ->label('Quantity');
```

## CSS Customization

The field uses the CSS class `fi-input-with-select`. You can override the bundled styles in your own stylesheet:

```css
/* Make the select wider */
.fi-input-with-select > .fi-sc {
    grid-template-columns: minmax(0, 1fr) 120px !important;
}

/* Custom border radius */
.fi-input-with-select > .fi-sc > :first-child .fi-input-wrp {
    border-start-end-radius: 0.5rem !important;
    border-end-end-radius: 0.5rem !important;
}

.fi-input-with-select > .fi-sc > :last-child .fi-input-wrp {
    border-start-start-radius: 0.5rem !important;
    border-end-start-radius: 0.5rem !important;
}
```

See [CSS Customization](css-customization.md) for more details.

## Common Patterns

| Pattern | Input Config | Select Config |
|---------|--------------|---------------|
| Price + Currency | `->numeric()->step(0.01)->minValue(0)` | Currency codes with symbols |
| Duration + Unit | `->numeric()->minValue(1)` | `minutes`, `hours`, `days`, `weeks` |
| Quantity + Unit | `->numeric()->minValue(0)` | `g`, `kg`, `lb`, `oz`, `pcs` |
| Date + Timezone | `->date()` | Timezone list |

## Limitations

- Only supports `TextInput` (text or numeric) + `Select`. Other combinations (e.g., `DatePicker` + `Select`) are not supported.
- The select **must** be a `Select` component (not `Radio`, `CheckboxList`, etc.).
- For RTL languages, the CSS uses logical properties (`border-start-end-radius`, etc.) so it adapts automatically.

## See Also

- [Installation](installation.md)
- [StarRating](star-rating.md)
- [CSS Customization](css-customization.md)
- [Filament FusedGroup Docs](https://filamentphp.com/docs/4.x/schemas/layouts#fusing)