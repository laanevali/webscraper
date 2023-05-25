<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class NatalieDeeScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:ndee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $comicsWanted = $this->ask('How many comics do you need?');
        $comics = [];
        $client = new Client(['verify' => false]);
        $baseUrl = 'http://www.nataliedee.com';

        while (count($comics) < $comicsWanted) {
            $response = $client->get($baseUrl);
            $html = $response->getBody()->getContents();
            $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
            $imgEl = $crawler->filter('#butts>img');
            $comicsInPage = $imgEl->each(function (Crawler $cell) {
                return $cell->attr('src');
            });

            foreach ($comicsInPage as $oneComics) {
                if (count($comics) < $comicsWanted) {
                    var_dump($oneComics);
                    $comics[] = $oneComics;
                } else {
                    break;
                }
            }
        }
    }
}
