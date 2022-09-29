<?php

declare(strict_types=1);

namespace App\Factory;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticsearchClientFactory
{
    private string $host;

    private int $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function create(): Client
    {
        return ClientBuilder::fromConfig(
            [
                'hosts' => [
                    [
                        'host' => $this->host,
                        'port' => $this->port,
                    ]
                ]
            ]
        );
    }

}