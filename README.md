# See, Understand, and Handle Laravel Errors using ErrorLens

[![Latest Version on Packagist](https://img.shields.io/packagist/v/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)
[![Total Downloads](https://img.shields.io/packagist/dt/narolalabs/error-lens.svg?style=flat-square)](https://packagist.org/packages/narolalabs/error-lens)

![ErrorLens](https://github.com/narolalabs/error-lens/blob/v2.x/resources/dist/assets/readme-images/Banner.png)


## Version Compatibility

 PHP      | 7.3, 7.4, 8.0, 8.1      |
:---------|-------------------------|
Laravel   | 8.x, 9.x, 10.x, 11x     |

## Installation

_Please adhere to the following instructions meticulously to successfully complete the installation process._

Package Installation **(Required)**
```bash
composer require narolalabs/error-lens
```

Install recommended migration, assets and seeder with single command. **(Required)**
```bash
php artisan error-lens:install
```

To safeguard against unauthorized access, you can set or reset the username and password for authentication. **(Highly recommended but optional)**
```bash
php artisan error-lens:authentication
```

Once you have completed the above steps, clear the cache using the following command.
```bash
php artisan config:clear
```
> **Important Instructions**: If you are installing in a development environment and the cache is not cleared, please re-run the `php artisan serve` command.

Finally, To view all the error logs, visit the **`https://domain.com/error-lens`**
<br>

## Upgrade

While you upgrade the package and wish to install recommended configurations, you can do that with a single command.
```bash
php artisan error-lens:update
```

## Additional Configurations

_In case you face any issues with the above installation and upgrade commands, you can resolve them by running the individual commands provided below._

Publish and run migration 
```bash
php artisan vendor:publish --tag="error-lens-migrations"
```
```bash
php artisan migrate
```

Publish Assets 
```bash
php artisan vendor:publish --tag="error-lens-assets" --force
```

If you are not aware or confused about the setting configurations, you can publish the seeder and run it.
```bash
php artisan vendor:publish --tag=error-lens-seeds
```
```bash
php artisan db:seed --class=ErrorLensConfigurationSeeder
```

## Screenshots

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