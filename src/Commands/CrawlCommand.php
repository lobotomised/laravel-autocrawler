<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawl\Commands;

use Illuminate\Console\Command;
use Lobotomised\Autocrawl\Crawler;

class CrawlCommand extends Command
{
    public $signature = 'crawl  {--u|url= : Url to crawl. Default your current laravel app}
                                {--c|concurrency=10 : Number of concurrent connections}
                                {--t|timeout=30 : Max duration of a request}
                                {--r|ignore-robots : Should ignore rules in the /robots.txt} 
                                {--e|ignore-external-links : Do not follow external links}
                                {--o|output= : Write all non-2** ou non-3** to a file}
                                {--i|interactive : Interactive ask your for the options}
                                {--fail-on-error: Command will fail if HTTP 400 or 500 codes are detected}';

    public $description = 'Crawl your own website and search failing status code';

    public function __construct(private Crawler $crawler)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $baseUrl = $this->option('url') ?: config('app.url');

        if($this->option('interactive')) {
            $concurrency = (int) $this->ask('Number of concurrent connections ?', $this->option('concurrency'));
            $timeout = (int) $this->ask('Max duration of a request ?', $this->option('timeout'));
            $baseUrl = $this->ask('Url to crawl ?', $baseUrl);
            $output = $this->ask('Filename where we store all non-2** ou non-3** response. Leave blank to not write to a file', false);
            $ignore_robots = $this->confirm('Should ignore rules in the /robots.txt', $this->option('ignore-robots'));
            $ignore_external_links = $this->confirm('Ignore external links ?', $this->option('ignore-external-links'));
        }

        $this->crawler->setUrl($baseUrl)
            ->setOutput($this->getOutput())
            ->setOutputToFile($output ?? $this->option('output'))
            ->setConcurrency($concurrency ?? (int) $this->option('concurrency'))
            ->setTimeout($timeout ?? (int) $this->option('timeout'))
            ->setIgnoreRobots($ignore_robots ?? $this->option('ignore-robots'))
            ->setCrawlProfile($ignore_external_links ?? $this->option('ignore-external-links'))
            ->startCrawling();

        return 0;
    }
}