<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Service\Share;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

interface ShareServiceInterface
{
    public function shared(Uri $remoteActorId, Uri $localObjectId): void;

    public function share(Uri $localActorId, LinkableObject $remoteObject): void;
}
