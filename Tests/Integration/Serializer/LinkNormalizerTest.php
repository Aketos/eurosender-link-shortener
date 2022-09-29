<?php

declare(strict_types=1);

namespace App\Tests\Integration\Serializer;

use App\Domain\Link;
use App\Serializer\LinkNormalizer;
use App\Tests\Traits\EntityTestHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LinkNormalizerTest extends KernelTestCase
{
    use EntityTestHelperTrait;

    private Link $link;
    private LinkNormalizer $subject;

    public function setUp(): void
    {
        self::bootKernel();

        $this->link = $this->createLink();
        $this->subject = self::getContainer()->get(LinkNormalizer::class);
    }

    public function testItSupportsNormalization(): void
    {
        self::assertTrue($this->subject->supportsNormalization($this->link));
    }

    public function testItNormalizes(): void
    {
        self::assertEquals(
            $this->createLinkEntityData()->getData(),
            $this->subject->normalize($this->link)
        );
    }
}