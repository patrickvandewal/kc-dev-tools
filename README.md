# KingsCode DevTools Package

[![Packagist](https://img.shields.io/packagist/v/patrickvandewal/kc-dev-tools.svg?colorB=brightgreen)](https://packagist.org/packages/patrickvandewal/kc-dev-tools)
[![Packagist](https://img.shields.io/packagist/dt/patrickvandewal/kc-dev-tools.svg?colorB=brightgreen)](https://packagist.org/packages/patrickvandewal/kc-dev-tools)
[![license](https://img.shields.io/github/license/patrickvandewal/kc-dev-tools.svg?colorB=brightgreen)](https://github.com/patrickvandewal/kc-dev-tools)

## Installation

Require the package. (Or as a `dev` package with the `--dev` flag)

```
composer require patrickvandewal/kc-dev-tools
```

Optionally, publish the service provider and views

```
php artisan vendor:publish --provider="KingsCode\DevTools\Providers\DevToolsServiceProvider"
```

Add the `DEV_TOOLS_PIN` key to your `.env` file

```
DEV_TOOLS_PIN=<your key to login>
```

Access the dev-tools at the following urls:
```
{environment-url}/dev-tools/overview
{environment-url}/dev-tools/joij/overview
```

