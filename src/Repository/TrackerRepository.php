<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Tracker;
use App\Domain\EntityCollection;
use App\Domain\EntityData;
use App\Domain\EntityInterface;
use App\Repository\Connector\ConnectorInterface;
use App\Repository\Connector\ElasticsearchConnector;
use Symfony\Component\Serializer\SerializerInterface;

class TrackerRepository implements RepositoryInterface
{
    private ElasticsearchConnector $connector;
    
    private SerializerInterface $serializer;

    public function __construct(
        ElasticsearchConnector $connector,
        SerializerInterface $serializer
    ) {
        $this->connector = $connector;
        $this->serializer = $serializer;
    }

    public function find(string $id): Tracker
    {
        return $this->serializer->denormalize(
            $this->connector->get($id, Tracker::TYPE)->getData(),
            Tracker::class
        );
    }

    public function findAll(): EntityCollection
    {
        $collection = new EntityCollection();

        /** @var EntityData $companyData */
        foreach ($this->connector->getCollection(Tracker::TYPE) as $companyData) {
            $collection->add($this->serializer->denormalize($companyData->getData(), Tracker::class));
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
