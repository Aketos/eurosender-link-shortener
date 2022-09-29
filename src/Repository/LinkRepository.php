<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Link;
use App\Domain\EntityCollection;
use App\Domain\EntityData;
use App\Domain\EntityInterface;
use App\Repository\Connector\ConnectorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LinkRepository implements RepositoryInterface
{
    private ConnectorInterface $connector;
    
    private SerializerInterface $serializer;

    public function __construct(
        ConnectorInterface $connector,
        SerializerInterface $serializer
    ) {
        $this->connector = $connector;
        $this->serializer = $serializer;
    }

    public function find(string $id): Link
    {
        return $this->serializer->denormalize(
            $this->connector->get($id, Link::TYPE)->getData(),
            Link::class
        );
    }

    public function findAll(): EntityCollection
    {
        $collection = new EntityCollection();

        /** @var EntityData $companyData */
        foreach ($this->connector->getCollection(Link::TYPE) as $companyData) {
            $collection->add($this->serializer->denormalize($companyData->getData(), Link::class));
        }

        return $collection;
    }

    public function save(EntityInterface $entity): void
    {
        $this->connector->save($entity);
    }

    public function delete(EntityInterface $entity): void
    {
        $this->connector->delete($entity);
    }
}