<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\JsonLdContext;
use Dontdrinkandroot\Common\Asserted;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

abstract class AbstractCoreTypeNormalizer
    implements SerializerAwareInterface, DenormalizerInterface, NormalizerInterface
{
    use SerializerAwareTrait;

    public function __construct(protected readonly TypeClassRegistry $typeClassRegistry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, CoreType::class, true)
            && $this->supportsType($type)
            && $this->supportsDenormalizationData($data)
            && $format === ActivityStreamEncoder::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): CoreType
    {
        assert(is_a($type, CoreType::class, true));
        return $this->denormalizeCoreType($data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return is_object($data)
            && is_a($data, CoreType::class)
            && $this->supportsType(get_class($data))
            && $format === ActivityStreamEncoder::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string
    {
        return $this->normalizeCoreType($object);
    }

    /**
     * @template T of CoreType
     * @param T $coreType
     * @param object $data
     * @return T
     */
    protected function populateFromData(CoreType $coreType, object $data): CoreType
    {
        if (null !== ($jsonLdContext = $data->{'@context'} ?? null)) {
            $coreType->jsonLdContext = $this->getSerializer()->denormalize(
                $jsonLdContext,
                JsonLdContext::class,
                ActivityStreamEncoder::FORMAT
            );
            unset($data->{'@context'});
        }

        $reflectionClass = new ReflectionClass($coreType);
        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $publicProperty) {
            $propertyName = $publicProperty->getName();
            if ('additionalProperties' === $propertyName) {
                continue;
            }
            if (null !== ($propertyData = $data->{$propertyName} ?? null)) {
                $value = $this->getPropertyValue($publicProperty, $propertyData);
                $publicProperty->setValue($coreType, $value);
                unset($data->{$propertyName});
            }
        }

        unset($data->type);
        if (count(get_object_vars($data)) > 0) {
            $coreType->additionalProperties = (array)$data;
        }

        return $coreType;
    }

    private function getPropertyValue(ReflectionProperty $publicProperty, mixed $propertyData): mixed
    {
        Asserted::instanceOf($propertyType = $publicProperty->getType(), ReflectionNamedType::class);
        $propertyTypeName = $propertyType->getName();

        /* Special handling for CoreObject as the class has to be determined from the type property */
        if (
            is_a($propertyTypeName, CoreObject::class, true)
            && null !== ($type = $propertyData->type ?? null)
            && $this->typeClassRegistry->hasType($type)
        ) {
            $subClass = $this->typeClassRegistry->getClass($type);
            return $this->getSerializer()->denormalize($propertyData, $subClass, ActivityStreamEncoder::FORMAT);
        }

        return $this->getSerializer()->denormalize($propertyData, $propertyTypeName, ActivityStreamEncoder::FORMAT);
    }

    protected function normalizeCoreType(CoreType $coreType): array|string
    {
        $data = [];

        if (null !== ($jsonLdContext = $coreType->jsonLdContext)) {
            $data['@context'] = $this->getSerializer()->normalize($jsonLdContext, ActivityStreamEncoder::FORMAT);
        }

        $data['type'] = $coreType->getType();

        $reflectionClass = new ReflectionClass($coreType);
        $publicProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($publicProperties as $publicProperty) {
            if (in_array($publicProperty->getName(), ['jsonLdContext', 'additionalProperties'])) {
                continue;
            }

            $value = $publicProperty->getValue($coreType);
            if (null !== $value) {
                $serializedValue = $this->getSerializer()->normalize($value, ActivityStreamEncoder::FORMAT);
                $data[$publicProperty->getName()] = $serializedValue;
            }
        }

        /* Additional properties */
        if (null !== ($additionalProperties = $coreType->additionalProperties)) {
            foreach ($additionalProperties as $key => $value) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    protected function supportsDenormalizationData(mixed $data): bool
    {
        return is_object($data);
    }

    /**
     * @param mixed $data
     * @param class-string<CoreType> $type
     * @param array $context
     * @return CoreType
     */
    abstract protected function denormalizeCoreType(mixed $data, string $type, array $context): CoreType;

    /**
     * @param class-string<CoreType> $class
     */
    abstract protected function supportsType(string $class): bool;
}
