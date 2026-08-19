# Filament Extra Fields

Extra form fields for [Filament v4](https://filamentphp.com), built on **native Filament primitives** rather than mirrored internal views. The goal is a small, well-maintained set of fields that fill gaps left by v3-era plugins that never made the jump to v4.

> **Design principle:** every field composes public, native Filament components. No copied internal Blade views, no ad-hoc state containers — those are exactly what broke the abandoned plugins on each Filament release. If a field can't be built cleanly on top of the framework, it doesn't ship here.

## Requirements

- PHP 8.2+
- Filament 4.0+

## Installation

```bash
composer require hackemate28-ux/filament-extra-fields
```

The service provider is auto-discovered and registers the bundled stylesheet on Filament pages, so there is nothing else to wire up.

## Fields Overview

| Field | Description |
|-------|-------------|
| [`InputWithSelect`](input-with-select.md) | A text/number input fused with a select into a single control — the select sits *inside* the field as a suffix (like an "amount + currency" input). |
| [`StarRating`](star-rating.md) | A clickable star-rating input (1–5 by default, configurable up to any number). |

## Quick Examples

### InputWithSelect

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

### StarRating

```php
use HackeMate\FilamentExtraFields\Forms\Components\StarRating;

StarRating::make('rating')
    ->label('Rating')
    ->helperText('How would you rate it?');

StarRating::make('score')
    ->max(10)              // 1..10 instead of 1..5
    ->clearable(false);    // clicking the selected star no longer unsets it
```

## Documentation

- [Installation](installation.md)
- [InputWithSelect](input-with-select.md)
- [StarRating](star-rating.md)
- [CSS Customization](css-customization.md)
- [Contributing](contributing.md)

## License

The MIT License (MIT). See [LICENSE](../../LICENSE).