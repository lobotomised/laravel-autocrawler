<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawler\Tests;

use Lobotomised\Autocrawler\AutocrawlerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            AutocrawlerServiceProvider::class,
        ];
    }
}