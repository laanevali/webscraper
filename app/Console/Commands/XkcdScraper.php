<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class XkcdScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:xkcd';

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
        $client = new Client(['verify'=> false]);
        $bar = $this->output->createProgressBar(6);
        $bar->start();
        for($i=1;$i<6;$i++) {
            $bar->advance();
            if(Cache::has('xkcd-'. $i)){
                $html = Cache::get('xkcd-'. $i);
            } else {
                $response = $client->get('https://xkcd.com/' . $i);
                $html = $response->getBody()->getContents();
                file_put_contents('./cache/' . $i . '.html', $html);
                Cache::put('xkcd-'. $i, $html);
            }
            $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
            $imgEl = $crawler->filter('#comic img');
            var_dump($imgEl->attr('src'));
//            var_dump($imgEl->attr('title'));
//            var_dump($imgEl->attr('alt'));
        }
        $bar->finish();
    }
}
