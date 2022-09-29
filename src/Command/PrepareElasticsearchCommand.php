<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Link;
use App\Domain\Tracker;
use App\Repository\LinkRepository;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PrepareElasticsearchCommand extends Command
{
    protected static $defaultName = 'elasticsearch:prepare';

    private const INDEXES = [
        'trackers' => Tracker::class
    ];

    private Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Initialize Elasticsearch indexes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->createIndexes($io);

        return 0;
    }

    private function createIndexes(SymfonyStyle $io): void
    {
        foreach (self::INDEXES as $index => $entityClass) {
            try {
                $this->client->indices()->create(['index' => $index]);
                $io->info(sprintf('Index %s successfully created', $index));
            } catch (\Throwable $e) {
                if ($e instanceof BadRequest400Exception) {
                    $io->warning(sprintf('Index %s already exists', $index));
                } else {
                    $io->error(sprintf('Unexpected error while creating index %s: %s', $index, $e->getMessage()));
                }
            }
        }
    }
}
