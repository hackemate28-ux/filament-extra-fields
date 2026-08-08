<?php

namespace HackeMate\FilamentExtraFields\Forms\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;

/**
 * Fuses a numeric input and a unit dropdown into a single control — the select sits *inside* the
 * field, as a suffix (the same shape as an "amount + currency" input) — on top of Filament v4's
 * native {@see FusedGroup}.
 *
 * Both halves are plain Filament components that **you** build and configure with the full native
 * API (`->numeric()`, `->minValue()`, `->options()`, `->default()`, `->live()`,
 * `->afterStateUpdated()`, `->dehydrated()`, …). This class assumes nothing about your use case: it
 * only hides the child labels (the group carries the label), lays the two halves out side by side,
 * and applies the stylesheet that sizes the unit to its content. Everything else is yours.
 *
 * Returns the underlying `FusedGroup`, so you keep chaining any layout method on it — `->label()`,
 * `->helperText()`, `->columnSpan()`, `->visible()`, …
 *
 * Example:
 *
 *     use Filament\Forms\Components\Select;
 *     use Filament\Forms\Components\TextInput;
 *     use HackeMate\FilamentExtraFields\Forms\Components\NumberWithUnit;
 *
 *     NumberWithUnit::make(
 *         number: TextInput::make('duration_value')->numeric()->minValue(1),
 *         unit: Select::make('duration_unit')
 *             ->options(['minutes' => 'Minutes', 'hours' => 'Hours', 'days' => 'Days'])
 *             ->default('hours'),
 *     )->label('Duration');
 */
final class NumberWithUnit
{
    /**
     * CSS hook applied to the FusedGroup wrapper; see resources/dist/filament-extra-fields.css.
     */
    public const CSS_CLASS = 'filament-extra-number-with-unit';

    /**
     * @param  TextInput  $number  the numeric input, configured by the caller
     * @param  Select  $unit  the unit dropdown, configured by the caller
     */
    public static function make(TextInput $number, Select $unit): FusedGroup
    {
        return FusedGroup::make([
            $number->hiddenLabel()->columnSpan(1),
            $unit->hiddenLabel()->columnSpan(1),
        ])
            ->columns(2)
            ->extraAttributes(['class' => self::CSS_CLASS]);
    }
}
