<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Lobotomised\Autocrawler\Tests\TestCase;

uses(TestCase::class)
    ->in(__DIR__);

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function expectToLog(): void
{
    $result = (new Filesystem)->isFile(storage_path('autocrawler/output.txt'));

    expect($result)->toBeTrue();
}

function expectToNotLog(): void
{
    $result = (new Filesystem)->isFile(storage_path('autocrawler/output.txt'));

    expect($result)->toBeFalse();
}
