<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Source;
use Override;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SourceNormalizer implements NormalizerInterface, DenormalizerInterface
{
    #[Override]
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === Source::class && $format === ActivityStreamEncoder::FORMAT;
    }

    #[Override]
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): Source
    {
        return new Source(
            content: $data->content,
            mediaType: $data->mediaType
        );
    }

    #[Override]
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Source && $format === ActivityStreamEncoder::FORMAT;
    }

    #[Override]
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        assert($object instanceof Source);
        return [
            'content' => $object->content,
            'mediaType' => $object->mediaType
        ];
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Source::class => true
        ];
    }
}
