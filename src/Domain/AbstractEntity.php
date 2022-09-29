<?php

declare(strict_types=1);

namespace App\Domain;

class AbstractEntity implements EntityInterface
{
    public const TYPE = '';

    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this::TYPE;
    }
}
