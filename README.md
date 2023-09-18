# See, Understand, and Handle Laravel Errors using ErrorLens

[![Latest Version on Packagist](https://img.shields.io/packagist/v/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)
[![Total Downloads](https://img.shields.io/packagist/dt/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)

![ErrorLens](https://github.com/narolalabs/error-lens/assets/143481636/8ff8f140-6dc7-406e-b060-986914886cc1)


## Version Compatibility

 PHP      | 7.1, 7.2, 7.3, 7.4, 8.0  |
:---------|--------------------------|
Laravel   | 8.x, 9.x                 |

## Installation

You can install the package via composer:

```bash
composer require narolalabs/error-lens
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="error-lens-migrations"
```

Run migration with:
```bash
php artisan migrate
```

You can publish the assets file with:

```bash
php artisan vendor:publish --tag="error-lens-assets"
```

This is the contents of the published assets file in the `public/vendors/error-lens`.

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="error-lens-views"
```

Register `ErrorLens` middleware in your `app/Http/Kernel.php`

```php
protected $middleware = [
    // ...
    \Narolalabs\ErrorLens\Middleware\ErrorLens::class,
];
```

## Usage

Make sure that you have set **PRODUCTION** from `config/app.php` and set `APP_DEBUG` to `false`.

After changing the configuration, make sure you clear the config.

```bash
php artisan config:clear
```

To view all the error logs, visit the `https://domain.com/error-lens`

![Error Lens - Dashbaord](https://github.com/narolalabs/error-lens/assets/143481636/54c1c0f0-a988-4754-9631-981bd485464e)

![Error Lens - Error modal view](https://github.com/narolalabs/error-lens/assets/143481636/d4a938fe-ff2f-4ee7-93bc-dffce50ccd35)

![Error Lens - Full page error view](https://github.com/narolalabs/error-lens/assets/143481636/cf078de5-1435-4896-9c78-c2a5336f4e17)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Narola Labs](https://github.com/narolalabs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
