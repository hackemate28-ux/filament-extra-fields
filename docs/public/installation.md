# Installation

## Requirements

- **PHP 8.2+**
- **Filament 4.0+** (specifically `filament/forms`, `filament/schemas`, `filament/support`)

## Install via Composer

```bash
composer require hackemate28-ux/filament-extra-fields
```

That's it. The package uses Laravel's package auto-discovery:

- The service provider (`FilamentExtraFieldsServiceProvider`) registers automatically.
- The bundled CSS (`filament-extra-fields.css`) is injected on every Filament page via `FilamentAsset`.
- The Blade view for `StarRating` is namespaced under `filament-extra-fields::` and loaded from the package.

## Verify Installation

After installing, you can use the fields immediately in any Filament form schema:

```php
use HackeMate\FilamentExtraFields\Forms\Components\InputWithSelect;
use HackeMate\FilamentExtraFields\Forms\Components\StarRating;

public function form(Schema $schema): Schema
{
    return $schema->components([
        InputWithSelect::make(
            input: TextInput::make('price')->numeric(),
            select: Select::make('currency')->options(['USD' => '$', 'EUR' => '€']),
        ),
        StarRating::make('rating')->max(5),
    ]);
}
```

## Updating

```bash
composer update hackemate28-ux/filament-extra-fields
```

Check the [changelog](https://github.com/hackemate28-ux/filament-extra-fields/releases) for breaking changes.

## Troubleshooting

### Styles not loading

Ensure your Filament layout includes the assets stack (default in Filament 4):

```blade
@filamentScripts
@filamentStyles
```

### View not found for StarRating

Run `php artisan view:clear` if you're developing locally and the view isn't resolved from the package namespace.

### Auto-discovery not working

In rare cases (custom Laravel setup), manually register the provider in `config/app.php`:

```php
'providers' => [
    // ...
    HackeMate\FilamentExtraFields\FilamentExtraFieldsServiceProvider::class,
],
```