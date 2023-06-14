<?php

declare(strict_types=1);

namespace App\Service;

/**
 * @author  Wolfgang Hinzmann <wolfgang.hinzmann@doccheck.com>
 * @license 2023 DocCheck Community GmbH
 */
class SongData
{
    private string $name;
    private array $text;
    private string $url;
    private string $album;
    private array $composer;
    private array $singer;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SongData
    {
        $this->name = $name;
        return $this;
    }

    public function getText(): array
    {
        return $this->text;
    }

    public function setText(array $text): SongData
    {
        $this->text = $text;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): SongData
    {
        $this->url = $url;
        return $this;
    }

    public function getAlbum(): string
    {
        return $this->album;
    }

    public function setAlbum(string $album): SongData
    {
        $this->album = $album;
        return $this;
    }

    public function getComposer(): array
    {
        return $this->composer;
    }

    public function setComposer(array $composer): SongData
    {
        $this->composer = $composer;
        return $this;
    }

    public function getSinger(): array
    {
        return $this->singer;
    }

    public function setSinger(array $singer): SongData
    {
        $this->singer = $singer;
        return $this;
    }
}
