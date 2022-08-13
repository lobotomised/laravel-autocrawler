<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawl;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\Console\Output\OutputInterface;
use function Termwind\render;

class CrawlerObserver extends CrawlObserver
{
    protected array $crawledUrls = [];
    private ?string $filename = null;

    public function __construct(private OutputInterface $consoleOutput)
    {
    }

    public function setOutputFile(string $filename): void
    {
        $this->filename = $filename;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $this->addResult($url, $foundOnUrl, $response->getStatusCode(), $response->getReasonPhrase());
        // TODO: Implement crawled() method.
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        // TODO: Implement crawlFailed() method.
    }

    public function finishedCrawling(): void
    {
        // TODO: Implement finishedCrawling() method.
    }

    private function addResult(UriInterface $url, ?UriInterface $foundOnUrl, int $code, string $reason): void
    {
        if (isset($this->crawledUrls[$code]) && in_array($url, $this->crawledUrls[$code])) {
            return;
        }

        $this->crawledUrls[$code][] = $url;


        $colorTag = $this->getColorTag($code);

        $date = date('Y-m-d H:i:s');

        $message = "$code $reason -  " . $url . " found on $foundOnUrl";

        $this->consoleOutput->writeln("<$colorTag> [$date] $message</$colorTag>");

        if($this->filename && $colorTag === 'error') {
            file_put_contents($this->filename, $message . PHP_EOL, FILE_APPEND);
        }
    }

    private function getColorTag(int $code): string
    {
        if(str_starts_with((string) $code, '2')){
            return 'info';
        }

        if(str_starts_with((string) $code, '3')){
            return 'comment';
        }

        return 'error';
    }
}


