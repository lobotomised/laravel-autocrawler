<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawl;

use Illuminate\Support\ServiceProvider;
use Lobotomised\Autocrawl\Commands\CrawlCommand;

class AutocrawlServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            $this->commands([
                CrawlCommand::class
            ]);
        }
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.url', 'http://localhost');
    }
}