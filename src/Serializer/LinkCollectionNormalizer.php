<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\LinkCollection;
use Override;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

class LinkCollectionNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    #[Override]
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === LinkCollection::class && $format === ActivityStreamEncoder::FORMAT
            && (is_string($data) || is_object($data) || is_array($data));
    }

    #[Override]
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): LinkCollection
    {
        $linkCollection = new LinkCollection();

        if (is_string($data) || is_object($data)) {
            $linkCollection->append($this->getSerializer()->denormalize($data, Link::class, $format));
        }

        if (is_array($data)) {
            foreach ($data as $item) {
                $linkCollection->append($this->getSerializer()->denormalize($item, Link::class, $format));
            }
        }

        return $linkCollection;
    }

    #[Override]
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof LinkCollection && $format === ActivityStreamEncoder::FORMAT;
    }

    #[Override]
    public function normalize(mixed $object, string $format = null, array $context = []): array|string
    {
        assert($object instanceof LinkCollection);

        if ($object->isSingleValued()) {
            $value = $this->getSerializer()->normalize($object[0], $format, $context);
            assert(is_string($value) || is_array($value));
            return $value;
        }

        $data = [];
        foreach ($object as $item) {
            $data[] = $this->getSerializer()->normalize($item, $format, $context);
        }
        return $data;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            LinkCollection::class => true
        ];
    }
}
