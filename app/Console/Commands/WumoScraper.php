<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WumoScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:wumo';

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

    public function handle() {
        $client = new Client(['verify'=> false]);
        $bar = $this->output->createProgressBar(1);
        $bar->start();
        $baseUrl = 'https://wumo.com';
        $url = '/wumo';
        for($i=1;$i<10;$i++) {
            $bar->advance();
            if(Cache::has('wumo-'. $url)){
                $html = Cache::get('wumo-'. $url);
            } else {
                $response = $client->get($baseUrl . $url);
                $html = $response->getBody()->getContents();
                Cache::put('wumo-'. $url, $html);
            }
            $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
            $imgEl = $crawler->filter('.box-content>a>img');
            var_dump($baseUrl . $imgEl->attr('src'));
            $link = $crawler->filter('.prev');
            $url = $link->attr('href');
//            var_dump($imgEl->attr('title'));
//            var_dump($imgEl->attr('alt'));
        }
        $bar->finish();
    }
}
