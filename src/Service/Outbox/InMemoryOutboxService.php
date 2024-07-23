<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Outbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Override;

class InMemoryOutboxService implements OutboxServiceInterface
{
    private array $items = [];

    #[Override]
    public function addItem(LocalActorInterface $localActor, Uri|Activity $activity): void
    {
        $username = $localActor->getUsername();
        if (!array_key_exists($username, $this->items)) {
            $this->items[$username] = [];
        }

        $this->items[$username][] = $activity;
    }
}
