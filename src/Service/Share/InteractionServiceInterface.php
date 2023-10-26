<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Share;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface InteractionServiceInterface
{
    public function incoming(Uri $uri, string $type, Uri $remoteActorId, Uri $localObjectId): void;
}
