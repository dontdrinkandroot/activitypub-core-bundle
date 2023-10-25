<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\AbstractActivity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Symfony\Component\HttpFoundation\Response;

interface InboxHandlerInterface
{
    public function handle(AbstractActivity $activity, Uri $signActorId, ?LocalActorInterface $inboxActor = null): ?Response;
}
