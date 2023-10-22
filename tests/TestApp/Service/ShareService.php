<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\ShareServiceInterface;
use RuntimeException;

class ShareService implements ShareServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function shared(Uri $remoteActorId, Uri $localObjectId): void
    {
        // TODO: Implement shared() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function share(Uri $localActorId, LinkableObject $remoteObject): void
    {
        // TODO: Implement share() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}
