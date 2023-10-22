<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface DeliveryServiceInterface
{
    public function send(LocalActorInterface $localActor, Uri $recipientInbox, CoreType $payload): void;

    public function sendQueued(?int $limit = null): void;
}
