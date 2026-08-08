# Filament Extra Fields

Extra form fields for [Filament v4](https://filamentphp.com), built on **native Filament primitives**
rather than mirrored internal views. The goal is a small, well-maintained set of fields that fill gaps
left by v3-era plugins that never made the jump to v4.

> **Design principle:** every field composes public, native Filament components. No copied internal
> Blade views, no ad-hoc state containers — those are exactly what broke the abandoned plugins on each
> Filament release. If a field can't be built cleanly on top of the framework, it doesn't ship here.

## Requirements

- PHP 8.2+
- Filament 4.0+

## Installation

```bash
composer require hackemate28-ux/filament-extra-fields
```

The service provider is auto-discovered and registers the bundled stylesheet on Filament pages, so
there is nothing else to wire up.

## Fields

### `InputWithSelect`

A text or number input fused with a select into a single control — the select sits *inside* the field,
as a suffix (the same shape as an "amount + currency" input). It is built on Filament's native
[`FusedGroup`](https://filamentphp.com/docs/4.x/schemas/layouts#fusing), so both halves are real
Filament fields: state, validation, hydration and reactivity are all native.

You build and configure **both halves yourself** with the full native API. `InputWithSelect` assumes
nothing about your use case — it only hides the child labels (the group carries the label), lays the
two halves side by side, and applies the stylesheet that sizes the select to its content and keeps the
inner corners flush:

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use HackeMate\FilamentExtraFields\Forms\Components\InputWithSelect;

InputWithSelect::make(
    input: TextInput::make('duration_value')
        ->numeric()
        ->minValue(1),
    select: Select::make('duration_unit')
        ->options(['minutes' => 'Minutes', 'hours' => 'Hours', 'days' => 'Days'])
        ->default('hours'),
)
    ->label('Duration')
    ->helperText('How long it lasts.');
```

| Argument | Type | Description |
|---|---|---|
| `input` | `TextInput` | The text or number input, configured by you (`->numeric()`, `->minValue()`, `->live()`, …). |
| `select` | `Select` | The select, configured by you (`->options()`, `->default()`, `->live()`, …). |

Because both halves are ordinary Filament components, anything native works — make them reactive with
`->live()->afterStateUpdated(...)`, keep them out of the model with `->dehydrated(false)`, validate
them, and so on. `make()` returns the `FusedGroup`, so keep chaining layout methods on it (`->label()`,
`->helperText()`, `->columnSpan()`, `->visible()`, …).

**Prefer a native select.** A native `<select>` (Filament's default) sizes itself to its widest option
and never wraps, so the fused control keeps a stable width. A JS select (`->native(false)`) sizes its
trigger to the *selected* value, so the control's width shifts as the selection changes.

The select is sized to its content (not an equal-columns split) via the bundled stylesheet.

## Roadmap

This package starts small and grows as fields prove themselves in real projects. Contributions and
ideas for fields that native Filament still lacks are welcome.

## Contributing

Please open an issue to discuss a field before sending a large pull request. Keep the "compose native
primitives" principle in mind.

## License

The MIT License (MIT). See [LICENSE](LICENSE).
