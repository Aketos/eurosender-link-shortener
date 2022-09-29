<?php

declare(strict_types=1);

namespace App\Repository\Connector;

use App\Domain\EntityData;
use App\Domain\EntityInterface;
use App\Repository\Exception\EntityNotFoundException;
use LogicException;
use PDOException;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\MysqlException;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class MysqlConnector implements ConnectorInterface
{
    private Mysql $client;
    private SerializerInterface $serializer;


    public function __construct(Mysql $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function save(EntityInterface $entity): void
    {
        $normalizedEntity = $this->serializer->normalize($entity);

        try {
            $this->client->insert($entity->getType(), $normalizedEntity);
        } catch (Throwable $exception) {
            if ($exception instanceof PDOException && $exception->getCode() === '23000') {
                $this->client->update($entity->getType(), ['id' => $entity->getId()], $normalizedEntity);
                return;
            }

            throw new ConnectorException($exception->getMessage());
        }
    }

    public function get(string $id, string $type): EntityData
    {
        try {
            $data = $this->client->fetchRow(
                sprintf('SELECT * FROM %s WHERE id = :id', $type),
                ['id' => $id]
            );

            if ($data === null) {
                throw new EntityNotFoundException(sprintf(
                    'Entity of type %s not found with id %s',
                    $type,
                    $id
                ));
            }

            return new EntityData($id, $data);
        } catch (MysqlException $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }

    public function getCollection(string $type): iterable
    {
        try {
            $searchResult = $this->client->fetchRowMany(sprintf('SELECT * FROM %s', $type));

            if ($searchResult === null) {
                return [];
            }

            foreach ($searchResult as $data) {
                yield new EntityData($data['id'], $data);
            }

        } catch (Throwable $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }

    public function delete(EntityInterface $entity): void
    {
        try {
            $this->client->delete($entity->getType(), ['id' => $entity->getId()]);
        } catch (Throwable $exception) {
            throw new ConnectorException($exception->getMessage());
        }
    }
}
