<?php

declare(strict_types=1);

use function Pest\Laravel\artisan;

it('dont log 200 status code', function () {
    artisan('crawl', [
        '--output' => true,
        '--url' => 'http://localhost:8080/200',
    ]);

    expectToNotLog();
});

it('dont log 3** status code', function () {
    artisan('crawl', [
        '--output' => true,
        '--url' => 'http://localhost:8080/301',
    ])->run();

    expectToNotLog();
});

it('log 4** error', function () {
    artisan('crawl', [
        '--output' => true,
        '--url' => 'http://localhost:8080/404',
    ])->run();

    expectToLog();
});

it('fail on error', function () {
    artisan('crawl', [
        '--url' => 'http://localhost:8080/404',
        '--fail-on-error' => true,
    ])->assertFailed();
});

it('dont fail on success', function () {
    artisan('crawl', [
        '--url' => 'http://localhost:8080/200',
        '--fail-on-error' => true,
    ])->assertSuccessful();
});
