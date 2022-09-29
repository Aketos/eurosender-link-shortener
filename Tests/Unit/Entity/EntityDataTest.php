<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Domain\EntityData;
use PHPUnit\Framework\TestCase;

class EntityDataTest extends TestCase
{

    private EntityData $subject;
    private array $userData;

    public function setUp(): void
    {
        $this->userData = [
            'id' => 'id',
            'name' => 'name',
            'firstname' => 'firstname',
            'email' => 'email',
            'address' => 'address'
        ];

        $this->subject = new EntityData(
            $this->userData['id'],
            $this->userData
        );
    }

    public function testItGetsId(): void
    {
        self::assertEquals('id', $this->subject->getId());
    }

    public function testItGetsData(): void
    {
        self::assertEquals($this->userData, $this->subject->getData());
    }
}