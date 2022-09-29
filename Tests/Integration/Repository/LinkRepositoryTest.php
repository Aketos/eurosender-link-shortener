<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Domain\EntityCollection;
use App\Domain\Link;
use App\Repository\Connector\ConnectorInterface;
use App\Repository\LinkRepository;
use App\Tests\Traits\EntityTestHelperTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LinkRepositoryTest extends KernelTestCase
{
    use EntityTestHelperTrait;

    private Link $link1;
    private Link $link2;

    /** @var MockObject|ConnectorInterface|mixed */
    private $mockConnector;

    private LinkRepository $subject;

    public function setUp(): void
    {
        $this->link1 = $this->createLink(1);
        $this->link2 = $this->createLink(2);

        $this->mockConnector = $this->createMock(ConnectorInterface::class);

        $linkRepository = new LinkRepository(
            $this->mockConnector,
            static::getContainer()->get(NormalizerInterface::class)
        );

        static::getContainer()->set(LinkRepository::class, $linkRepository);

        $this->subject = static::getContainer()->get(LinkRepository::class);
    }

    public function testItSaves(): void
    {
        $this->mockConnector->expects(self::once())->method('save')
            ->with($this->link1);

        $this->subject->save($this->link1);
    }

    public function testItFindsOne(): void
    {
        $this->mockConnector->expects(self::once())->method('get')
            ->with('id1', Link::TYPE)
            ->willReturn($this->createLinkEntityData(1));

        self::assertEquals($this->link1, $this->subject->find('id1'));
    }

    public function testItFindsAll(): void
    {
        $this->mockConnector->expects(self::once())->method('getCollection')
            ->willReturn(
                [
                    $this->createLinkEntityData(1),
                    $this->createLinkEntityData(2),
                ],
            );

        $collection = (new EntityCollection())
            ->add($this->link1)
            ->add($this->link2);

        self::assertEquals($collection, $this->subject->findAll());
    }

    public function testItDeletes(): void
    {
        $this->mockConnector->expects(self::once())->method('delete')
            ->with($this->link1);

        $this->subject->delete($this->link1);
    }
}