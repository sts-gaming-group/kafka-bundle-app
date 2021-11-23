<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\UserRegistered;
use StsGamingGroup\KafkaBundle\Client\Producer\ProducerClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserRegisteredProduceCommand extends Command
{
    protected static $defaultName = 'app:user-registered:produce';

    public function __construct(private ProducerClient $client, private int $userRegisteredTopicPartitionsNo)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Produces messages for user registered consumer.')
            ->addOption('count', null, InputOption::VALUE_REQUIRED, 'How many messages to produce.', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = (int) $input->getOption('count');

        for ($i = 1; $i <= $count; ++$i) {
            $clientId = $i % $this->userRegisteredTopicPartitionsNo;
            $userRegistered = new UserRegistered($clientId, new \DateTime());

            $this->client->produce($userRegistered);
        }

        $this->client->flush();

        return Command::SUCCESS;
    }
}
