<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\Common\Asserted;
use RuntimeException;

class FetchingObjectResolver implements ObjectResolverInterface
{
    public function __construct(private readonly ActivityPubClientInterface $activityPubClient)
    {
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

        return $this->activityPubClient->request('GET', $uri)
            ?? throw new RuntimeException('Could not resolve object');
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
