# See, Understand, and Handle Laravel Errors using ErrorLens

[![Latest Version on Packagist](https://img.shields.io/packagist/v/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)
[![Total Downloads](https://img.shields.io/packagist/dt/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)

![image](https://github.com/narolalabs/error-lens/assets/143481636/30da69dc-c36e-4431-bf7e-008324945fe7)

## Installation

You can install the package via composer:

```bash
composer require narolalabs/error-lens
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="error-lens-migrations"
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

Now that your project is live and debug mode is set to `false`, ErrorLens will capture any exceptions.

To view all the errors, visit the `https://domain.com/error-lens`

![image](https://github.com/narolalabs/error-lens/assets/143481636/30da69dc-c36e-4431-bf7e-008324945fe7)

![image](https://github.com/narolalabs/error-lens/assets/143481636/8fe72323-4171-46df-bdf2-51332d815ed9)


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
