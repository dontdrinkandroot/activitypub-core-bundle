<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $format === ActivityStreamEncoder::FORMAT
            && is_string($data)
            && is_a($type, DateTimeInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): DateTimeInterface {
        return new DateTime($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $format === ActivityStreamEncoder::FORMAT
            && $data instanceof DateTimeInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        $dateTime = $object;

        if ('+00:00' === $dateTime->format('P')) {
            return $dateTime->format('Y-m-d\TH:i:s\Z');
        }

        return $dateTime->format('Y-m-d\TH:i:sP');
    }
}
