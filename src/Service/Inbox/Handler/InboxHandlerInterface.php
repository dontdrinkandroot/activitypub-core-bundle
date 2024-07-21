<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\Handler;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubRequest;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\ActivityPubResponse;

interface InboxHandlerInterface
{
    public function handle(ActivityPubRequest $request): ?ActivityPubResponse;
}
