<?php

declare(strict_types=1);

namespace App\Service;

use DOMDocument;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author  Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2023 DocCheck Community GmbH
 */
class SongCollector
{
    private const BASE_URL = 'http://www.die-beste-band-der-welt.de/texte/';

    public function __construct(private HttpClientInterface $client,) {
    }

    /**
     * @return string[]
     */
    public function getSongUrls(): array
    {
        $url = self::BASE_URL.'texte.htm#a';

        try {
            $response = $this->client->request(
                'GET',
                $url
            );
            $crawler = new Crawler($response->getContent());
            $linkElements = $crawler->filter('a');
            $links = [];

            $linkElements->each(function (Crawler $node, $i) use (&$links) {
                $link = $node->attr('href');
                $links[] = $link;
            });
            return $links;
        } catch (TransportExceptionInterface $e) {
        }
        return [];
    }

    public function getTextFromUrl(string $songUrl, SongData $songData): ?SongData
    {
        try {
            $response = $this->client->request(
                'GET',
                self::BASE_URL.$songUrl
            );

            $crawler = new Crawler($response->getContent());

            $title = "";
            try {
                $titleElement = $crawler->filter('title')->first();
                $title = $titleElement->text();
            } catch (\Exception $e) {
            }

            if ($title === ""){
                preg_match('~\/(.*).htm~', $songUrl, $matches);
                if ($matches){
                    $title =  trim($matches[1]);
                }
            }

            $songData->setName($title);



            $paragraphs = $crawler->filter('p');
            $textArray = [];

            foreach ($paragraphs as $paragraph) {
                $text = trim(preg_replace('/\s+/', ' ', $paragraph->nodeValue));
                $textArray[] = $text;
            }

            $songData->setSinger($this->extractSinger($textArray[0]));
            $songData->setComposer($this->extractComposer($textArray[0]));

            $album = $this->extractAlbum($textArray[count($textArray)-1]);
            if ($album === ""){
                preg_match('~(.*)\/~', $songUrl, $matches);
                if ($matches){
                    $album =  trim($matches[1]);
                }
            }
            $songData->setAlbum($album);
            unset($textArray[count($textArray)-1]);
            unset($textArray[0]);
            $textArray = array_values($textArray);

            $songData->setText($textArray);

            return $songData;
        } catch (TransportExceptionInterface $e) {
        }
        return null;
    }

    private function extractSinger(string $composerLine): array
    {
        preg_match('~.*M:(.*)\/.*~', $composerLine, $matches);
        if ($matches){
            return explode('-', trim($matches[1]));
        }
        return [];
    }

    private function extractAlbum(string $albumLine)
    {
        preg_match('~.*vom Album \"(.*?)\".*~', $albumLine, $matches);
        if ($matches){
            return trim($matches[1]);
        }
        return '';
    }

    private function extractComposer(string $composerLine): array
    {
        preg_match('~.*\/T:(.*)\).*~', $composerLine, $matches);
        if ($matches){
            return explode('-', trim($matches[1]));
        }
        return [];
    }
}
