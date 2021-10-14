<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Consumer\HealthCheckConsumer;
use Psr\Log\LoggerInterface;
use Sts\KafkaBundle\Event\PostMessageConsumedEvent;
use Sts\KafkaBundle\Event\PreMessageConsumedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HealthCheckEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreMessageConsumedEvent::getEventName(HealthCheckConsumer::NAME) => 'onPreMessageConsumed',
            PostMessageConsumedEvent::getEventName(HealthCheckConsumer::NAME) => 'onPostMessageConsumed'
        ];
    }

    public function onPreMessageConsumed(PreMessageConsumedEvent $event): void
    {
        $this->logger->info('Pre message count: ' . $event->getConsumedMessages());
        $this->logger->info('Pre time ms: ' . $event->getConsumptionTimeMs());
    }

    public function onPostMessageConsumed(PostMessageConsumedEvent $event): void
    {
        $this->logger->info('Post message count: ' . $event->getConsumedMessages());
        $this->logger->info('Post time ms: ' . $event->getConsumptionTimeMs());
    }
}
