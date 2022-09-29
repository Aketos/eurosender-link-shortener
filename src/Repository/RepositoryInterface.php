<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\EntityCollection;
use App\Domain\EntityInterface;

interface RepositoryInterface
{
    public function find(string $id): EntityInterface;

    public function findAll(): EntityCollection;

    public function save(EntityInterface $entity): void;

    public function delete(EntityInterface $entity): void;
}
