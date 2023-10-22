<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\AbstractLinkableCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

class LinkableCollectionNormalizer implements SerializerAwareInterface, NormalizerInterface, DenormalizerInterface
{
    use SerializerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, AbstractLinkableCollection::class, true)
            && $format === ActivityStreamEncoder::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): AbstractLinkableCollection {
        assert(is_a($type, AbstractLinkableCollection::class, true), 'Expected Linkable type, got ' . $type);
        if (!is_array($data)) {
            return new $type(
                [$this->getSerializer()->denormalize($data, $type::getLinkableClass(), $format)]
            );
        }

        $result = [];
        foreach ($data as $item) {
            $result[] = $this->getSerializer()->denormalize($item, $type::getLinkableClass(), $format);
        }

        return new $type($result);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof AbstractLinkableCollection && $format === ActivityStreamEncoder::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string
    {
        assert($object instanceof AbstractLinkableCollection);
        if ($object->isSingleValued()) {
            $value = $this->getSerializer()->normalize($object[0], $format);
            assert(is_array($value) || is_string($value));
            return $value;
        }

        $result = [];
        foreach ($object as $item) {
            $result[] = $this->getSerializer()->normalize($item, $format);
        }
        return $result;
    }
}
