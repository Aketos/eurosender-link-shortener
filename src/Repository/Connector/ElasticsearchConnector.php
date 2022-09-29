<?php

declare(strict_types=1);

namespace App\Repository\Connector;

use App\Domain\EntityData;
use App\Domain\EntityInterface;
use Elasticsearch\Client;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class ElasticsearchConnector implements ConnectorInterface
{
    private Client $client;

    private SerializerInterface $serializer;

    public function __construct(Client $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function save(EntityInterface $entity): void
    {
        try {
            $this->client->index([
                'index' => $entity->getType(),
                'id' => $entity->getId(),
                'body' => $this->serializer->serialize($entity, 'json')
            ]);
        } catch (Throwable $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }

    public function get(string $id, string $type): EntityData
    {
        try {
            $data = $this->client->get([
                'index' => $type,
                'id' => $id
            ]);

            return new EntityData($data['_id'], $data['_source']);
        } catch (Throwable $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }

    public function getCollection(string $type): iterable
    {
        try {
            $searchParams = [
                'index' => $type
            ];

            $searchResult = $this->client->search($searchParams);

            foreach ($searchResult['hits']['hits'] as $hit) {
                yield new EntityData($hit['_id'], $hit['_source']);
            }

        } catch (Throwable $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }

    public function delete(EntityInterface $entity): void
    {
        try {
            $this->client->delete([
                'index' => $entity->getType(),
                'id' => $entity->getId()
            ]);
        } catch (Throwable $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }
}
