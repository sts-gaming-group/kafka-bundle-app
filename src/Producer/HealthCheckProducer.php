<?php

declare(strict_types=1);

namespace App\Producer;

use App\DTO\HealthCheck;
use Psr\Log\LoggerInterface;
use StsGamingGroup\KafkaBundle\Client\Contract\CallableInterface;
use StsGamingGroup\KafkaBundle\Client\Contract\ProducerInterface;
use StsGamingGroup\KafkaBundle\Client\Producer\Message;

class HealthCheckProducer implements ProducerInterface, CallableInterface
{
    use LoggableCallbacks;

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function produce($data): Message
    {
        /** @var $data HealthCheck */
        return new Message($data->getTimeFormatted(), null);
    }

    public function supports($data): bool
    {
        return $data instanceof HealthCheck;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
