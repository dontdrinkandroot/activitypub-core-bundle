<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Override;
use RuntimeException;

class DeliveryService implements DeliveryServiceInterface
{
    #[Override]
    public function send(LocalActorInterface $localActor, Uri $recipientInbox, CoreType $payload): void
    {
        // TODO: Implement send() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }

    #[Override]
    public function sendQueued(?int $limit = null): void
    {
        // TODO: Implement sendQueued() method.
        throw new RuntimeException(__FUNCTION__ . ' not implemented');
    }
}
