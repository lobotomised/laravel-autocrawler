<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawler\Commands;

use Illuminate\Console\Command;
use Lobotomised\Autocrawler\Crawler;
use phpDocumentor\Reflection\Types\Self_;

class CrawlerCommand extends Command
{
    public $signature = 'crawl  {--u|url= : Url to crawl. Default your current laravel app}
                                {--c|concurrency=10 : Number of concurrent connections}
                                {--t|timeout=30 : Max duration of a request}
                                {--r|ignore-robots : Should ignore rules in the /robots.txt} 
                                {--e|ignore-external-links : Do not follow external links}
                                {--o|output : Write all non-2** ou non-3** to storage/autocrawler/output.txt}
                                {--i|interactive : Interactive ask your for the options}
                                {--fail-on-error : Command will fail if HTTP 400 or 500 codes are detected}';

    public $description = 'Crawl your own website and search failing status code';

    public function __construct(private Crawler $crawler)
    {
        parent::__construct();
    }

    public function handle(): int
    {

        $baseUrl = $this->option('url')
            ? $this->getOption('url')
            : config('app.url');

        if($this->option('interactive')) {
            $concurrency = (int) $this->ask('Number of concurrent connections ?', $this->getOption('concurrency'));
            $timeout = (int) $this->ask('Max duration of a request in seconds ?', $this->getOption('timeout'));
            $baseUrl = $this->ask('Url to crawl ?', $baseUrl);
            $output = $this->confirm('Write all non-2** ou non-3** to storage/autocrawler/output.txt', (bool)$this->option('output'));
            $ignore_robots = $this->confirm('Should ignore rules in the /robots.txt', (bool)$this->option('ignore-robots'));
            $ignore_external_links = $this->confirm('Ignore external links ?', (bool)$this->option('ignore-external-links'));
        }

        $result = $this->crawler->setUrl($baseUrl)
            ->setConsoleOutput($this->getOutput())
            ->shouldOutput($output ?? (bool)$this->option('output'))
            ->setConcurrency($concurrency ?? (int) $this->option('concurrency'))
            ->setTimeout($timeout ?? (int) $this->option('timeout'))
            ->setIgnoreRobots($ignore_robots ?? (bool)$this->option('ignore-robots'))
            ->setCrawlProfile($ignore_external_links ?? (bool)$this->option('ignore-external-links'))
            ->startCrawling()
        ;

        if($this->option('fail-on-error') && $result === false) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function getOption(string $option): ?string
    {
        $value = $this->option($option);

        if(is_string($value) || $value === null) {
            return $value;
        }

        return null;
    }
}