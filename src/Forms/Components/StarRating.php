<?php

namespace HackeMate\FilamentExtraFields\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * A clickable star-rating input for Filament v4.
 *
 * The state is an integer from 1 to {@see max()} (default 5), or `null` when unset. Hovering previews a
 * rating; clicking a star commits it; clicking the star that is already selected clears it back to
 * `null`, so "no rating" is always reachable (disable that with `->clearable(false)`).
 *
 * Unlike the other fields in this package it is **not** a composition of existing components — Filament
 * ships no star primitive — so it extends the public {@see Field} base with a tiny, self-contained
 * Blade + Alpine view. It still stays strictly on the public field API: the standard field wrapper
 * (`$getFieldWrapperView()`) and native state binding (`$wire.$entangle(...)`). It copies **no** internal
 * Filament view and keeps **no** ad-hoc state container, so it upgrades with the framework like the rest.
 *
 * Like every field here it assumes nothing about your model: bind it to any nullable integer column and
 * keep chaining the native API (`->label()`, `->helperText()`, `->required()`, `->disabled()`,
 * `->default()`, `->columnSpan()`, …).
 *
 * Example:
 *
 *     use HackeMate\FilamentExtraFields\Forms\Components\StarRating;
 *
 *     StarRating::make('rating')->label('Rating');            // 1..5, clearable
 *     StarRating::make('score')->max(10)->clearable(false);   // 1..10, cannot be unset
 */
class StarRating extends Field
{
    protected string $view = 'filament-extra-fields::star-rating';

    protected int $max = 5;

    protected bool $clearable = true;

    /**
     * Number of stars (the maximum selectable rating). Defaults to 5.
     */
    public function max(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Whether clicking the currently selected star clears the value back to `null`. Defaults to `true`.
     */
    public function clearable(bool $clearable = true): static
    {
        $this->clearable = $clearable;

        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function isClearable(): bool
    {
        return $this->clearable;
    }
}
