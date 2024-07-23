<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Outbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface OutboxServiceInterface
{
    public function addItem(LocalActorInterface $localActor, Activity|Uri $activity): void;
}
