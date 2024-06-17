<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UriNormalizer implements NormalizerInterface, DenormalizerInterface
{
    #[Override]
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, Uri::class, true)
            && $format === ActivityStreamEncoder::FORMAT
            && is_string($data);
    }

    #[Override]
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): Uri
    {
        return Uri::fromString($data);
    }

    #[Override]
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_a($data, Uri::class)
            && $format === ActivityStreamEncoder::FORMAT;
    }

    #[Override]
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        assert($object instanceof Uri);
        return $object->__toString();
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Uri::class => true
        ];
    }
}
