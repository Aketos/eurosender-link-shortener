<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Domain\EntityData;
use App\Domain\Link;
use App\Domain\Tracker;
use Carbon\Carbon;

trait EntityTestHelperTrait
{
    public function createLink(int $index = null): Link
    {
        return new Link(
            'id' . $index ?? '',
            'shortenedLink' . $index ?? '',
            'link' . $index ?? ''
        );
    }

    public function createTracker(int $index = null): Tracker
    {
        return new Tracker(
            'id' . $index ?? '',
            'shortenedLink' . $index ?? '',
            'link' . $index ?? '',
            Carbon::now()
        );
    }

    public function createLinkEntityData(int $index = null): EntityData
    {
        return new EntityData(
            'id' . $index ?? '',
            [
                'id' => 'id' . $index ?? '',
                'shortenedLink' => 'shortenedLink' . $index ?? '',
                'link' => 'link' . $index ?? ''
            ]
        );
    }

    public function createTrackerEntityData(int $index = null): EntityData
    {
        return new EntityData(
            'id' . $index ?? '',
            [
                'id' => 'id' . $index ?? '',
                'shortenedLink' => 'shortenedLink' . $index ?? '',
                'link' => 'link' . $index ?? '',
                'redirectedAt' => Carbon::now()
            ]
        );
    }
}