<?php

declare(strict_types=1);

namespace App\Service\Link;

use App\Domain\Link;
use App\Repository\LinkRepository;
use App\Repository\RepositoryInterface;

class LinkConversionService
{
    private LinkRepository $linkRepository;

    private string $applicationHost;

    public function __construct(LinkRepository $linkRepository, string $applicationHost)
    {
        $this->linkRepository = $linkRepository;
        $this->applicationHost = $applicationHost;
    }

    public function createShortenLink(string $longLink): Link
    {
        $uuid = bin2hex(random_bytes(4));

        $link = new Link(
            $uuid,
            sprintf('%s/%s',
                $this->applicationHost ,
                $uuid
            ),
            $longLink
        );

        $this->linkRepository->save($link);

        return $link;
    }
}
