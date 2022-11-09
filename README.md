# KingsCode DevTools Package

[![Packagist](https://img.shields.io/packagist/v/patrickvandewal/kc-dev-tools.svg?colorB=brightgreen)](https://packagist.org/packages/patrickvandewal/kc-dev-tools)
[![Packagist](https://img.shields.io/packagist/dt/patrickvandewal/kc-dev-tools.svg?colorB=brightgreen)](https://packagist.org/packages/patrickvandewal/kc-dev-tools)
[![license](https://img.shields.io/github/license/patrickvandewal/kc-dev-tools.svg?colorB=brightgreen)](https://github.com/patrickvandewal/kc-dev-tools)

## Installation

Require the package.

```
composer require patrickvandewal/kc-dev-tools
```

Optionally, publish the service provider

```
    php artisan vendor:publish --provider="KingsCode\DevTools\DevToolsServiceProvider"
```

Add the dev-tools overview page to the `VerifyCsrfToken` class to the exceptions

```
    protected $except = [
        ...
        'dev-tools/*'
    ];
```
