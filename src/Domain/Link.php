<?php

declare(strict_types=1);

namespace App\Domain;

class Link extends AbstractEntity
{
    public const TYPE = 'links';

    private ?string $link;

    private ?string $shortenedLink;

    public function __construct(string $id, string $shortenedLink = null, string $link = null)
    {
        $this->id = $id;
        $this->shortenedLink = $shortenedLink;
        $this->link = $link;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getShortenedLink(): ?string
    {
        return $this->shortenedLink;
    }

    public function setShortenedLink(?string $shortenedLink): self
    {
        $this->shortenedLink = $shortenedLink;
        return $this;
    }
}
