<?php

declare(strict_types=1);

namespace App\Producer;

use App\DTO\HealthCheck;
use Psr\Log\LoggerInterface;
use Sts\KafkaBundle\Client\Contract\CallableInterface;
use Sts\KafkaBundle\Client\Contract\ProducerInterface;
use Sts\KafkaBundle\Client\Producer\Message;
use Sts\KafkaBundle\RdKafka\Callbacks;

class HealthCheckProducer implements ProducerInterface, CallableInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function produce($data): Message
    {
        /** @var $data HealthCheck */
        return new Message($data->getTimeFormatted());
    }

    public function callbacks(): array
    {
        $logger = $this->logger;

        return [
            Callbacks::MESSAGE_DELIVERY_CALLBACK => static function (
                \RdKafka\Producer $kafkaProducer,
                \RdKafka\Message $message
            ) use ($logger) {
                if ($message->err) {
                    $logger->emergency(sprintf('Producer: unable to produce message. Error %s', $message->err));

                    return;
                }

                $logger->info(sprintf(
                        'Producer: message produced. Payload %s | Partition %s',
                        $message->payload,
                        $message->partition)
                );
            }
        ];
    }

    public function supports($data): bool
    {
        return $data instanceof HealthCheck;
    }
}
