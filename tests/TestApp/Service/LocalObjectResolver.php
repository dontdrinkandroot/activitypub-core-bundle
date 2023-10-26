<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
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
    public function getOwner(Uri $uri): LocalActorInterface|false|null
    {
        // TODO: Implement getOwner() method.
    }
}
