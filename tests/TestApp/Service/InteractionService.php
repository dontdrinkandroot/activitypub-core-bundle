<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use RuntimeException;

class InteractionService implements InteractionServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function incoming(Uri $uri, string $type, Uri $remoteActorId, Uri $localObjectId): void
    {
        // TODO: Implement incoming() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}
