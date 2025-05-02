<?php

namespace App\Command;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\ScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:calculate-scoring',
    description: 'Рассчитать скоринг по всем или одному клиенту',
)]

class CalculateScoringCommand extends Command
{
    public function __construct(
        readonly ClientRepository $clientRepository,
        readonly ScoringService $scoringService,
        readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::OPTIONAL, 'ID клиента (если не указан — расчет по всем)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');

        if ($id) {
            $client = $this->clientRepository->find($id);
            if (!$client) {
                $output->writeln("<error>Клиент с ID $id не найден.</error>");
                return Command::FAILURE;
            }

            $this->processClient($client, $output);
        } else {
            $clients = $this->clientRepository->findAll();
            foreach ($clients as $client) {
                $this->processClient($client, $output);
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }

    private function processClient(Client $client, OutputInterface $output): void
    {
        $result = $this->scoringService->calculate($client);
        $client->setScoring($result->getTotal());

        $output->writeln("Клиент ID {$client->getId()} ({$client->getFirstName()} {$client->getLastName()}):");
        foreach ($result->getBreakdown() as $rule => $points) {
            $output->writeln("  - $rule: $points");
        }
        $output->writeln("  >> Общий скоринг: {$result->getTotal()}\n");
    }
}