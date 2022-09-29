<?php

declare(strict_types=1);

namespace App\Action;

use App\Service\Link\LinkConversionService;
use App\Validator\ShortenLinkActionValidator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortenLinkAction
{
    private LinkConversionService $linkConversionService;

    private ShortenLinkActionValidator $actionValidator;
    private LoggerInterface $logger;

    public function __construct(
        ShortenLinkActionValidator $actionValidator,
        LinkConversionService $linkShortenService,
        LoggerInterface $logger
    ) {
        $this->linkConversionService = $linkShortenService;
        $this->actionValidator = $actionValidator;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $parameters = $this->actionValidator->getParameters($request);

        $link = $this->linkConversionService->createShortenLink($parameters['link']);

        $this->logger->info(sprintf(
            '[%s] created short link for %s',
            $link->getId(),
            $link->getLink()
        ));

        return new JsonResponse(
            $link->getShortenedLink(),
            Response::HTTP_OK
        );
    }
}
