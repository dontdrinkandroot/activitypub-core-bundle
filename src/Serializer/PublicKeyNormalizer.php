<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PublicKeyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    #[Override]
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === PublicKey::class && $format === ActivityStreamEncoder::FORMAT;
    }

    #[Override]
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): ?PublicKey
    {
        if (!is_object($data)) {
            return null;
        }

        return new PublicKey(
            id: Uri::fromString($data->id),
            owner: Uri::fromString($data->owner),
            publicKeyPem: $data->publicKeyPem
        );
    }

    #[Override]
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof PublicKey && $format === ActivityStreamEncoder::FORMAT;
    }

    #[Override]
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        assert($object instanceof PublicKey);

        return [
            'id' => (string)$object->id,
            'owner' => (string)$object->owner,
            'publicKeyPem' => $object->publicKeyPem
        ];
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PublicKey::class => true
        ];
    }
}
