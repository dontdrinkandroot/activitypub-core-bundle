<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ScalarNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return (
                $type === 'string'
                || $type === 'int'
                || $type === 'float'
                || $type === 'bool'
            )
            && is_scalar($data);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        return match ($type) {
            'string' => (string)$data,
            'int' => (int)$data,
            'float' => (float)$data,
            'bool' => (bool)$data,
            default => throw new RuntimeException('Unsupported type: ' . $type)
        };
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_scalar($data);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): mixed
    {
        return $object;
    }
}
