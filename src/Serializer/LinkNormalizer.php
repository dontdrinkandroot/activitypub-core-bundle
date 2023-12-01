<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Link;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;

class LinkNormalizer extends AbstractCoreTypeNormalizer
{
    /**
     * {@inheritdoc}
     */
    protected function supportsType(string $class): bool
    {
        return is_a($class, Link::class, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsDenormalizationData(mixed $data): bool
    {
        return is_string($data) || is_object($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function denormalizeCoreType(mixed $data, string $type, array $context): CoreType
    {
        assert(is_a($type, Link::class, true));
        $link = new $type();

        if (is_string($data)) {
            $link->href = Uri::fromString($data);
            return $link;
        }

        return $this->populateFromData($link, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function normalizeCoreType(CoreType $coreType): array|string
    {
        Asserted::instanceOf($coreType, Link::class);
        if ($coreType->hasOnlyHref()) {
            return (string)$coreType->href;
        }

        return parent::normalizeCoreType($coreType);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            Link::class => true
        ];
    }
}
