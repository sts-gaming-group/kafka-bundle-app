<?php

declare(strict_types=1);

namespace App\Producer;

use App\DTO\UserRegistered;
use Psr\Log\LoggerInterface;
use StsGamingGroup\KafkaBundle\Client\Contract\CallableInterface;
use StsGamingGroup\KafkaBundle\Client\Contract\PartitionAwareProducerInterface;
use StsGamingGroup\KafkaBundle\Client\Producer\Message;
use StsGamingGroup\KafkaBundle\Configuration\ResolvedConfiguration;

class UserRegisteredProducer implements PartitionAwareProducerInterface, CallableInterface
{
    use LoggableCallbacks;

    public function __construct(private LoggerInterface $logger, private int $userRegisteredTopicPartitionsNo)
    {
    }

    public function produce($data): Message
    {
        /** @var $data UserRegistered */
        return new Message(
            json_encode($data->toArray(), JSON_THROW_ON_ERROR)
        );
    }

    public function supports($data): bool
    {
        return $data instanceof UserRegistered;
    }

    public function getPartition($data, ResolvedConfiguration $configuration): int
    {
        /** @var $data UserRegistered */
        return $data->getClientId() % $this->userRegisteredTopicPartitionsNo;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
