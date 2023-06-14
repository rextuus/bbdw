<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Song;

/**
 * @author  Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2023 DocCheck Community GmbH
 */
class SongFactory
{

    public function createSong(): Song
    {
        return new Song();
    }

    public function mapData(Song $song, SongData $data): Song
    {
        $song->setName($data->getName());
        $song->setText($data->getText());
        $song->setUrl($data->getUrl());
        $song->setAlbum($data->getAlbum());
        $song->setComposer($data->getComposer());
        $song->setSinger($data->getSinger());

        return $song;
    }
}
