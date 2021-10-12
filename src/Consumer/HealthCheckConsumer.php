<?php

declare(strict_types=1);

namespace App\Consumer;

use App\DTO\HealthCheck;
use Psr\Log\LoggerInterface;
use Sts\KafkaBundle\Client\Consumer\Exception\NullMessageException;
use Sts\KafkaBundle\Client\Consumer\Message;
use Sts\KafkaBundle\Client\Contract\ConsumerInterface;
use Sts\KafkaBundle\Client\Producer\ProducerClient;
use Sts\KafkaBundle\RdKafka\Context;

class HealthCheckConsumer implements ConsumerInterface
{
    public const NAME = 'health_check';

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

    public function handleException(\Exception $exception, Context $context): void
    {
        if ($exception instanceof NullMessageException) {
            $this->logger->info('Consumer: no more messages available. Let\'s produce one.');

            $this->producerClient
                ->produce(new HealthCheck())
                ->flush();

            return;
        }

        $this->logger->emergency($exception->getMessage());
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
