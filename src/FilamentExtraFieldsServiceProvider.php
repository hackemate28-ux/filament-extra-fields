<?php

namespace HackeMate\FilamentExtraFields;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentExtraFieldsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Views for the fields that need one (e.g. StarRating). Namespaced so consumers can override
        // them if they wish; resolved from the package whether it runs from source or from vendor/.
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-extra-fields');

        // Registers the bundled stylesheet so it loads on every Filament page. The fields ship their
        // own CSS instead of asking consumers to paste rules into their theme — the package is
        // self-contained and portable across projects.
        FilamentAsset::register([
            Css::make('filament-extra-fields', __DIR__.'/../resources/dist/filament-extra-fields.css'),
        ], package: 'hackemate28-ux/filament-extra-fields');
    }
}
