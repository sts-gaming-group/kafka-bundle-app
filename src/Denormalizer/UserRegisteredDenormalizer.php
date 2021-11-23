<?php

declare(strict_types=1);

namespace App\Denormalizer;

use App\DTO\UserRegistered;
use StsGamingGroup\KafkaBundle\Denormalizer\Contract\DenormalizerInterface;

final class UserRegisteredDenormalizer implements DenormalizerInterface
{
    public function denormalize($data): UserRegistered
    {
        return new UserRegistered(
            $data['clientId'],
            new \DateTime($data['registeredAt']['date'], new \DateTimeZone($data['registeredAt']['timezone']))
        );
    }
}
