# Check your application for broken links

[![GitHub Tests Action Status](https://github.com/lobotomised/laravel-autocrawler/actions/workflows/run-test.yml/badge.svg)](https://github.com/lobotomised/laravel-autocrawler/actions/workflows/run-test.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lobotomised/laravel-autocrawler.svg?style=flat-square)](https://packagist.org/packages/lobotomised/laravel-autocrawler)
[![Latest Stable Version](https://img.shields.io/packagist/v/lobotomised/laravel-autocrawler)](https://packagist.org/packages/lobotomised/laravel-autocrawler)
[![License](https://img.shields.io/packagist/l/lobotomised/laravel-autocrawler)](https://packagist.org/packages/lobotomised/laravel-autocrawler)

Using this package you can check if your application have broken links.

```bash
php artisan crawl
200 OK - http://myapp.test/ 
200 OK - http://myapp.test/login found on http://myapp.test/
200 OK - http://myapp.test/register found on http://myapp.test/
301 301 Moved Permanently - http://myapp.test/homepage found on http://myapp.test/register
404 Not Found - http://myapp.test/brokenlink found on http://myapp.test/register
200 OK - http://myapp.test/features found on http://myapp.test/


Crawl finished

Results:
Status 200: 4 founds
Status 301: 1 found
Status 404: 1 found
```

## Installation
This package can be installed via Composer:

``` bash
composer require --dev lobotomised/laravel-autocrawler
```

When crawling your site, it will automatically detect the url your application is using. If instead it scan http://localhost, check in your .env you properly configure the APP_URL variable
``` dotenv
APP_URL="http://myapp.test"
``` 

## Working with GitHub actions
To execute the crawler you first need to start a web server. You can choose to install apache or nginx. 
Here is an example using the php build-in webserver
```
steps:
  - uses: actions/checkout@v3
  - name: Prepare The Environment
    run: cp .env.example .env
  - name: Install Composer Dependencies
    run: composer install
  - name: Generate Application Key
    run: php artisan key:generate
  - name: Install npm Dependencies
    run: npm ci
  - name: Compile assets
    run: npm run build

  - name: Start php build-in webserver
    run: (php artisan serve &) || /bin/true

  - name: Crawl website
    run: php artisan crawl --url=http://localhost:8000/ --fail-on-error
``` 

## Documentation

All documentation is available with the command:
```bash
php artisan crawl --help
```

## Alternatives
This package is heavily inspire by [spatie/http-status-check](https://github.com/spatie/http-status-check), but instead of being a project dependency, it is a global installation

## Testing

First we need to start the included node http server in a separate terminal.
```bash
make start
```
Then to run the tests:
```bash
make test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
