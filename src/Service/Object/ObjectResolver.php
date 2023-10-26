<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\Common\Asserted;
use RuntimeException;

class ObjectResolver implements ObjectResolverInterface
{
    /**
     * @param iterable<ObjectProviderInterface> $providers
     */
    public function __construct(
        private readonly iterable $providers,
        private readonly ActivityPubClientInterface $activityPubClient
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri|LinkableObject $object, ?SignKey $signKey = null): CoreType
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
            if (null !== $result) {
                throw new RuntimeException('Object was not found');
            }
            if (false !== $result) {
                return $result;
            }
        }

        throw throw new RuntimeException('No resolver could resolve object');
    }

    /**
     * {@inheritdoc}
     */
    public function resolveTyped(Uri|LinkableObject $object, string $type, ?SignKey $signKey = null): CoreType
    {
        $resolvedObject = $this->resolve($object, $signKey);
        if (!$resolvedObject instanceof $type) {
            throw new RuntimeException('Resolved object is not of type ' . $type);
        }

        return $resolvedObject;
    }
}
