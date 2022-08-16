# Check your application for broken links

[![GitHub Tests Action Status](https://github.com/lobotomised/laravel-autocrawler/actions/workflows/run-test.yml/badge.svg)](https://github.com/lobotomised/laravel-autocrawler/actions/workflows/run-test.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lobotomised/laravel-autocrawler.svg?style=flat-square)](https://packagist.org/packages/lobotomised/laravel-autocrawler)
[![Latest Stable Version](https://img.shields.io/packagist/v/lobotomised/laravel-autocrawler)](https://packagist.org/packages/lobotomised/laravel-autocrawler)
[![License](https://img.shields.io/packagist/l/lobotomised/laravel-autocrawler)](https://packagist.org/packages/lobotomised/laravel-autocrawler)

Using this package you can check if your application have broken lins.

Here's an example where we'll monitor available disk space.

```bash
php artisan crawl
200 OK -  https://app.test/ 
200 OK -  https://app.test/login  found on https://app.test/
200 OK -  https://app.test/register  found on https://app.test/
301 301 Moved Permanently -  https://app.test/homepage  found on https://app.test/register
404 Not Found -  https://app.test/brokenlink  found on https://app.test/register
200 OK -  https://app.test/register  found on https://app.test/


Crawl finished

Results:
Status 200: 4 founds
Status 301: 1 found
Status 404: 1 found
```

## Installation
This package can be installed via Composer:

```bash
composer require --dev lobotomised/laravel-autocrawler
```

## Documentation

All documentation is available with the command:
```bash
php artisan crawl --help
```

## Alternatives
This package is heavily inspire by [spatie/http-status-check|https://github.com/spatie/http-status-check], but instead of being a project dependencies, it is a global installation

## Testing

First we need to start the included node http server in a separate terminal.
```bash
cd tests/server
npm install
node server.js
```
Then to run the tests:
```bash
make test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
