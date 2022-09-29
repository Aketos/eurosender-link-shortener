<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\EntityInterface;
use App\Domain\Link;
use App\Domain\Tracker;
use App\Repository\RepositoryInterface;
use App\Repository\LinkRepository;
use ReflectionClass;
use ReflectionProperty;
use Simplon\Mysql\Mysql;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class PrepareMysqlCommand extends Command
{
    protected static $defaultName = 'mysql:prepare';

    private Mysql $client;

    public function __construct(Mysql $client) {
        $this->client = $client;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Interact with Mysql database (create, initialize, purge etc)')
            ->addOption('create', 'c', InputOption::VALUE_NONE, 'Create the default database schema')
            ->addOption('purge', 'p', InputOption::VALUE_NONE, 'Purge the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('purge')) {
            $this->purge($io);
        }

        if ($input->getOption('create')) {
            $this->createTables($io);
        }

        return 0;
    }

    private function createTables(SymfonyStyle $io): void
    {
        $createQuery = 'CREATE TABLE IF NOT EXISTS links (
                         id varchar(254) PRIMARY KEY,
                         link varchar(254),
                         shortenedLink varchar(254),
                         INDEX (shortenedLink)
                         ) ENGINE=INNODB';

        $io->info(sprintf('Executing create query: %s', $createQuery));

        try {
            $this->client->executeSql($createQuery);
            $io->info('Table links created successfully');
        } catch (Throwable $exception) {
            $io->error(sprintf('Error creating table: %s', $exception->getMessage()));
        }
    }

    private function purge(SymfonyStyle $io): void
    {
        try {
            $this->client->executeSql('DELETE FROM links');
            $io->info('Table links purged successfully');
        } catch (Throwable $exception) {
            $io->error(sprintf('Error creating table: %s', $exception->getMessage()));
        }
    }
}
