<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Activity\ActivityType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Linkable\LinkableObject;

class Activity extends AbstractActivity
{
    public ?LinkableObject $object = null;

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return ActivityType::ACTIVITY->value;
    }
}
