<?php

require __DIR__ . '/vendor/autoload.php';

if(!is_dir('./cache')){
    mkdir('./cache');
}

$client = new GuzzleHttp\Client(['verify'=> false]);
for($i=1;$i<10;$i++) {
    if(file_exists('./cache/'. $i .'.html')){
        $html = file_get_contents('./cache/'. $i .'.html');
    } else {
        var_dump("not cached");
        $response = $client->get('https://xkcd.com/' . $i);
        $html = $response->getBody()->getContents();
        file_put_contents('./cache/' . $i . '.html', $html);
    }
    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
    $imgEl = $crawler->filter('#comic img');
    var_dump($imgEl->attr('src'));
    var_dump($imgEl->attr('title'));
    var_dump($imgEl->attr('alt'));
}