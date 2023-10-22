<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\LocalObjectResolverInterface;
use RuntimeException;

class LocalObjectResolver implements LocalObjectResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function hasObject(Uri $uri): bool
    {
        // TODO: Implement hasObject() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function getObject(Uri $uri): ?CoreObject
    {
        // TODO: Implement getObject() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}
