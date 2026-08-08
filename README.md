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

### `NumberWithUnit`

A numeric input fused with a unit dropdown into a single control — the select sits *inside* the field,
as a suffix (the same shape as an "amount + currency" input). It is built on Filament's native
[`FusedGroup`](https://filamentphp.com/docs/4.x/schemas/layouts#fusing), so both halves are real
Filament fields: state, validation, hydration and reactivity are all native.

```php
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use HackeMate\FilamentExtraFields\Forms\Components\NumberWithUnit;

NumberWithUnit::make(
    numberName: 'duration_value',
    unitName: 'duration_unit',
    units: [
        'minutes' => 'minutes',
        'hours' => 'hours',
        'days' => 'days',
    ],
    defaultUnit: 'hours',
    onChange: function (Get $get, Set $set): void {
        // Fired whenever the number OR the unit changes — e.g. recompute an end time.
    },
)
    ->label('Duration')
    ->helperText('How long the event lasts.');
```

| Argument | Type | Description |
|---|---|---|
| `numberName` | `string` | Name / state path of the numeric input. |
| `unitName` | `string` | Name / state path of the unit select. |
| `units` | `array<string, string>` | Select options as `value => label`. |
| `defaultUnit` | `?string` | Initial unit value (e.g. `'hours'`). |
| `onChange` | `?Closure` | `afterStateUpdated` callback (receives `Get`/`Set`) fired when either half changes. |
| `ephemeral` | `bool` | When `true` (default), neither half is dehydrated — they are inputs, not model columns. |

`make()` returns a `FusedGroup`, so you can keep chaining any layout method on it (`->label()`,
`->helperText()`, `->columnSpan()`, `->visible()`, …).

The unit dropdown is sized to its content (not an equal-columns split) via the bundled stylesheet.

## Roadmap

This package starts small and grows as fields prove themselves in real projects. Contributions and
ideas for fields that native Filament still lacks are welcome.

## Contributing

Please open an issue to discuss a field before sending a large pull request. Keep the "compose native
primitives" principle in mind.

## License

The MIT License (MIT). See [LICENSE](LICENSE).
