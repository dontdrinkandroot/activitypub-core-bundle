<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\JsonLdContext;
use RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ContextNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return JsonLdContext::class === $type && ActivityStreamEncoder::FORMAT === $format;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): JsonLdContext
    {
        if (is_string($data)) {
            return new JsonLdContext([$data]);
        }

        if (is_array($data)) {
            $values = [];
            foreach ($data as $key => $value) {
                if (is_string($key)) {
                    $values[$key] = $value;
                } else {
                    $values[] = $value;
                }
            }
            return new JsonLdContext($values);
        }

        if (is_object($data)) {
            return new JsonLdContext([$data]);
        }

        throw new RuntimeException('Invalid JsonLdContext ' . gettype($data));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof JsonLdContext && ActivityStreamEncoder::FORMAT === $format;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): string|array
    {
        assert($object instanceof JsonLdContext);
        if (count($object->values) === 1) {
            return $this->normalizeValue($object->values[0]);
        }

        $values = [];
        foreach ($object->values as $key => $value) {
            $values[$key] = $this->normalizeValue($value);
        }

        return $values;
    }

    private function normalizeValue(string|object $value): string|array
    {
        if (is_string($value)) {
            return $value;
        }

        return (array)$value;
    }
}
