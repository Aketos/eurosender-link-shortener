<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Tracker;
use App\Repository\Exception\EntityNotFoundException;
use App\Repository\LinkRepository;
use App\Repository\TrackerRepository;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class RedirectLinkAction
{
    private LinkRepository $linkRepository;
    private TrackerRepository $trackerRepository;
    private LoggerInterface $logger;

    public function __construct(
        LinkRepository $linkRepository,
        TrackerRepository $trackerRepository,
        LoggerInterface $logger
    ) {
        $this->linkRepository = $linkRepository;
        $this->trackerRepository = $trackerRepository;
        $this->logger = $logger;
    }

    public function __invoke(string $id): RedirectResponse
    {
        try {
            $link = $this->linkRepository->find($id);
        } catch (EntityNotFoundException $exception) {
            throw new NotFoundHttpException(sprintf('Cannot find original link from code %s', $id));
        } catch (Throwable $exception) {
            throw new RuntimeException('Something went wrong');
        }

        try {
            $this->trackerRepository->save(new Tracker(
                $link->getId(),
                $link->getShortenedLink(),
                $link->getLink(),
                Carbon::now()
            ));
        } catch (Throwable $exception) {
            $this->logger->alert(sprintf(
                '[%s] Unable to track the redirection: %s',
                $link->getId(),
                $exception->getMessage()
            ));
            throw $exception;
        }

        $this->logger->info(sprintf(
            '[%s] Redirected to %s',
            $link->getId(),
            $link->getLink()
        ));

        return new RedirectResponse($link->getLink());
    }
}
