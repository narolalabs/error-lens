# See, Understand, and Handle Laravel Errors using ErrorLens

[![Latest Version on Packagist](https://img.shields.io/packagist/v/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)
[![Total Downloads](https://img.shields.io/packagist/dt/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)

![ErrorLens](https://github.com/narolalabs/error-lens/blob/v2.x/resources/dist/assets/readme-images/Banner.png)


## Version Compatibility

 PHP      | 7.3, 7.4, 8.0, 8.1  |
:---------|---------------------|
Laravel   | 8.x, 9.x, 10.x      |

## Installation

_Please adhere to the following instructions meticulously to successfully complete the installation process._

1. Package Installation **(Required)**
> ```bash
> composer require narolalabs/error-lens
> ```


2. Publish and run migration **(Required)**
> ```bash
> php artisan vendor:publish --tag="error-lens-migrations"
> ```
> ```bash
> php artisan migrate
> ```

3. Publish Assets **(Required)**
> ```bash
> php artisan vendor:publish --tag="error-lens-assets"
> ```

4. Register middleware in your `app/Http/Kernel.php` **(Required)**
> ```php
> protected $middleware = [
>     // ...
>     \Narolalabs\ErrorLens\Middleware\ErrorLens::class,
> ];
> ```

5. Finally, set the configuration as `APP_ENV=production` and `APP_DEBUG=false` and clear the cache by below command.

> ```bash
> php artisan config:clear
> ```

## Additional Configurations

_To safeguard against unauthorized access, you can set or reset the username and password for authentication._ **(Highly Recommended but optional)**
> ```bash
> php artisan error-lens:authentication
> ```

_If you are not aware or confused about the setting configurations, you can publish the seeder and run it._ **(Optional)**
> ```bash
> php artisan vendor:publish --tag=error-lens-seeds
> ```
>
> ```bash
> php artisan db:seed --class=ErrorLensConfigurationSeeder
> ```

## Usage
To view all the error logs, visit the `https://domain.com/error-lens`

![Error Lens - Dashboard](https://github.com/narolalabs/error-lens/blob/v2.x/resources/dist/assets/readme-images/Dashboard.png)

![Error Lens - Error modal view](https://github.com/narolalabs/error-lens/blob/v2.x/resources/dist/assets/readme-images/ErrorInDrawer.png)

![Error Lens - Full page error view](https://github.com/narolalabs/error-lens/blob/v2.x/resources/dist/assets/readme-images/ErrorInFullPage.png)

![Error Lens - Configuration](https://github.com/narolalabs/error-lens/blob/v2.x/resources/dist/assets/readme-images/Config.png)


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