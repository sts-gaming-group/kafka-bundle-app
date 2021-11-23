<?php

declare(strict_types=1);

namespace App\Consumer;

use App\DTO\UserRegistered;
use Psr\Log\LoggerInterface;
use StsGamingGroup\KafkaBundle\Client\Consumer\Exception\NullMessageException;
use StsGamingGroup\KafkaBundle\Client\Consumer\Message;
use StsGamingGroup\KafkaBundle\Client\Contract\ConsumerInterface;
use StsGamingGroup\KafkaBundle\RdKafka\Context;

class UserRegisteredConsumer implements ConsumerInterface
{
    public const NAME = 'user_registered';

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function consume(Message $message, Context $context): void
    {
        /** @var UserRegistered $userRegistered */
        $userRegistered = $message->getData();

        $this->logger->notice(
            sprintf(
                'Consumer: got message with client id %s | time registered %s | partition %s',
                $userRegistered->getClientId(),
                $userRegistered->getRegisteredAt()->format('Y-m-d H:i:s'),
                $message->getPartition()
            )
        );
    }

    public function handleException(\Exception $exception, Context $context): void
    {
        if ($exception instanceof NullMessageException) {
            $this->logger->info('Consumer: no more messages available.');

            return;
        }

        $this->logger->emergency($exception->getMessage());
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
