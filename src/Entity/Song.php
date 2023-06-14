<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $text = [];

    #[ORM\Column(length: 255)]
    private ?string $album = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $composer = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $singer = [];

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Song
    {
        $this->name = $name;
        return $this;
    }

    public function getText(): array
    {
        return $this->text;
    }

    public function setText(array $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): static
    {
        $this->album = $album;

        return $this;
    }

    public function getComposer(): array
    {
        return $this->composer;
    }

    public function setComposer(array $composer): static
    {
        $this->composer = $composer;

        return $this;
    }

    public function getSinger(): array
    {
        return $this->singer;
    }

    public function setSinger(array $singer): static
    {
        $this->singer = $singer;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
