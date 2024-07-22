<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Request\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Response\ActivityPubResponse;

interface InboxHandlerInterface
{
    public function handle(ActivityPubRequest $request): ?ActivityPubResponse;
}
