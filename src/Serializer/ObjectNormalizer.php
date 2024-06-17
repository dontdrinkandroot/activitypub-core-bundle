<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Serializer;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\CustomObject;
use Override;

class ObjectNormalizer extends AbstractCoreTypeNormalizer
{
    #[Override]
    protected function supportsType(string $class): bool
    {
        return is_a($class, CoreObject::class, true);
    }

    #[Override]
    protected function denormalizeCoreType(mixed $data, string $type, array $context): CoreType
    {
        if (CustomObject::class === $type) {
            $instance = new CustomObject($data->type);
        } else {
            $instance = new $type;
        }
        return $this->populateFromData($instance, $data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            CoreObject::class => true
        ];
    }
}
