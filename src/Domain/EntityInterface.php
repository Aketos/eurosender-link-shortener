<?php

declare(strict_types=1);

namespace App\Domain;

interface EntityInterface
{
    public function getId(): string;

    public function getType(): string;
}