<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

class ActivityStreamNormalizer implements SerializerAwareInterface, DenormalizerInterface
{
    use SerializerAwareTrait;

    public const NAMESPACE = 'https://www.w3.org/ns/activitystreams';

    public function __construct(private readonly TypeClassRegistry $typeClassRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return CoreType::class === $type
            && null !== ($activityPubType = $data->type ?? null)
            && is_string($activityPubType)
            && null !== ($jsonLdContext = $data->{'@context'} ?? null)
            && (
                ($jsonLdContext === self::NAMESPACE)
                || (is_array($jsonLdContext) && in_array(self::NAMESPACE, $jsonLdContext, true))
            );
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): CoreType
    {
        $class = $this->typeClassRegistry->getClass($data->type);
        return $this->getSerializer()->denormalize($data, $class, $format, $context);
    }
}
