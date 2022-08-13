<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawl;

use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler as SpatieCrawler;
use Spatie\Crawler\CrawlProfiles\CrawlAllUrls;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Symfony\Component\Console\Output\OutputInterface;

class Crawler
{
    private OutputInterface $consoleOutput;
    private UriInterface $baseUrl;
    private int $concurrency = 10;
    private bool $ignore_robots = false;
    private CrawlProfile $crawlProfile;
    private ?string $outputFile = null;

    protected static array $defaultClientOptions = [
        RequestOptions::COOKIES => true,
        RequestOptions::CONNECT_TIMEOUT => 10,
        RequestOptions::TIMEOUT => 10,
        RequestOptions::ALLOW_REDIRECTS => [
            'track_redirects' => true,
        ],
    ];

    public function setOutput(OutputInterface $output): self
    {
        $this->consoleOutput = $output;

        return $this;
    }

    public function setUrl(UriInterface|string $url): self
    {
        if(! $url instanceof UriInterface) {
            $url = new Uri($url);
        }
        
        $this->baseUrl = $url;
        
        return $this;
    }
    
    public function setConcurrency(int $concurrency): self
    {
        $this->concurrency = $concurrency;
        
        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        self::$defaultClientOptions[RequestOptions::TIMEOUT] = $timeout;

        return $this;
    }

    public function setIgnoreRobots(bool $ignore_robots): self
    {
        $this->ignore_robots = $ignore_robots;

        return $this;
    }

    public function setCrawlProfile(bool $crawlProfile): self
    {
        if($crawlProfile) {
            $this->crawlProfile = new CrawlInternalUrls($this->baseUrl);

            return $this;
        }

        $this->crawlProfile = new CrawlAllUrls();

        return $this;
    }

    public function startCrawling(): void
    {
        $observer = new CrawlerObserver($this->consoleOutput);

        if($this->outputFile) {
            $observer->setOutputFile($this->outputFile);
        }

        $crawler = SpatieCrawler::create([])
            ->setCrawlObserver($observer)
            ->setConcurrency($this->concurrency)
            ->setCrawlProfile($this->crawlProfile);

        $this->ignore_robots
            ? $crawler->ignoreRobots()
            : $crawler->respectRobots();


        $crawler->startCrawling($this->baseUrl);
    }

    public function setOutputToFile(?string $file): self
    {
        if($file) {
            $this->outputFile = $file;
        }

        return $this;
    }
}