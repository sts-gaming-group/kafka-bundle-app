<?php

declare(strict_types=1);

namespace App\Consumer;

use App\DTO\HealthCheck;
use Psr\Log\LoggerInterface;
use Sts\KafkaBundle\Client\Consumer\Message;
use Sts\KafkaBundle\Client\Contract\ConsumerInterface;
use Sts\KafkaBundle\Client\Producer\ProducerClient;
use Sts\KafkaBundle\Exception\KafkaException;
use Sts\KafkaBundle\Exception\NullMessageException;
use Sts\KafkaBundle\RdKafka\Context;

class HealthCheckConsumer implements ConsumerInterface
{
    private ProducerClient $producerClient;
    private LoggerInterface $logger;

    public function __construct(ProducerClient $producerClient, LoggerInterface $logger)
    {
        $this->producerClient = $producerClient;
        $this->logger = $logger;
    }

    public function consume(Message $message, Context $context): void
    {
        $time = $message->getData();

        $this->logger->info(sprintf('Consumer: got message with time %s', $time));
        $this->logger->info('Consumer: waiting 10 seconds.');

        sleep(10);
    }

    public function handleException(KafkaException $exception, Context $context): void
    {
        $throwable = $exception->getThrowable();
        if ($throwable instanceof NullMessageException) {
            $this->logger->info('Consumer: no more messages available. Let\'s produce one.');

            $healthCheck = new HealthCheck(new \DateTime());
            $this->producerClient->produce($healthCheck)->flush();

            return;
        }

        $this->logger->emergency($throwable->getMessage());
    }

    public function getName(): string
    {
        return 'health_check';
    }
}
