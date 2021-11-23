<?php

declare(strict_types=1);

namespace App\DTO;

class UserRegistered
{
    public function __construct(private int $clientId, private \DateTimeInterface $registeredAt)
    {
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getRegisteredAt(): \DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function toArray(): array
    {
        return [
            'clientId' => $this->clientId,
            'registeredAt' => $this->registeredAt
        ];
    }
}
