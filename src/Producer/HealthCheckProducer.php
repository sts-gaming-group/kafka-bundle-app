<?php

declare(strict_types=1);

namespace App\Producer;

use App\DTO\HealthCheck;
use Psr\Log\LoggerInterface;
use StsGamingGroup\KafkaBundle\Client\Contract\CallableInterface;
use StsGamingGroup\KafkaBundle\Client\Contract\ProducerInterface;
use StsGamingGroup\KafkaBundle\Client\Producer\Message;
use StsGamingGroup\KafkaBundle\RdKafka\Callbacks;

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
        return new Message($data->getTimeFormatted(), null);
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

                $logger->notice(
                    sprintf(
                        'Producer: message produced. Payload %s | Partition %s | Waiting 10 seconds.',
                        $message->payload,
                        $message->partition
                    )
                );

                sleep(10);
            }
        ];
    }

    public function supports($data): bool
    {
        return $data instanceof HealthCheck;
    }
}
