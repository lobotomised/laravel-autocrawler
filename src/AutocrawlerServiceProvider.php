<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawler;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lobotomised\Autocrawler\Commands\CrawlerCommand;

class AutocrawlerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CrawlerCommand::class,
            ]);
        }
    }

    protected function defineEnvironment(Application $app): void
    {
        $app['config']->set('app.url', 'http://localhost');
    }
}
