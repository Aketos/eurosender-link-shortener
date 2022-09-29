<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeInterface;

class Tracker extends AbstractEntity
{
    public const TYPE = 'trackers';

    private ?string $link;

    private ?string $shortenedLink;

    private DateTimeInterface $redirectedAt;

    public function __construct(string $id, string $shortenedLink, string $link, DateTimeInterface $redirectedAt)
    {
        $this->id = $id;
        $this->shortenedLink = $shortenedLink;
        $this->link = $link;
        $this->redirectedAt = $redirectedAt;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getShortenedLink(): ?string
    {
        return $this->shortenedLink;
    }

    public function getRedirectedAt(): DateTimeInterface
    {
        return $this->redirectedAt;
    }
}
