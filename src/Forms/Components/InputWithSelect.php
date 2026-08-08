<?php

namespace HackeMate\FilamentExtraFields\Forms\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;

/**
 * Fuses a text/number input and a select into a single control — the select sits *inside* the field,
 * as a suffix (the same shape as an "amount + currency" input) — on top of Filament v4's native
 * {@see FusedGroup}.
 *
 * Both halves are plain Filament components that **you** build and configure with the full native
 * API (`->numeric()`, `->minValue()`, `->options()`, `->default()`, `->live()`,
 * `->afterStateUpdated()`, `->dehydrated()`, …). The `TextInput` may be text or numeric — this class
 * assumes nothing about your use case. It only hides the child labels (the group carries the label),
 * lays the two halves out side by side, and applies the stylesheet that sizes the select to its
 * content and keeps the inner corners flush.
 *
 * Returns the underlying `FusedGroup`, so you keep chaining any layout method on it — `->label()`,
 * `->helperText()`, `->columnSpan()`, `->visible()`, …
 *
 * Tip: prefer a **native** select (`Select`'s default) for the second half. A native `<select>` sizes
 * to its widest option and never wraps, so the control stays a stable width; a JS select
 * (`->native(false)`) sizes its trigger to the *selected* value, so the width shifts as you change it.
 *
 * Example:
 *
 *     use Filament\Forms\Components\Select;
 *     use Filament\Forms\Components\TextInput;
 *     use HackeMate\FilamentExtraFields\Forms\Components\InputWithSelect;
 *
 *     InputWithSelect::make(
 *         input: TextInput::make('duration_value')->numeric()->minValue(1),
 *         select: Select::make('duration_unit')
 *             ->options(['minutes' => 'Minutes', 'hours' => 'Hours', 'days' => 'Days'])
 *             ->default('hours'),
 *     )->label('Duration');
 */
final class InputWithSelect
{
    /**
     * CSS hook applied to the FusedGroup wrapper; see resources/dist/filament-extra-fields.css.
     */
    public const CSS_CLASS = 'fi-input-with-select';

    /**
     * @param  TextInput  $input  the text/number input, configured by the caller
     * @param  Select  $select  the select, configured by the caller
     */
    public static function make(TextInput $input, Select $select): FusedGroup
    {
        return FusedGroup::make([
            $input->hiddenLabel()->columnSpan(1),
            $select->hiddenLabel()->columnSpan(1),
        ])
            ->columns(2)
            ->extraAttributes(['class' => self::CSS_CLASS]);
    }
}
