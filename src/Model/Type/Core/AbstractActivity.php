<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObjectsCollection;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use RuntimeException;

class AbstractActivity extends CoreObject
{
    public ?LinkableObjectsCollection $actor = null;

    public ?LinkableObjectsCollection $target = null;

    public ?CoreObject $result = null;

    public ?CoreObject $origin = null;

    public ?LinkableObjectsCollection $instrument = null;

    /**
     * Returns an Actor if there is exactly one or null otherwise.
     */
    public function getSingleActorOrNull(): ?LinkableObject
    {
        return $this->actor?->getSingleValue();
    }

    public function getSingleActor(): LinkableObject
    {
        return $this->getSingleActorOrNull() ?? throw new RuntimeException('None or multiple actors set');
    }

    /**
     * Returns the id of an Actor if there is exactly one or null otherwise.
     */
    public function getSingleActorIdOrNull(): ?Uri
    {
        return $this->getSingleActorOrNull()?->getId();
    }

    public function getSingleActorId(): Uri
    {
        return $this->getSingleActorIdOrNull() ?? throw new RuntimeException('None or multiple actors set');
    }
}
