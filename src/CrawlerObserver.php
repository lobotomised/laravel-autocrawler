<?php

declare(strict_types=1);

namespace Lobotomised\Autocrawler;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlerObserver extends CrawlObserver
{
    /** @var array<int, array<int, UriInterface>> */
    protected array $crawledUrls = [];
    private Filesystem $files;
    private bool $shouldOutput = false;

    private const DIRECTORY = 'autocrawler';

    public function __construct(private OutputInterface $consoleOutput)
    {
        $this->files = new Filesystem();
        $this->createLogDirectory();
    }

    public function shouldOutput(bool $shouldOutput): void
    {
        $this->shouldOutput = $shouldOutput;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $this->addResult($url, $foundOnUrl, $response->getStatusCode(), $response->getReasonPhrase());
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        $response = $requestException->getResponse();

        $this->addResult($url, $foundOnUrl, $response->getStatusCode(), $response->getReasonPhrase());
    }

    public function finishedCrawling(): void
    {
        $this->consoleOutput->writeln("\n<info>Crawl finished</info>");
        $this->consoleOutput->writeln("\n<info>Results:</info>");

        foreach($this->crawledUrls as $code => $urls) {
            $count = count($urls);
            $txt = $count > 1 ? 'founds' : 'found';
            $colorTag = $this->getColorTag($code);

            $this->consoleOutput->writeln("<$colorTag>Status $code: $count $txt</$colorTag>");
        }
    }

    /**
     * @return array<int, array<int, UriInterface>>
     */
    public function result(): array
    {
        return $this->crawledUrls;
    }

    private function addResult(UriInterface $url, ?UriInterface $foundOnUrl, int $code, string $reason): void
    {
        if (isset($this->crawledUrls[$code]) && in_array($url, $this->crawledUrls[$code])) {
            return;
        }

        $this->crawledUrls[$code][] = $url;


        $colorTag = $this->getColorTag($code);

        $date = date('Y-m-d H:i:s');

        $message = "$code $reason -  $url ";
        if($foundOnUrl) {
            $message .= " found on $foundOnUrl";
        }

        $this->consoleOutput->writeln("<$colorTag> [$date] $message</$colorTag>");

        if($this->shouldOutput && $colorTag === 'error') {
            $this->log($message);
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

    private function createLogDirectory(): void
    {
        $dir_path = storage_path(self::DIRECTORY);

        if(! $this->files->isDirectory( $dir_path )) {
            if($this->files->makeDirectory($dir_path)) {
                $this->files->put($dir_path . DIRECTORY_SEPARATOR . '.gitignore', "*\n!.gitignore\n");
            } else {
                throw new \Exception("Cannot create directory 'self::DIRECTORY'");
            }

        }

        $this->files->delete($dir_path . DIRECTORY_SEPARATOR . 'output.txt');
    }

    private function log(string $message): void
    {
        $this->files->put(storage_path(self::DIRECTORY) . DIRECTORY_SEPARATOR . 'output.txt', $message);
    }
}
