<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\ActivityType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;
use Override;

class Activity extends AbstractActivity
{
    public ?LinkableObject $object = null;

    #[Override]
    public function getType(): string
    {
        return ActivityType::ACTIVITY->value;
    }
}
