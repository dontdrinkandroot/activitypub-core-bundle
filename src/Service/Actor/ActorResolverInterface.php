<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface ActorResolverInterface
{
    public function resolveInbox(Uri $actorId): ?Uri;

    public function resolvePublicKey(Uri $actorId): ?string;
}
