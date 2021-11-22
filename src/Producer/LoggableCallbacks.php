<?php

declare(strict_types=1);

namespace App\Producer;

use Psr\Log\LoggerInterface;
use StsGamingGroup\KafkaBundle\RdKafka\Callbacks;

trait LoggableCallbacks
{
    abstract protected function getLogger(): LoggerInterface;

    public function callbacks(): array
    {
        $logger = $this->getLogger();

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
                        'Producer: message produced. Payload %s | Partition %s',
                        $message->payload,
                        $message->partition
                    )
                );
            }
        ];
    }
}
