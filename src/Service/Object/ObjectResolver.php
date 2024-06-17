<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\Common\Asserted;
use Override;
use RuntimeException;

class ObjectResolver implements ObjectResolverInterface
{
    /**
     * @param iterable<ObjectProviderInterface> $providers
     */
    public function __construct(
        private readonly iterable $providers,
    ) {
    }

    #[Override]
    public function resolve(Uri|LinkableObject $object, ?SignKey $signKey = null): ?CoreObject
    {
        if ($object instanceof LinkableObject) {
            if ($object->isObject()) {
                return Asserted::notNull($object->object);
            }
            $uri = Asserted::notNull($object->link?->href);
        } else {
            $uri = $object;
        }

        foreach ($this->providers as $provider) {
            $result = $provider->provide($uri, $signKey);
            if (false !== $result) {
                return $result;
            }
        }

        return null;
    }

    #[Override]
    public function resolveTyped(Uri|LinkableObject $object, string $type, ?SignKey $signKey = null): ?CoreObject
    {
        $resolvedObject = $this->resolve($object, $signKey);
        if (null === $resolvedObject) {
            return null;
        }
        if (!$resolvedObject instanceof $type) {
            throw new RuntimeException('Resolved object is not of type ' . $type);
        }

        return $resolvedObject;
    }
}
