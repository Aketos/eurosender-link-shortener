<?php

declare(strict_types=1);

namespace App\Domain;

class EntityCollection
{
    /** @var EntityInterface[] */
    private array $entities = [];

    public function add(EntityInterface $entity): self
    {
        $this->entities[$entity->getId()] = $entity;

        return $this;
    }

    public function get(string $id): ?EntityInterface
    {
        return $this->entities[$id] ?? null;
    }

    public function all(): array
    {
        return $this->entities;
    }
}