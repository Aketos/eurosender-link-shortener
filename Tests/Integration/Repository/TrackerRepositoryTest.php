<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Domain\EntityCollection;
use App\Domain\Tracker;
use App\Repository\Connector\ConnectorInterface;
use App\Repository\Connector\ElasticsearchConnector;
use App\Repository\TrackerRepository;
use App\Tests\Traits\EntityTestHelperTrait;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TrackerRepositoryTest extends KernelTestCase
{
    use EntityTestHelperTrait;

    private Tracker $tracker1;
    private Tracker $tracker2;

    /** @var MockObject|ConnectorInterface|mixed */
    private $mockConnector;

    private TrackerRepository $subject;

    public function setUp(): void
    {
        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d', '2021-01-01'));
        $this->tracker1 = $this->createTracker(1);
        $this->tracker2 = $this->createTracker(2);

        $this->mockConnector = $this->createMock(ElasticsearchConnector::class);

        $trackerRepository = new TrackerRepository(
            $this->mockConnector,
            static::getContainer()->get(NormalizerInterface::class)
        );

        static::getContainer()->set(TrackerRepository::class, $trackerRepository);

        $this->subject = static::getContainer()->get(TrackerRepository::class);
    }

    public function testItSaves(): void
    {
        $this->mockConnector->expects(self::once())->method('save')
            ->with($this->tracker1);

        $this->subject->save($this->tracker1);
    }

    public function testItFindsOne(): void
    {
        $this->mockConnector->expects(self::once())->method('get')
            ->with('id1', Tracker::TYPE)
            ->willReturn($this->createTrackerEntityData(1));

        self::assertEquals($this->tracker1, $this->subject->find('id1'));
    }

    public function testItFindsAll(): void
    {
        $this->mockConnector->expects(self::once())->method('getCollection')
            ->willReturn(
                [
                    $this->createTrackerEntityData(1),
                    $this->createTrackerEntityData(2),
                ],
            );

        $collection = (new EntityCollection())
            ->add($this->tracker1)
            ->add($this->tracker2);

        self::assertEquals($collection, $this->subject->findAll());
    }

    public function testItDeletes(): void
    {
        $this->mockConnector->expects(self::once())->method('delete')
            ->with($this->tracker1);

        $this->subject->delete($this->tracker1);
    }
}