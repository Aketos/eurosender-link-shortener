<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Domain\Company;
use App\Domain\EntityCollection;
use App\Domain\Link;
use App\Tests\Traits\EntityTestHelperTrait;
use PHPUnit\Framework\TestCase;

class EntityCollectionTest extends TestCase
{
    use EntityTestHelperTrait;

    private Link $link1;
    private Link $link2;
    private EntityCollection $subject;

    public function setUp(): void
    {
        $this->link1 = $this->createLink(1);
        $this->link2 = $this->createLink(2);

        $this->subject = (new EntityCollection())
            ->add($this->link1)
            ->add($this->link2);
    }

    public function testItGetsEntity(): void
    {
        self::assertEquals($this->link1, $this->subject->get('id1'));
        self::assertEquals($this->link2, $this->subject->get('id2'));
    }

    public function testItGetsAllEntities(): void
    {
        $entities = $this->subject->all();

        self::assertCount(2, $entities);
        self::assertEquals(
            [
                $this->link1->getId() => $this->link1,
                $this->link2->getId() => $this->link2
            ],
            $entities
        );
    }
}