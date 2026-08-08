<?php

namespace HackeMate\FilamentExtraFields\Forms\Components;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;

/**
 * A reusable "number + unit" field: an integer input fused with a unit dropdown into a single
 * control (the select sits *inside* the field, as a suffix), built on Filament v4's native
 * {@see FusedGroup} — the same primitive behind "amount + currency" inputs.
 *
 * Both halves are real Filament fields, so state, validation, hydration and reactivity
 * (`live()` / `afterStateUpdated`) are all native. There are no mirrored internal Blade views and no
 * ad-hoc containers, which is exactly what made the abandoned v3-era plugins break on upgrades.
 *
 * The unit dropdown is sized to its content (not a 50/50 grid split) by the bundled stylesheet, which
 * the package's service provider registers automatically.
 *
 * Example:
 *
 *     use Filament\Schemas\Components\Utilities\Get;
 *     use Filament\Schemas\Components\Utilities\Set;
 *     use HackeMate\FilamentExtraFields\Forms\Components\NumberWithUnit;
 *
 *     NumberWithUnit::make(
 *         numberName: 'duration_value',
 *         unitName: 'duration_unit',
 *         units: ['minutes' => 'minutes', 'hours' => 'hours', 'days' => 'days'],
 *         defaultUnit: 'hours',
 *         onChange: fn (Get $get, Set $set) => /* recompute end time, etc. */ null,
 *     )->label('Duration');
 */
final class NumberWithUnit
{
    /**
     * CSS hook applied to the FusedGroup wrapper; see resources/dist/filament-extra-fields.css.
     */
    public const CSS_CLASS = 'filament-extra-number-with-unit';

    /**
     * @param  string  $numberName  name / state path of the numeric input
     * @param  string  $unitName  name / state path of the unit select
     * @param  array<string, string>  $units  select options (value => label)
     * @param  string|null  $defaultUnit  initial unit value (e.g. 'hours')
     * @param  Closure|null  $onChange  afterStateUpdated callback (receives Get/Set) fired when either half changes
     * @param  bool  $ephemeral  when true (default), neither half is dehydrated — they are inputs, not columns
     */
    public static function make(
        string $numberName,
        string $unitName,
        array $units,
        ?string $defaultUnit = null,
        ?Closure $onChange = null,
        bool $ephemeral = true,
    ): FusedGroup {
        $number = TextInput::make($numberName)
            ->hiddenLabel()
            ->numeric()
            ->minValue(1)
            ->columnSpan(1);

        $unit = Select::make($unitName)
            ->hiddenLabel()
            ->options($units)
            ->selectablePlaceholder(false)
            ->native(false)
            ->columnSpan(1);

        if ($defaultUnit !== null) {
            $unit->default($defaultUnit);
        }

        if ($ephemeral) {
            $number->dehydrated(false);
            $unit->dehydrated(false);
        }

        if ($onChange !== null) {
            $number->live(debounce: '500ms')->afterStateUpdated($onChange);
            $unit->live()->afterStateUpdated($onChange);
        }

        return FusedGroup::make([$number, $unit])
            ->columns(2)
            ->extraAttributes(['class' => self::CSS_CLASS]);
    }
}
