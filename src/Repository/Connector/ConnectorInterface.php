<?php

declare(strict_types=1);

namespace App\Repository\Connector;

use App\Domain\EntityData;
use App\Domain\EntityInterface;

interface ConnectorInterface
{
    public function save(EntityInterface $entity): void;

    public function get(string $id, string $type): EntityData;

    public function getCollection(string $type): iterable;

    public function delete(EntityInterface $entity): void;
}