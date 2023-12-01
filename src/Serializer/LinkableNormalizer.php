<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\CustomObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\AbstractLinkable;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

class LinkableNormalizer implements SerializerAwareInterface, NormalizerInterface, DenormalizerInterface
{
    use SerializerAwareTrait;

    public function __construct(private readonly TypeClassRegistry $typeClassRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, AbstractLinkable::class, true)
            && $format === ActivityStreamEncoder::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): AbstractLinkable
    {
        assert(is_a($type, AbstractLinkable::class, true));
        if (is_string($data)) {
            $link = new Link();
            $link->href = Uri::fromString($data);
            return new $type(link: $link);
        }

        if ($this->typeClassRegistry->hasType($data->type)) {
            $class = $this->typeClassRegistry->getClass($data->type);
            return new $type(object: $this->getSerializer()->denormalize($data, $class, $format));
        }

        return new $type(object: $this->getSerializer()->denormalize($data, CustomObject::class, $format));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof AbstractLinkable && $format === ActivityStreamEncoder::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string
    {
        if (null !== ($link = $object->link)) {
            return Asserted::notNull($link->href);
        }

        return Asserted::array($this->getSerializer()->normalize($object->object, $format));
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            AbstractLinkable::class => true
        ];
    }
}
