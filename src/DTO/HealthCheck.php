<?php

declare(strict_types=1);

namespace App\DTO;

class HealthCheck
{
    public const TIME_FORMAT = 'Y-d-s H:i:s';

    private \DateTime $time;

    public function __construct(\DateTime $time = null)
    {
        $this->time = $time ?: new \DateTime();
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function getTimeFormatted(): string
    {
        $time = clone $this->getTime();

        return $time->format(self::TIME_FORMAT);
    }
}
